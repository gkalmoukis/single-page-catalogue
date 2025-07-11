<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Services\QrCodeService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class QrCodeController extends Controller
{
    public function download(Tenant $tenant)
    {
        // Disable error reporting temporarily to prevent any output
        $originalErrorReporting = error_reporting(0);
        
        // Turn off output buffering completely
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        try {
            $qrCodeService = app(QrCodeService::class);
            
            // Generate QR code with minimal error handling to prevent any output
            $qrCodeData = $qrCodeService->generateForDownload($tenant, 300);
            
            if (!$qrCodeData) {
                // Restore error reporting
                error_reporting($originalErrorReporting);
                abort(500, 'Failed to generate QR code');
            }
            
            $filename = "qr-code-{$tenant->slug}.png";
            
            // Create a clean response with minimal headers
            return response()->make($qrCodeData, 200, [
                'Content-Type' => 'image/png',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Content-Length' => strlen($qrCodeData),
            ]);
            
        } catch (\Throwable $e) {
            // Restore error reporting
            error_reporting($originalErrorReporting);
            
            // Log error after restoring error reporting
            Log::error('QR Code download failed: ' . $e->getMessage());
            abort(500, 'Failed to generate QR code');
        } finally {
            // Always restore error reporting
            error_reporting($originalErrorReporting);
        }
    }
}