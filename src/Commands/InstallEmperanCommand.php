<?php

namespace Bale\Emperan\Commands;

use Illuminate\Console\Command;
use Bale\Emperan\Models\Section;
use Illuminate\Support\Str;

class InstallEmperanCommand extends Command
{
    protected $signature = 'emperan:install {name?}';
    protected $description = 'Install Bale Emperan package (publish config, migrate, seed, etc)';

    public function handle()
    {
        $name = $this->argument('name');

        $this->info('⚡ Installing Bale Emperan package...');

        // 1. Publish config
        $this->callSilent('vendor:publish', [
            '--provider' => "\\Bale\\Emperan\\EmperanServiceProvider",
            '--tag' => "emperan:config",
            '--force' => true,
        ]);
        $this->info('📦 Config published');

        // 3. Seed data
        $this->runSeed($name);
        $this->info('🌱 Seeder executed');

        $this->info('✅ Bale Emperan package installed successfully!');
    }

    public function runSeed($name)
    {
        $this->heroSection($name);
        $this->postSection($name);
        $this->footerSection($name);
    }

    public function heroSection($name)
    {
        Section::updateOrCreate(
            [
                'slug' => "hero-{$name}-section",
            ],
            [
                'name' => "Hero {$name} Section",
                'content' => [
                    'meta' => [
                        'title' => 'Hero Title',
                        'subtitle' => 'Hero Subtitle',
                        'button_1' => 'Button 1',
                        'button_1_url' => '#',
                        'button_2' => 'Button 2',
                        'button_2_url' => '#',
                    ],
                    'items' => [],
                ],
            ],
        );

    }

    public function postSection($name)
    {
        Section::updateOrCreate(
            [
                'slug' => "post-{$name}-section",
            ],
            [
                'name' => "Post {$name} Section",
                'content' => [
                    'meta' => [
                        'title' => 'Post Title',
                        'subtitle' => 'Post Subtitle',
                        'button' => 'More',
                        'button_url' => '#',
                    ],
                    'items' => [],
                ],
            ],
        );
    }

    public function footerSection($name)
    {
        Section::updateOrCreate(
            [
                'slug' => "footer-{$name}-section",
            ],
            [
                'name' => "Footer {$name} Section",
                'content' => [
                    'meta' => [
                        'socials' => [
                            [
                                'name' => 'Facebook',
                                'url' => 'https://facebook.com',
                            ],
                            [
                                'name' => 'Instagram',
                                'url' => 'https://instagram.com',
                            ],
                            [
                                'name' => 'Twitter',
                                'url' => 'https://twitter.com',
                            ],
                            [
                                'name' => 'Youtube',
                                'url' => 'https://youtube.com',
                            ],
                        ],
                        "contact" => [
                            "phone" => "08123456789",
                            "email" => "contact@bale.id",
                            "address" => "Jl. Pahlawan No. 1, Ponorogo",
                        ],
                        "link" => [
                            [
                                "name" => "Home",
                                "url" => "\/",
                            ],
                            [
                                "name" => "News",
                                "url" => "\/news",
                            ],
                        ]
                    ],
                    'items' => [],
                ],
            ],
        );
    }
}

