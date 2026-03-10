<?php

namespace Bale\Emperan\Commands;

use Bale\Emperan\Models\Option;
use Illuminate\Console\Command;
use Bale\Emperan\Models\Section;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use function Laravel\Prompts\select;

class InstallEmperanCommand extends Command
{
    protected $signature = 'emperan:install';
    protected $description = 'Install Bale Emperan package (publish config, migrate, seed, etc)';

    public function handle()
    {
        $this->info('⚡ Installing Bale Emperan package...');

        // 1. Publish config
        $this->callSilent('vendor:publish', [
            '--provider' => "\\Bale\\Emperan\\EmperanServiceProvider",
            '--tag' => "emperan:config",
            '--force' => true,
        ]);
        $this->info('📦 Config published');

        // 2. Choice of Seeding
        $seedOption = $this->choice(
            'What would you like to seed?',
            ['All', 'Core only', 'Role-Permission only'],
            0
        );

        // 3. Seed data
        if (in_array($seedOption, ['All', 'Core only'])) {
            $this->runSeed();
            $this->info('🌱 Core seeder executed');
        }

        if (in_array($seedOption, ['All', 'Role-Permission only'])) {
            $this->runRolePermissionSeed();
            $this->info('🔑 Role & Permission seeder executed');
        }

        $this->info('✅ Bale Emperan package installed successfully!');
    }

    public function runSeed()
    {
        $this->heroSection();
        $this->postSection();
        $this->footerSection();
        $this->optionValue();
    }

    public function heroSection()
    {
        Section::updateOrCreate(
            [
                'slug' => "hero-section",
            ],
            [
                'name' => "Hero Section",
                'content' => [
                    'meta' => [
                        'title' => 'Hero Title',
                        'subtitle' => 'Hero Subtitle',
                        'organization' => 'Bale Content Management System',
                        'button_1' => [
                            'label' => 'Button 1',
                            'url' => '#',
                        ],
                        'button_2' => [
                            'label' => 'Button 2',
                            'url' => '#',
                        ],
                    ],
                    'items' => [],
                ],
            ],
        );

    }

    public function postSection()
    {
        Section::updateOrCreate(
            [
                'slug' => "post-section",
            ],
            [
                'name' => "Post Section",
                'content' => [
                    'meta' => [
                        'title' => 'Post Title',
                        'subtitle' => 'Post Subtitle',
                        'button' => [
                            'label' => 'More',
                            'url' => '#',
                        ],
                    ],
                    'items' => [],
                ],
            ],
        );
    }

    public function footerSection()
    {
        Section::updateOrCreate(
            [
                'slug' => "footer-section",
            ],
            [
                'name' => "Footer Section",
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

    public function optionValue()
    {
        Option::updateOrCreate(
            ['name' => 'url'],
            ['value' => 'http://localhost:8000']
        );

        Option::updateOrCreate(
            ['name' => 'organization_slug'],
            ['value' => 'bale-content-management-system']
        );
    }

    public function runRolePermissionSeed()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $pages = ['Post', 'Page', 'Navigation', 'Section', 'Role', 'Permission'];
        $actions = ['create', 'read', 'update', 'delete'];
        $allPermissions = [];
        $postPermissions = [];

        foreach ($pages as $page) {
            foreach ($actions as $action) {
                $permissionName = strtolower($page) . ".{$action}";
                $allPermissions[] = $permissionName;
                Permission::firstOrCreate(['name' => $permissionName]);

                if (strtolower($page) === 'post') {
                    $postPermissions[] = $permissionName;
                }
            }
        }

        $root = Role::firstOrCreate(['name' => 'root']);
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $user = Role::firstOrCreate(['name' => 'user']);

        $root->syncPermissions($allPermissions);
        $admin->syncPermissions($allPermissions);
        $user->syncPermissions($postPermissions);
    }
}

