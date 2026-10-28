<?php

/**
 * @brief PreCheck, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugins
 *
 * @author Franck Paul
 *
 * @copyright Franck Paul contact@open-time.net
 * @copyright GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
declare(strict_types=1);

if (isset($this) && is_object($this) && method_exists($this, 'registerModule') && isset($this->id) && is_string($this->id)) {
    $this->registerModule(
        'PreCheck',
        'Pre check current Dotclear installation before upgrading it',
        'Franck Paul',
        '1.5',
        [
            'date'        => '2026-10-28T09:26:45+0100',
            'requires'    => [['core', '2.38']],
            'permissions' => 'My',
            'type'        => 'plugin',
            'settings'    => [],

            'details'    => 'https://open-time.net/?q=PreCheck',
            'support'    => 'https://github.com/franck-paul/PreCheck',
            'repository' => 'https://raw.githubusercontent.com/franck-paul/PreCheck/main/dcstore.xml',
            'license'    => 'gpl2',
        ]
    );
}
