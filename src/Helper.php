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

namespace Dotclear\Plugin\PreCheck;

/**
 * Plugin definitions
 */
class Helper
{
    // Helper methods

    public static function getPlugins(bool $active_only = false): array
    {
        return [];
    }

    public static function getThemes(bool $active_only = false): array
    {
        return [];
    }
}
