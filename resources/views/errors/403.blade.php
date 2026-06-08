<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Denegado</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="bg-white rounded-lg shadow-lg p-8 max-w-md text-center">
            <div class="text-6xl mb-4">🔒</div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Error 403</h1>
            <p class="text-gray-600 mb-6">
                No tienes permiso para acceder a este recurso. 
                Contacta al administrador si crees que esto es un error.
            </p>
            <div class="flex gap-3">
                <a href="{{ route('dashboard') }}" 
                   class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Ir al inicio
                </a>
            
            </div>
        </div>
    </div>
</body>
</html>