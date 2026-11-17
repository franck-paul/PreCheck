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

class Version_2_43
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
        // Check usage of backend legacy.js methods

        $search_terms = [
            'jQuery.expandContent'     => '/(?<!dotclear\\.)expandContent/m',
            'jQuery.toggleWithLegend'  => '/(?<!dotclear\\.)toggleWithLegend/m',
            'jQuery.toggleWithDetails' => '/(?<!dotclear\\.)toggleWithDetails/m',
        ];

        $plugins = Helper::getPlugins();
        $themes  = Helper::getThemes(exclude_distributed: false);   // We may have some old distributed themes copied in current themes folder

        $report = Helper::makeReport(
            Helper::searchInJSSourceFilesOfModules($plugins, $search_terms),
            Helper::searchInJSSourceFilesOfModules($themes, $search_terms),
            // Before
            [
                [
                    false,   // info
                    __('The JQuery methods <code>expandContent</code>, <code>toggleWithLegend</code> and <code>toggleWithDetails</code> class are deprecated and are still present in the following modules.<br>You should review and update them by using the Javascript <code>dotclear</code> object equivalent methods (see admin/js/common.js).'),
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
