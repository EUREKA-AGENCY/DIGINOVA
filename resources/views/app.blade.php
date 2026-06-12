<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#1B2D8C">
        <meta name="description" content="Diginova — Solutions digitales sur mesure pour votre entreprise. Développement web, applications SaaS, transformation digitale.">

        <title inertia>{{ config('app.name', 'Diginova') }}</title>

        <!-- Favicons -->
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="alternate icon" href="/favicon.ico">
        <link rel="apple-touch-icon" href="/logo.svg">
        <link rel="manifest" href="/site.webmanifest">

        <!-- Open Graph -->
        <meta property="og:type"        content="website">
        <meta property="og:site_name"   content="Diginova">
        <meta property="og:title"       content="Diginova — Solutions Digitales">
        <meta property="og:description" content="Solutions digitales sur mesure pour votre entreprise. Développement web, SaaS, transformation digitale.">
        <meta property="og:image"       content="{{ config('app.url') }}/logo.svg">
        <meta property="og:url"         content="{{ config('app.url') }}">

        <!-- Twitter Card -->
        <meta name="twitter:card"        content="summary">
        <meta name="twitter:title"       content="Diginova — Solutions Digitales">
        <meta name="twitter:description" content="Solutions digitales sur mesure pour votre entreprise.">
        <meta name="twitter:image"       content="{{ config('app.url') }}/logo.svg">

        <!-- CSRF -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600|poppins:400,500,600,700,800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @routes
        @vite([
            'resources/js/app.js',
            "resources/js/pages/{$page['component']}.vue"
        ])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
