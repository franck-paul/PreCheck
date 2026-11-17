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

class Version_2_45
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
        // Check usage of globals `_ctx`, `__widgets`, `_lang`, `_menu`, `p_url`, `_page_number`, `mod_files`, `mod_ts`, `__l10n`, `__l10n_files`
        // Deprecated since 2.23

        $search_terms = [
            '$_ctx'                      => '/global\\s+\\$_ctx\\W/m',
            '$GLOBALS[\'_ctx\']'         => '/\\$GLOBALS\\[\'_ctx\'\\]/m',
            '$__l10n'                    => '/global\\s+\\$__l10n\\W/m',
            '$GLOBALS[\'__l10n\']'       => '/\\$GLOBALS\\[\'__l10n\'\\]/m',
            '$__l10n_files'              => '/global\\s+\\$__l10n_files\\W/m',
            '$GLOBALS[\'__l10n_files\']' => '/\\$GLOBALS\\[\'__l10n_files\'\\]/m',
            '$_lang'                     => '/global\\s+\\$_lang\\W/m',
            '$GLOBALS[\'_lang\']'        => '/\\$GLOBALS\\[\'_lang\'\\]/m',
            '$_menu'                     => '/global\\s+\\$_menu\\W/m',
            '$GLOBALS[\'_menu\']'        => '/\\$GLOBALS\\[\'_menu\'\\]/m',
            '$mod_files'                 => '/global\\s+\\$mod_files\\W/m',
            '$GLOBALS[\'mod_files\']'    => '/\\$GLOBALS\\[\'mod_files\'\\]/m',
            '$mod_ts'                    => '/global\\s+\\$mod_ts\\W/m',
            '$GLOBALS[\'mod_ts\']'       => '/\\$GLOBALS\\[\'mod_ts\'\\]/m',
            '$_page_number'              => '/global\\s+\\$_page_number\\W/m',
            '$GLOBALS[\'_page_number\']' => '/\\$GLOBALS\\[\'_page_number\'\\]/m',
            '$p_url'                     => '/global\\s+\\$p_url\\W/m',
            '$GLOBALS[\'p_url\']'        => '/\\$GLOBALS\\[\'p_url\'\\]/m',
            '$__widgets'                 => '/global\\s+\\$__widgets\\W/m',
            '$GLOBALS[\'__widgets\']'    => '/\\$GLOBALS\\[\'__widgets\'\\]/m',
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
                    __('Global variables <code>$_ctx</code>, <code>__l10n</code>, <code>__l10n_files</code>, <code>_lang</code>, <code>_menu</code>, <code>mod_files</code>, <code>mod_ts</code>, <code>_page_number</code>, <code>p_url</code> and <code>__widgets</code> are deprecated since <strong>Dotclear 2.23</strong> (published on August 13, 2022) and are still present in the following modules.<br>You must review and update them.'),
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
