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

class Version_2_42
{
    public static function check(): ?Component
    {
        $checks = [
            self::checkOldClasses(),
        ];

        $checks = array_filter($checks, fn (?Component $v): bool => !is_null($v));

        return $checks !== []
            ? (new Set())
                ->separator((new Single('hr'))->render())
                ->items($checks)
            : null;
    }

    public static function checkOldClasses(): ?Component
    {
        // Check usage of form in modules (plugins/themes)

        $search_terms = [
            'form::combo()'    => '/\\bform::\\bcombo\(/m',
            'form::radio()'    => '/\\bform::\\bradio\(/m',
            'form::checkbox()' => '/\\bform::\\bcheckbox\(/m',
            'form::field()'    => '/\\bform::\\bfield\(/m',
            'form::password()' => '/\\bform::\\bpassword\(/m',
            'form::color()'    => '/\\bform::\\bcolor\(/m',
            'form::email()'    => '/\\bform::\\bemail\(/m',
            'form::url()'      => '/\\bform::\\burl\(/m',
            'form::datetime()' => '/\\bform::\\bdatetime\(/m',
            'form::date()'     => '/\\bform::\\bdate\(/m',
            'form::time()'     => '/\\bform::\\btime\(/m',
            'form::file()'     => '/\\bform::\\bfile\(/m',
            'form::number()'   => '/\\bform::\\number\(/m',
            'form::textArea()' => '/\\bform::\\btextArea\(/m',
            'form::hidden()'   => '/\\bform::\\bhidden\(/m',
            'formSelectOption' => '/\\bformSelectOption/m',
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
                    __('<code>form</code> and <code>formSelectOption</code> classes are deprecated since <strong>Dotclear 2.26</strong> (published on May 13, 2023) and are still present in the following modules.<br>You must review and update them by using their replacements (see <code>Dotclear/Helper/Html/Form/...</code>).'),
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
}
