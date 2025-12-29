<?php

namespace Paparee\BaleEmperan\Commands;

use Illuminate\Console\Command;
use Paparee\BaleEmperan\Models\Section;

class InstallBaleEmperanCommand extends Command
{
    protected $signature = 'emperan:install';
    protected $description = 'Install Bale Emperan package (publish config, migrate, seed, etc)';

    public function handle()
    {
        $this->info('⚡ Installing Bale Emperan package...');

        // 1. Publish config
        $this->callSilent('vendor:publish', [
            '--provider' => "Paparee\\BaleEmperan\\BaleEmperanServiceProvider",
            '--tag' => "bale-emperan:config",
            '--force' => true,
        ]);
        $this->info('📦 Config published');

        // 3. Seed data
        $this->runSeed();
        $this->info('🌱 Seeder executed');

        $this->info('✅ Bale Emperan package installed successfully!');
    }

    public function runSeed()
    {
        $this->heroSection();
        $this->postSection();
        $this->footerSection();
    }

    public function heroSection()
    {
        Section::updateOrCreate(
            [
                'slug' => 'hero-section',
            ],
            [
                'name' => 'Hero Section',
                'slug' => 'hero-section',
                'content' => [
                    'show_organization' => true,
                    'organization' => 'Bale CMS',

                    'show_title' => true,
                    'title' => 'Bale Content Management System',

                    'show_subtitle' => true,
                    'subtitle' => 'Manajemen Konten yang mudah dan Modern',

                    'backgrounds' => [
                        [
                            'type' => 'image',
                            'path' => 'uploads/hero/hero-1.jpg',
                            'alt' => 'Bale CMS',
                            'caption' => 'Background 1',
                            'position' => 'center',
                        ],
                        [
                            'type' => 'image',
                            'path' => 'uploads/hero/hero-2.jpg',
                            'alt' => 'Bale CMS 2',
                            'caption' => null,
                            'position' => 'center',
                        ]
                    ],

                    'buttons' => [
                        [
                            'show' => true,
                            'label' => 'Button 1',
                            'url' => '/portal',
                            'target' => '_self',
                            'class' => 'bg-secondary text-primary hover:bg-secondary/90 font-semibold text-base px-8 py-4 rounded-lg shadow-lg transition-all duration-300 hover:shadow-xl hover:scale-105 inline-flex items-center justify-center gap-2'
                        ],
                        [
                            'show' => true,
                            'label' => 'Button 2',
                            'url' => '/programs',
                            'target' => '_self',
                            'class' => 'bg-white/10 text-white border-2 border-white/30 hover:bg-white/20 backdrop-blur-sm font-semibold text-base px-8 py-4 rounded-lg transition-all duration-300 hover:scale-105'
                        ]
                    ],

                    'layout_options' => [
                        'align' => 'left',
                        'text_width' => 'md'
                    ],
                ],
            ],
        );

    }

    public function postSection()
    {
        Section::updateOrCreate(
            [
                'slug' => 'post-section',
            ],
            [
                'name' => 'Post Section',
                'slug' => 'post-section',
                'content' => [
                    'show_title' => true,
                    'title' => 'News & Announcements',

                    'show_subtitle' => true,
                    'subtitle' => 'Stay updated with the latest developments in Ponorogo',

                    'layouts' => [
                        'post_limit' => 3,
                        'grid' => 'grid-3'
                    ],

                    'buttons' => [
                        [
                            'show' => true,
                            'label' => 'See All News',
                            'url' => '/news',
                            'target' => '_self',
                            'class' => 'bg-secondary text-primary hover:bg-secondary/90 font-semibold text-base px-8 py-4 rounded-lg shadow-lg transition-all duration-300 hover:shadow-xl hover:scale-105 inline-flex items-center justify-center gap-2'
                        ]
                    ],

                    'is_active' => true,
                ],
            ],
        );
    }

    public function footerSection()
    {
        Section::updateOrCreate(
            [
                'slug' => 'footer-section',
            ],
            [
                'name' => 'Footer Section',
                'slug' => 'footer-section',
                'content' => [
                    [
                        "title" => "Quick Links",
                        "type" => "link",
                        "items" => [
                            ["label" => "Home", "url" => "/"],
                            ["label" => "News", "url" => "#news"]
                        ]
                    ],
                    [
                        "title" => "Contact",
                        "type" => "list",
                        "items" => [
                            ["label" => "Jl. Soekarno Hatta 123"],
                            ["label" => "+62 352 481234"],
                            ["label" => "cms@bale.id"]
                        ]
                    ],
                    [
                        "title" => "Social",
                        "type" => "social",
                        "items" => [
                            ["label" => "Facebook", "url" => "https://fb.com"],
                            ["label" => "Instagram", "url" => "https://ig.com"]
                        ]
                    ]
                ],
                'is_active' => true
            ]
        );
    }
}

