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

namespace Dotclear\Plugin\PreCheck\Version;

use Dotclear\Helper\Html\Form\Component;
use Dotclear\Helper\Html\Form\Set;
use Dotclear\Helper\Html\Form\Single;
use Dotclear\Plugin\PreCheck\Helper;

class Version_2_39
{
    public static function check(): ?Component
    {
        $checks = [
            self::checkUrlType(),
            self::checkOldForm(),
            self::checkDeprecatedBehaviors(),
        ];

        $checks = array_filter($checks, fn (?Component $v): bool => !is_null($v));

        return $checks !== []
            ? (new Set())
                ->separator((new Single('hr'))->render())
                ->items($checks)
            : null;
    }

    public static function checkUrlType(): ?Component
    {
        // Check if there any reference to App::url()->type which should be replaced by App::url()->getType()

        $search_terms = [
            'App::url()->type' => '/App::url\(\)->type/m',
        ];

        $plugins = Helper::getPlugins();
        $themes  = Helper::getThemes(exclude_distributed: false);   // We may have some old distributed themes copied in current themes folder

        return Helper::makeReport(
            Helper::searchInPHPSourceFilesOfModules($plugins, $search_terms),
            Helper::searchInPHPSourceFilesOfModules($themes, $search_terms),
            // Before
            [
                [
                    true,   // warning
                    __('The <code>App::url()->type</code> property is no longer accessible as of <strong>Dotclear 2.39</strong> (released on August 13, 2026), but is still present in the following modules.<br>You should review and update them (by replacing <code>App::url()->type</code> with <code>App::url()->getType()</code>).'),
                ],
            ],
            // After
            [
                [
                    false,
                    __('Some distributed themes may appear in this list if you copied them to a directory other than Dotclear\'s <var>themes</var> directory.<br>In that case, we recommend deleting them from your custom directory and using the button labeled “ Add the distributed themes here ” on the blog\'s appearance page.'),
                ],
            ],
        );
    }

    public static function checkOldForm(): ?Component
    {
        // Check if there any reference to form::... which should be replaced by Helper/Html/Form/Component

        $search_terms = [
            'form::???()' => '/App::form\(/m',
        ];

        $plugins = Helper::getPlugins();
        $themes  = Helper::getThemes(exclude_distributed: false);   // We may have some old distributed themes copied in current themes folder

        return Helper::makeReport(
            Helper::searchInPHPSourceFilesOfModules($plugins, $search_terms),
            Helper::searchInPHPSourceFilesOfModules($themes, $search_terms),
            // Before
            [
                [
                    true,   // warning
                    __('The <code>form::???() methods are (strongly) deprecated as of <strong>Dotclear 2.26</strong> (released on May 13, 2023), but are still used in the following modules.<br>You should review and update them (by using <code>Helper/Html/Form/...</code>).'),
                ],
            ],
            // After
            [
                [
                    false,
                    __('Some distributed themes may appear in this list if you copied them to a directory other than Dotclear\'s <var>themes</var> directory.<br>In that case, we recommend deleting them from your custom directory and using the button labeled “ Add the distributed themes here ” on the blog\'s appearance page.'),
                ],
            ],
        );
    }

    public static function checkDeprecatedBehaviors(): ?Component
    {
        // Check if there any usage of coreContentFilter behavior

        $search_terms = [
            'adminPageNotification' => '/\'adminPageNotification\'/m',
            'adminFiltersListsV2'   => '/\'adminFiltersListsV2\'/m',
        ];

        $plugins = Helper::getPlugins();
        $themes  = Helper::getThemes(exclude_distributed: false);   // We may have some old distributed themes copied in current themes folder

        return Helper::makeReport(
            Helper::searchInPHPSourceFilesOfModules($plugins, $search_terms),
            Helper::searchInPHPSourceFilesOfModules($themes, $search_terms),
            // Before
            [
                [
                    false,   // info
                    __('The usage of these behaviors should be removed or replaced as of <strong>Dotclear 2.40</strong> (see CHANGELOG or code).'),
                ],
            ],
        );
    }
}
