<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
@foreach($posts as $post)
    <url>
        <loc>{{ url('/berita/' . $post->slug) }}</loc>
        <lastmod>{{ \Carbon\Carbon::parse($post->getRawOriginal('updated_at'))->toW3cString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
        @if($post->thumbnail)
        <image:image>
            <image:loc>{{ url($post->thumbnail) }}</image:loc>
            <image:title>{{ htmlspecialchars($post->title, ENT_XML1, 'UTF-8') }}</image:title>
        </image:image>
        @endif
    </url>
@endforeach
</urlset>
