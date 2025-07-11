<?php

namespace App\Services;

use App\Models\Tenant;
use SimpleSoftwareIO\QrCode\Generator;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Illuminate\Support\Facades\Log;

class QrCodeService
{
    protected Generator $qrGenerator;
    protected ImageManager $imageManager;

    public function __construct()
    {
        $this->qrGenerator = app(Generator::class);
        // Force the QR code generator to use GD backend
        // $this->qrGenerator->setBackEnd('gd');
        
        // Initialize Intervention Image with GD driver (v3 syntax)
        $this->imageManager = ImageManager::gd();
    }

    /**
     * Generate QR code with tenant logo overlay
     */
    public function generateWithLogo(Tenant $tenant, string $format = 'svg', int $size = 300): string
    {
        $url = url("/t/{$tenant->slug}");
        
        // Only log debug info for SVG format to prevent interference with PNG binary data
        if ($format === 'svg') {
            $logoPath = $this->getLogoPath($tenant);
            Log::info('QR Code Generation Debug', [
                'tenant_id' => $tenant->id,
                'tenant_name' => $tenant->name,
                'has_logo_check' => $tenant->hasLogo(),
                'logo_path_found' => $logoPath,
                'logo_path_exists' => $logoPath ? file_exists($logoPath) : false,
                'format' => $format,
                'size' => $size
            ]);
        }
        
        try {
            // Generate base QR code as PNG and save to temporary file
            $tempQrPath = tempnam(sys_get_temp_dir(), 'qr_') . '.png';
            $qrCodePng = $this->qrGenerator
                ->format('png')
                ->size($size)
                ->errorCorrection('H') // High error correction for logo overlay
                ->margin(2)
                ->generate($url, $tempQrPath);

            // Load QR code with Intervention Image from file path
            $qrImage = $this->imageManager->read($tempQrPath);
            
            // Clean up temporary file
            if (file_exists($tempQrPath)) {
                unlink($tempQrPath);
            }
            
            // If tenant has a logo, overlay it
            if ($tenant->hasLogo()) {
                $logoPath = $this->getLogoPath($tenant);
                
                if ($logoPath && file_exists($logoPath)) {
                    $this->overlayLogoWithIntervention($qrImage, $logoPath, $size);
                    
                    // Log successful overlay for SVG format
                    if ($format === 'svg') {
                        Log::info('QR Code logo overlay successful', [
                            'tenant_id' => $tenant->id,
                            'logo_path_used' => $logoPath
                        ]);
                    }
                } else {
                    // Log failed overlay for SVG format
                    if ($format === 'svg') {
                        Log::warning('QR Code logo overlay failed - logo path not found or inaccessible', [
                            'tenant_id' => $tenant->id,
                            'logo_path_attempted' => $logoPath,
                            'path_exists' => $logoPath ? file_exists($logoPath) : false
                        ]);
                    }
                }
            }

            // Return in requested format with proper binary handling
            if ($format === 'svg') {
                $pngEncoder = $qrImage->toPng();
                $base64 = base64_encode($pngEncoder->toString());
                return $this->createSvgWrapper($base64, $size);
            } else {
                // For PNG format, ensure absolutely clean binary output
                $pngEncoder = $qrImage->toPng();
                return $pngEncoder->toString();
            }

        } catch (\Exception $e) {
            // Clean up temporary file on error
            if (isset($tempQrPath) && file_exists($tempQrPath)) {
                unlink($tempQrPath);
            }
            
            // Only log errors for non-PNG formats to prevent output interference
            if ($format !== 'png') {
                Log::warning('QR code logo overlay failed: ' . $e->getMessage(), [
                    'tenant_id' => $tenant->id,
                    'exception_class' => get_class($e),
                    'trace' => $e->getTraceAsString()
                ]);
            }
            
            // Return fallback QR code with proper format handling
            return $this->qrGenerator
                ->format($format)
                ->size($size)
                ->errorCorrection('H')
                ->margin(2)
                ->generate($url);
        }
    }

    /**
     * Get the best available logo path for the tenant
     */
    protected function getLogoPath(Tenant $tenant): ?string
    {
        $logoMedia = $tenant->getFirstMedia('logo');
        
        if (!$logoMedia) {
            return null;
        }

        // Try to get the original file path first
        try {
            $logoPath = $logoMedia->getPath();
            
            if (file_exists($logoPath)) {
                return $logoPath;
            }
        } catch (\Exception $e) {
            // Continue to try conversions
        }

        // If original doesn't exist, try to get URL and convert it to a local path
        try {
            $logoUrl = $logoMedia->getUrl();
            
            // Convert URL to local file path
            $publicPath = public_path('storage');
            $relativePath = str_replace(config('app.url') . '/storage/', '', $logoUrl);
            $localPath = $publicPath . '/' . $relativePath;
            
            if (file_exists($localPath)) {
                return $localPath;
            }
        } catch (\Exception $e) {
            // Continue to try conversions
        }

        // Try medium conversion (original format, not WebP)
        try {
            $mediumPath = $logoMedia->getPath('medium');
            if (file_exists($mediumPath)) {
                return $mediumPath;
            }
        } catch (\Exception $e) {
            // Continue
        }

        // Try thumbnail conversion
        try {
            $thumbPath = $logoMedia->getPath('thumb');
            if (file_exists($thumbPath)) {
                return $thumbPath;
            }
        } catch (\Exception $e) {
            // Continue
        }

        // Try to use the full path from storage
        try {
            $storagePath = storage_path('app/public/tenants/' . $tenant->id . '/logo/' . $logoMedia->file_name);
            if (file_exists($storagePath)) {
                return $storagePath;
            }
        } catch (\Exception $e) {
            // Final fallback failed
        }

        return null;
    }

