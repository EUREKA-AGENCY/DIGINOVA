<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#0A2422">
        <meta name="robots" content="index, follow">
        <meta name="author" content="Diginova">
        <meta name="geo.region" content="CM-CE">
        <meta name="geo.placename" content="Yaoundé, Cameroun">

        <meta name="description" content="Diginova — Agence de développement web et SaaS à Yaoundé. Applications sur mesure, microservices, DevOps. 8 ans d'expérience · 19+ projets en production.">

        <title inertia>{{ config('app.name', 'Diginova') }}</title>

        <!-- Canonical -->
        <link rel="canonical" href="{{ config('app.url') }}{{ request()->getPathInfo() }}">

        <!-- Favicons -->
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="icon" href="/favicon.ico" sizes="32x32">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">
        <link rel="manifest" href="/site.webmanifest">

        <!-- Open Graph -->
        <meta property="og:type"        content="website">
        <meta property="og:locale"      content="fr_CM">
        <meta property="og:site_name"   content="Diginova">
        <meta property="og:title"       content="Diginova — Développement Web & SaaS | Yaoundé, Cameroun">
        <meta property="og:description" content="Agence de développement web et SaaS à Yaoundé. Applications sur mesure, microservices, DevOps. 8 ans d'expérience · 19+ projets en production.">
        <meta property="og:image"       content="{{ config('app.url') }}/og-image.png">
        <meta property="og:image:width"  content="1200">
        <meta property="og:image:height" content="630">
        <meta property="og:url"         content="{{ config('app.url') }}">

        <!-- Twitter Card -->
        <meta name="twitter:card"        content="summary_large_image">
        <meta name="twitter:title"       content="Diginova — Développement Web & SaaS | Yaoundé, Cameroun">
        <meta name="twitter:description" content="Agence de développement web et SaaS à Yaoundé. Applications sur mesure, microservices, DevOps.">
        <meta name="twitter:image"       content="{{ config('app.url') }}/og-image.png">

        <!-- CSRF -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600|space-grotesk:500,600,700&display=swap" rel="stylesheet">

        <!-- JSON-LD Structured Data -->
        @php
            $base = config('app.url');
            $jsonLd = [
                '@context' => 'https://schema.org',
                '@graph'   => [
                    [
                        '@type'              => ['Organization', 'ProfessionalService'],
                        '@id'                => $base . '/#organization',
                        'name'               => 'Diginova',
                        'url'                => $base,
                        'logo'               => ['@type' => 'ImageObject', 'url' => $base . '/logo.png'],
                        'image'              => $base . '/og-image.png',
                        'description'        => 'Agence de développement web et solutions SaaS à Yaoundé, Cameroun. Développement sur mesure, microservices, DevOps, transformation digitale.',
                        'priceRange'         => '$$',
                        'currenciesAccepted' => 'XAF',
                        'areaServed'         => ['@type' => 'Country', 'name' => 'Cameroun'],
                        'address'            => [
                            '@type'           => 'PostalAddress',
                            'addressLocality' => 'Yaoundé',
                            'addressRegion'   => 'Centre',
                            'addressCountry'  => 'CM',
                        ],
                        'contactPoint' => [
                            '@type'             => 'ContactPoint',
                            'telephone'         => '+237-655-065-494',
                            'contactType'       => 'customer service',
                            'email'             => 'contact@diginova.cm',
                            'availableLanguage' => 'French',
                        ],
                        'sameAs'            => ['https://github.com/EUREKA-AGENCY', 'https://wa.me/237655065494'],
                        'foundingDate'      => '2017',
                        'numberOfEmployees' => ['@type' => 'QuantitativeValue', 'value' => 5],
                    ],
                    [
                        '@type'     => 'WebSite',
                        '@id'       => $base . '/#website',
                        'url'       => $base,
                        'name'      => 'Diginova',
                        'publisher' => ['@id' => $base . '/#organization'],
                        'inLanguage'=> 'fr-CM',
                    ],
                    [
                        '@type'       => 'WebPage',
                        '@id'         => $base . '/#webpage',
                        'url'         => $base,
                        'name'        => 'Diginova — Développement Web & Solutions SaaS | Yaoundé, Cameroun',
                        'description' => "Agence de développement web et SaaS à Yaoundé. Applications sur mesure, microservices, DevOps. 8 ans d'expérience, 19+ projets en production.",
                        'isPartOf'    => ['@id' => $base . '/#website'],
                        'about'       => ['@id' => $base . '/#organization'],
                        'inLanguage'  => 'fr-CM',
                        'breadcrumb'  => [
                            '@type'           => 'BreadcrumbList',
                            'itemListElement' => [
                                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Accueil', 'item' => $base],
                            ],
                        ],
                    ],
                ],
            ];
        @endphp
        <script type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>

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
