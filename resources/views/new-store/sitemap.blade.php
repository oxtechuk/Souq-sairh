{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
    {{-- Static Core Pages --}}
    <url>
        <loc>{{ route('new.home') }}</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ route('new.cars.index') }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>{{ route('new.offers.index') }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ route('new.calculator') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ route('new.compare') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    <url>
        <loc>{{ route('new.blog.index') }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ route('new.about') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    <url>
        <loc>{{ route('new.contact') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    <url>
        <loc>{{ route('new.booking') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>

    {{-- Cars --}}
    @foreach($cars as $car)
    <url>
        <loc>{{ route('new.cars.show', $car->slug) }}</loc>
        <lastmod>{{ $car->updated_at ? $car->updated_at->tz('UTC')->toAtomString() : now()->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
        @if($car->thumbnail)
        <image:image>
            <image:loc>{{ asset('storage/' . $car->thumbnail) }}</image:loc>
        </image:image>
        @endif
    </url>
    @endforeach

    {{-- Blog Posts --}}
    @foreach($blogs as $blog)
    <url>
        <loc>{{ route('new.blog.show', $blog->slug) }}</loc>
        <lastmod>{{ $blog->updated_at ? $blog->updated_at->tz('UTC')->toAtomString() : now()->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
        @if($blog->thumbnail)
        <image:image>
            <image:loc>{{ asset('storage/' . $blog->thumbnail) }}</image:loc>
        </image:image>
        @endif
    </url>
    @endforeach
</urlset>
