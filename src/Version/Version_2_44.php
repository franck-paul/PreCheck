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

class Version_2_44
{
    public static function check(bool &$warning): ?Component
    {
        $checks = [
            self::checkOldGlobalVars($warning),
        ];

        $checks = array_filter($checks, fn (?Component $v): bool => !is_null($v));

        return $checks !== []
            ? (new Set())
                ->separator((new Single('hr'))->render())
                ->items($checks)
            : null;
    }

    public static function checkOldGlobalVars(bool &$warning): ?Component
    {
        // Check usage of Clearbricks
        // Deprecated since 2.26

        $search_terms = [
            'Clearbricks' => '/\\bClearbrick/m',
        ];

        $plugins = Helper::getPlugins();
        $themes  = Helper::getThemes(exclude_distributed: false);   // We may have some old distributed themes copied in current themes folder

        $report = Helper::makeReport(
            Helper::searchInPHPSourceFilesOfModules($plugins, $search_terms),
            Helper::searchInPHPSourceFilesOfModules($themes, $search_terms),
            // Before
            [
                [
                    true,   // warning
                    __('<code>Clearbricks</code> class is deprecated since <strong>Dotclear 2.26</strong> (published on May 13, 2023) and are still present in the following modules.<br>You must review and update them.'),
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

        if ($report instanceof Component) {
            $warning = true;
        }

        return $report;
    }
}
