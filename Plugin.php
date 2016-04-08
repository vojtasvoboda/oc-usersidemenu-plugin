<?php namespace VojtaSvoboda\UserSideMenu;

use Backend;
use Event;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public $require = ['RainLab.User'];

    public function boot()
    {
        // override backend menus
        Event::listen('backend.menu.extendItems', function($manager)
        {
            // move RainLab.User more to the right
            $manager->addMainMenuItem('RainLab.User', 'user', [
                'label' => 'rainlab.user::lang.users.menu_label',
                'url' => Backend::url('rainlab/user/users'),
                'icon' => 'icon-user',
                'permissions' => ['rainlab.users.*'],
                'order' => 500,
            ]);

            // add submenu to RainLab.User plugin
            $manager->addSideMenuItems('RainLab.User', 'user', [
                'users' => [
                    'label' => 'rainlab.user::lang.users.menu_label',
                    'url' => Backend::url('rainlab/user/users'),
                    'icon' => 'icon-user',
                    'permissions' => ['rainlab.users.access_users'],
                    'order' => 100,
                ],
                'usergroups' => [
                    'label' => 'rainlab.user::lang.groups.menu_label',
                    'url' => Backend::url('rainlab/user/usergroups'),
                    'icon' => 'icon-users',
                    'permissions' => ['rainlab.users.access_groups'],
                    'order' => 200,
                ],
            ]);
        });
    }
}
