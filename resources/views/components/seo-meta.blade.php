{{--
Bale Emperan SEO Meta Component

Usage: <x-emperan::seo-meta :model="$post" />

The model should use HasSeoMeta trait.
Fallback values will be used if seo_meta is not set.
--}}

@props(['model' => null, 'defaults' => []])

@php
    // Get defaults or empty values
    $defaultTitle = $defaults['title'] ?? config('app.name', 'Website');
    $defaultDescription = $defaults['description'] ?? '';
    $defaultImage = $defaults['image'] ?? asset('img/og-image.jpg');
    $defaultKeywords = $defaults['keywords'] ?? '';

    // Get SEO values from model or use defaults
    if ($model && method_exists($model, 'getSeoTitle')) {
        $title = $model->getSeoTitle() ?: $defaultTitle;
        $description = $model->getSeoDescription() ?: $defaultDescription;
        $ogTitle = $model->getOgTitle() ?: $title;
        $ogDescription = $model->getOgDescription() ?: $description;
        $ogImage = $model->getOgImage() ?: $defaultImage;
        $keywords = $model->getSeoKeywords() ?: $defaultKeywords;
        $canonical = $model->getCanonicalUrl() ?: url()->current();
        $robots = $model->getSeoRobots();
        $structuredData = $model->getStructuredData();
    } else {
        $title = $defaultTitle;
        $description = $defaultDescription;
        $ogTitle = $title;
        $ogDescription = $description;
        $ogImage = $defaultImage;
        $keywords = $defaultKeywords;
        $canonical = url()->current();
        $robots = 'index, follow';
        $structuredData = null;
    }
@endphp

{{-- Basic Meta Tags --}}
<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}">
@if($keywords)
    <meta name="keywords" content="{{ $keywords }}">
@endif
<meta name="robots" content="{{ $robots }}">

{{-- Canonical URL --}}
<link rel="canonical" href="{{ $canonical }}">

{{-- Open Graph / Facebook --}}
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:title" content="{{ $ogTitle }}">
<meta property="og:description" content="{{ $ogDescription }}">
@if($ogImage)
    <meta property="og:image" content="{{ str_starts_with($ogImage, 'http') ? $ogImage : url($ogImage) }}">
@endif

{{-- Twitter Card --}}
<meta name="twitter:card" content="{{ $model?->seoMeta?->twitter_card ?? 'summary_large_image' }}">
<meta name="twitter:url" content="{{ url()->current() }}">
<meta name="twitter:title" content="{{ $ogTitle }}">
<meta name="twitter:description" content="{{ $ogDescription }}">
@if($ogImage)
    <meta name="twitter:image" content="{{ str_starts_with($ogImage, 'http') ? $ogImage : url($ogImage) }}">
@endif

{{-- Structured Data (JSON-LD) --}}
@if($structuredData)
    <script type="application/ld+json">
    {!! json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>
@elseif($model)
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Article",
        "headline": "{{ $title }}",
        "description": "{{ $description }}",
        @if($ogImage)
            "image": "{{ str_starts_with($ogImage, 'http') ? $ogImage : url($ogImage) }}",
        @endif
        "url": "{{ url()->current() }}",
        "dateModified": "{{ $model->updated_at ?? now()->toIso8601String() }}"
    }
    </script>
@endif