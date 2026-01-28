# Bale Landing Page Backend

[![Latest Version on Packagist](https://img.shields.io/packagist/v/bale/emperan.svg?style=flat-square)](https://packagist.org/packages/bale/emperan)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/bale/emperan/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/bale/emperan/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/bale/emperan/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/bale/emperan/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/bale/emperan.svg?style=flat-square)](https://packagist.org/packages/bale/emperan)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/bale-emperan.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/bale-emperan)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

Anda dapat menginstal paket ini melalui composer:

```bash
composer require bale/emperan
```

Setelah paket terinstal, jalankan perintah instalasi untuk mempublikasikan konfigurasi, menjalankan migrasi, dan melakukan seeding data awal:

```bash
# Instalasi standar (membuat section default: hero, post, footer)
php artisan emperan:install

# Instalasi dengan nama section kustom (misal: hero-nama_organisasi-section, dst)
php artisan emperan:install nama_organisasi
```

Perintah di atas akan otomatis melakukan:

1. Publish konfigurasi `emperan:config`.
2. Seeding data `Section` dengan struktur JSON `content` (meta & items).

Jika Anda ingin menjalankan langkah-langkah secara manual:

```bash
# Publish migrasi
php artisan vendor:publish --tag="emperan:migrations"

# Jalankan migrasi
php artisan migrate

# Publish config
php artisan vendor:publish --tag="emperan:config"
php artisan vendor:publish --tag="emperan:landing-page"
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

### CDN Support

Package ini menyediakan integrasi CDN untuk mempercepat loading asset statis.

#### Konfigurasi

Tambahkan variabel berikut di file `.env` Anda:

```env
EMPERAN_CDN_ENABLED=true
EMPERAN_CDN_URL=https://cdn.bale.co.id
EMPERAN_CDN_PREFIX=bale
```

#### Helper Functions

Tersedia helper global untuk memudahkan akses asset via CDN:

- `cdn_asset(string $path)`: Generate full URL ke asset di CDN.
- `cdn_url(string $path)`: Alias dari `cdn_asset()`.
- `cdn_enabled()`: Mengecek apakah CDN sedang aktif.

#### Logic Path CDN

URL yang dihasilkan mengikuti format:
`{EMPERAN_CDN_URL}/{EMPERAN_CDN_PREFIX}/{slug_organisasi}/{path}`

**Pengecualian:**
Jika path diawali dengan `shared/`, maka `{slug_organisasi}` akan dihilangkan dari URL.
Contoh: `cdn_asset('shared/logo.png')` -> `https://cdn.bale.co.id/bale/shared/logo.png`

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
