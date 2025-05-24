<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Lazysizes -->
    <script>
        window.lazySizesConfig = window.lazySizesConfig || {};
        lazySizesConfig.loadMode = 1; // Eager loading mode
        lazySizesConfig.expFactor = 4; // Load images earlier
        lazySizesConfig.expand = 500; // Expand the preload area
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js" async></script>

    <!-- Custom styles for lazy loading -->
    <style>
        .lazyload {
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }
        
        .lazyloaded {
            opacity: 1;
        }
        
        .lazyload-placeholder {
            opacity: 1;
            transition: opacity 0.3s ease-in-out;
        }
        
        .lazyloaded + .lazyload-placeholder {
            opacity: 0;
        }
        
        .shimmer {
            background: linear-gradient(
                90deg,
                rgba(255,255,255,0) 0%,
                rgba(255,255,255,0.2) 50%,
                rgba(255,255,255,0) 100%
            );
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }
        
        @keyframes shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
    </style>

    <!-- IntersectionObserver polyfill -->
    <script>
        if (!('IntersectionObserver' in window)) {
            document.write('<script src="https://polyfill.io/v3/polyfill.min.js?features=IntersectionObserver"><\/script>');
        }
    </script>
</head>

<body class="bg-white text-gray-900 font-sans">
    @include('layouts.partials.navbar')
    <main>
        @yield('content')
    </main>
    @include('layouts.partials.footer')
</body>

</html>