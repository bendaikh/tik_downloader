@php
    $seoService = app(\App\Service\SeoService::class);
    $metaTags = $seoService->generateMetaTags($pageData ?? []);
    $structuredData = $seoService->generateStructuredData($pageData ?? []);
@endphp

{{-- Meta Tags --}}
@if(!empty($metaTags['title']))
    <title>{{ $metaTags['title'] }}</title>
@endif

@if(!empty($metaTags['description']))
    <meta name="description" content="{{ $metaTags['description'] }}">
@endif

@if(!empty($metaTags['keywords']))
    <meta name="keywords" content="{{ $metaTags['keywords'] }}">
@endif

@if(!empty($metaTags['author']))
    <meta name="author" content="{{ $metaTags['author'] }}">
@endif

@if(!empty($metaTags['robots']))
    <meta name="robots" content="{{ $metaTags['robots'] }}">
@endif

@if(!empty($metaTags['canonical']))
    <link rel="canonical" href="{{ $metaTags['canonical'] }}">
@endif

{{-- Open Graph Meta Tags --}}
@if(!empty($metaTags['og:title']))
    <meta property="og:title" content="{{ $metaTags['og:title'] }}">
@endif

@if(!empty($metaTags['og:description']))
    <meta property="og:description" content="{{ $metaTags['og:description'] }}">
@endif

@if(!empty($metaTags['og:image']))
    <meta property="og:image" content="{{ $metaTags['og:image'] }}">
@endif

@if(!empty($metaTags['og:type']))
    <meta property="og:type" content="{{ $metaTags['og:type'] }}">
@endif

@if(!empty($metaTags['og:url']))
    <meta property="og:url" content="{{ $metaTags['og:url'] }}">
@endif

@if(!empty($metaTags['og:site_name']))
    <meta property="og:site_name" content="{{ $metaTags['og:site_name'] }}">
@endif

{{-- Twitter Card Meta Tags --}}
@if(!empty($metaTags['twitter:card']))
    <meta name="twitter:card" content="{{ $metaTags['twitter:card'] }}">
@endif

@if(!empty($metaTags['twitter:site']))
    <meta name="twitter:site" content="{{ $metaTags['twitter:site'] }}">
@endif

@if(!empty($metaTags['twitter:creator']))
    <meta name="twitter:creator" content="{{ $metaTags['twitter:creator'] }}">
@endif

@if(!empty($metaTags['twitter:title']))
    <meta name="twitter:title" content="{{ $metaTags['twitter:title'] }}">
@endif

@if(!empty($metaTags['twitter:description']))
    <meta name="twitter:description" content="{{ $metaTags['twitter:description'] }}">
@endif

@if(!empty($metaTags['twitter:image']))
    <meta name="twitter:image" content="{{ $metaTags['twitter:image'] }}">
@endif

{{-- Structured Data (JSON-LD) --}}
@foreach($structuredData as $data)
    <script type="application/ld+json">
        {!! json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
@endforeach
