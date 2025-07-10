<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Restaurant Not Found</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md mx-auto text-center">
        <div class="mb-8">
            <h1 class="text-6xl font-bold text-gray-800 mb-4">404</h1>
            <h2 class="text-2xl font-semibold text-gray-700 mb-2">Restaurant Not Found</h2>
            <p class="text-gray-600">
                The restaurant you're looking for doesn't exist or is currently unavailable.
            </p>
        </div>
        
        <div class="space-y-4">
            <div class="text-sm text-gray-500">
                <p>If you think this is an error, please contact the administrator.</p>
            </div>
            
            <a href="{{ url('/admin') }}" 
               class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                Go to Admin Dashboard
            </a>
        </div>
    </div>
</body>
</html>