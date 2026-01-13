# Bale Landing Page Backend

[![Latest Version on Packagist](https://img.shields.io/packagist/v/paparee/bale-emperan.svg?style=flat-square)](https://packagist.org/packages/paparee/bale-emperan)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/paparee/bale-emperan/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/paparee/bale-emperan/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/paparee/bale-emperan/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/paparee/bale-emperan/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/paparee/bale-emperan.svg?style=flat-square)](https://packagist.org/packages/paparee/bale-emperan)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/bale-emperan.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/bale-emperan)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require paparee/bale-emperan
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="emperan:migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="emperan:config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="emperan:views"
```

## Usage

### SEO Meta Tags

Model apapun dapat mendukung SEO dengan menggunakan trait `HasSeoMeta`:

```php
use Bale\Emperan\Traits\HasSeoMeta;

class YourModel extends Model
{
    use HasSeoMeta;
}
```

Model bawaan yang sudah mendukung SEO:

- `Post` - untuk artikel/berita
- `Page` - untuk halaman statis
- `Section` - untuk section landing page

### Menggunakan SEO Component di Blade

Gunakan component `<x-emperan::seo-meta>` di layout Blade Anda:

```blade
<head>
    {{-- Dengan model --}}
    <x-emperan::seo-meta :model="$post" />

    {{-- Dengan fallback defaults --}}
    <x-emperan::seo-meta
        :model="$post"
        :defaults="[
            'title' => 'Default Title',
            'description' => 'Default description',
            'image' => asset('img/default-og.jpg'),
        ]"
    />
</head>
```

Component akan otomatis render:

- Title dan meta description
- Open Graph tags (Facebook)
- Twitter Card tags
- Canonical URL
- Robots meta (noindex/nofollow)
- JSON-LD structured data

### Menyimpan SEO Meta ke Database

```php
// Create/update SEO meta
$post->updateSeoMeta([
    'title' => 'Custom SEO Title',
    'description' => 'Custom meta description for search engines',
    'keywords' => 'keyword1, keyword2, keyword3',
    'og_title' => 'Custom Open Graph Title',
    'og_description' => 'Description for social media',
    'og_image' => 'https://example.com/og-image.jpg',
    'canonical_url' => 'https://example.com/canonical',
    'no_index' => false,
    'no_follow' => false,
]);

// Akses SEO data
$post->getSeoTitle();
$post->getSeoDescription();
$post->getOgImage();
$post->getSeoKeywords();
```

### Sitemap XML

Sitemap otomatis diakses di:

- `/sitemap.xml` - Sitemap index
- `/sitemap-posts.xml` - Sitemap untuk posts
- `/sitemap-pages.xml` - Sitemap untuk pages

Sitemap akan otomatis mengambil data dari database dengan:

- `lastmod` dari `updated_at`
- `changefreq` dan `priority` yang optimal
- Image sitemap extension untuk thumbnail

### Robots.txt

Robots.txt dinamis diakses di `/robots.txt` dengan:

- Allow semua crawler
- Disallow admin, API, dan auth paths
- Sitemap URL otomatis

### Menambahkan SEO ke Model Custom

Untuk menambahkan SEO ke model custom Anda:

```php
// 1. Tambahkan trait ke model
use Bale\Emperan\Traits\HasSeoMeta;

class Event extends Model
{
    use HasSeoMeta;

    // Model harus punya 'title' untuk fallback default
}

// 2. (Opsional) Tambahkan ke sitemap dengan extend SitemapController
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Papa Ree](https://github.com/paparee)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