    /**
     * Overlay logo on QR code using Intervention Image
     */
    protected function overlayLogoWithIntervention($qrImage, string $logoPath, int $qrSize): void
    {
        // Load the logo
        $logo = $this->imageManager->read($logoPath);
        
        // Calculate logo size (20% of QR code size)
        $logoSize = intval($qrSize * 0.2);
        
        // Resize logo maintaining aspect ratio (v3 syntax)
        $logo->scaleDown($logoSize, $logoSize);
        
        // Create a white circular background for better visibility
        $backgroundSize = $logoSize + 20;
        $background = $this->imageManager->create($backgroundSize, $backgroundSize);
        
        // Fill with white color
        $background->fill('ffffff');
        
        // Calculate center position for logo on background
        $logoX = intval(($backgroundSize - $logo->width()) / 2);
        $logoY = intval(($backgroundSize - $logo->height()) / 2);
        
        // Place logo on background
        $background->place($logo, 'top-left', $logoX, $logoY);
        
        // Calculate center position for logo+background on QR code
        $centerX = intval(($qrImage->width() - $backgroundSize) / 2);
        $centerY = intval(($qrImage->height() - $backgroundSize) / 2);
        
        // Place the logo with background on the QR code
        $qrImage->place($background, 'top-left', $centerX, $centerY);
    }

    /**
     * Create SVG wrapper for PNG image
     */
    protected function createSvgWrapper(string $base64Png, int $size): string
    {
        return sprintf(
            '<svg width="%d" height="%d" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 %d %d">
                <image width="%d" height="%d" href="data:image/png;base64,%s"/>
            </svg>',
            $size, $size, $size, $size, $size, $size, $base64Png
        );
    }

    /**
     * Generate simple QR code without logo
     */
    public function generateSimple(string $url, string $format = 'svg', int $size = 300): string
    {
        return $this->qrGenerator
            ->format($format)
            ->size($size)
            ->errorCorrection('M')
            ->margin(2)
            ->generate($url);
    }

    /**
     * Generate QR code and save as file
     */
    public function generateAndSave(Tenant $tenant, string $filePath, int $size = 300): bool
    {
        try {
            $qrCodeData = $this->generateWithLogo($tenant, 'png', $size);
            return file_put_contents($filePath, $qrCodeData) !== false;
        } catch (\Exception $e) {
            Log::error('Failed to save QR code file', [
                'tenant_id' => $tenant->id,
                'file_path' => $filePath,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Generate QR code specifically for download - minimal logging and error handling
     */
    public function generateForDownload(Tenant $tenant, int $size = 300): ?string
    {
        try {
            $url = url("/t/{$tenant->slug}");
            
            // Generate base QR code as PNG and save to temporary file
            $tempQrPath = tempnam(sys_get_temp_dir(), 'qr_download_') . '.png';
            $this->qrGenerator
                ->format('png')
                ->size($size)
                ->errorCorrection('H')
                ->margin(2)
                ->generate($url, $tempQrPath);

            // Load QR code with Intervention Image from file path
            $qrImage = $this->imageManager->read($tempQrPath);
            
            // Clean up temporary file
            if (file_exists($tempQrPath)) {
                unlink($tempQrPath);
            }
            
            // If tenant has a logo, overlay it (silently)
            if ($tenant->hasLogo()) {
                $logoPath = $this->getLogoPath($tenant);
                if ($logoPath && file_exists($logoPath)) {
                    $this->overlayLogoWithIntervention($qrImage, $logoPath, $size);
                }
            }

            // Return clean PNG binary data
            return $qrImage->toPng()->toString();
            
        } catch (\Throwable $e) {
            // Clean up temporary file on error
            if (isset($tempQrPath) && file_exists($tempQrPath)) {
                unlink($tempQrPath);
            }
            
            // Silent fallback - return simple QR code
            try {
                return $this->qrGenerator
                    ->format('png')
                    ->size($size)
                    ->errorCorrection('H')
                    ->margin(2)
                    ->generate($url);
            } catch (\Throwable $fallbackError) {
                return null;
            }
        }
    }
}