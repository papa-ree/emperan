<?php

namespace Bale\Emperan\Controllers;

use Bale\Emperan\Models\Page;
use Bale\Emperan\Models\Post;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class SitemapController extends Controller
{
    /**
     * Generate sitemap index
     */
    public function index(): Response
    {
        $baseUrl = url('/');

        $sitemaps = [
            [
                'loc' => url('/sitemap-posts.xml'),
                'lastmod' => Post::where('published', true)->latest('updated_at')->value('updated_at'),
            ],
            [
                'loc' => url('/sitemap-pages.xml'),
                'lastmod' => Page::latest('updated_at')->value('updated_at'),
            ],
        ];

        $content = view('emperan::sitemap.index', compact('sitemaps', 'baseUrl'))->render();

        return response($content, 200, [
            'Content-Type' => 'application/xml',
        ]);
    }

    /**
     * Generate posts sitemap
     */
    public function posts(): Response
    {
        $posts = Post::where('published', true)
            ->select(['id', 'slug', 'title', 'thumbnail', 'updated_at'])
            ->orderBy('updated_at', 'desc')
            ->get();

        $content = view('emperan::sitemap.posts', compact('posts'))->render();

        return response($content, 200, [
            'Content-Type' => 'application/xml',
        ]);
    }

    /**
     * Generate pages sitemap
     */
    public function pages(): Response
    {
        $pages = Page::select(['id', 'slug', 'title', 'updated_at'])
            ->orderBy('updated_at', 'desc')
            ->get();

        $content = view('emperan::sitemap.pages', compact('pages'))->render();

        return response($content, 200, [
            'Content-Type' => 'application/xml',
        ]);
    }
}
