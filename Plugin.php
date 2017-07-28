<?php namespace VojtaSvoboda\UserSideMenu;

use Backend;
use Event;
use System\Classes\PluginBase;
use System\Classes\SettingsManager;
use VojtaSvoboda\UserSideMenu\Models\Settings;

class Plugin extends PluginBase
{
    public $require = [
        'RainLab.User',
    ];

    public function registerSettings()
    {
        return [
            'settings' => [
                'label'       => 'vojtasvoboda.usersidemenu::lang.settings.label',
                'description' => 'vojtasvoboda.usersidemenu::lang.settings.description',
                'category'    => SettingsManager::CATEGORY_USERS,
                'icon'        => 'icon-ellipsis-v',
                'class'       => 'VojtaSvoboda\UserSideMenu\Models\Settings',
                'order'       => 600,
                'permissions' => ['rainlab.users.access_settings'],
            ]
        ];
    }

    public function boot()
    {
        // Override backend menu
        Event::listen('backend.menu.extendItems', function($manager) {
            // Override main menu icon
            if ($base = Settings::getBaseIconSettings()) {
                $manager->addMainMenuItem('RainLab.User', 'user', [
                    'label'       => $base['label'],
                    'url'         => Backend::url($base['url']),
                    'icon'        => $base['icon'],
                    'iconSvg'     => null,
                    'permissions' => [$base['permissions']],
                    'order'       => intval($base['order']),
                ]);
            }

            // Add submenu to RainLab.User plugin
            $icons = $this->getSideMenuItems();
            if (!empty($icons)) {
                $manager->addSideMenuItems('RainLab.User', 'user', $icons);
            }
        });
    }

    private function getSideMenuItems()
    {
        $icons = [];

        if ($first = Settings::getFirstIconSettings()) {
            $icons['new_user'] = $this->getIconArray($first);
        }
        if ($second = Settings::getSecondIconSettings()) {
            $icons['users'] = $this->getIconArray($second);
        }
        if ($third = Settings::getThirdIconSettings()) {
            $icons['usergroups'] = $this->getIconArray($third);
        }
        if ($fourth = Settings::getFourthIconSettings()) {
            $icons['additional1'] = $this->getIconArray($fourth);
        }
        if ($fifth = Settings::getFifthIconSettings()) {
            $icons['additional2'] = $this->getIconArray($fifth);
        }

        return $icons;
    }

    private function getIconArray($data)
    {
        return [
            'label'       => $data['label'],
            'url'         => Backend::url($data['url']),
            'icon'        => $data['icon'],
            'permissions' => [$data['permissions']],
            'order'       => intval($data['order']),
        ];
    }
}
