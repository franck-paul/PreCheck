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
use Dotclear\Helper\Html\Form\Li;
use Dotclear\Helper\Html\Form\Note;
use Dotclear\Helper\Html\Form\Set;
use Dotclear\Helper\Html\Form\Ul;

class Version_2_39
{
    public static function check(): ?Component
    {
        // Check if there any reference to App::url()->type which should be replaced by App::url()->getType()
        // The result will be provided as a unumbered list, grouped by module type

        return (new Set())
            ->items([
                (new Note())
                    ->text(__('<code>App::url()->type</code> is no longer usable, replace it by <code>App::url()->getType()</code> in following modules.')),
                (new Ul())
                    ->items([
                        (new Li())
                            ->text('Plugins')
                            ->items([
                                (new Ul())
                                    ->items([
                                        (new Li())
                                            ->text('module 1 : src/Frontend.php'),
                                        (new Li())
                                            ->text('module 2 : src/FrontendBehaviors.php'),
                                    ]),
                            ]),
                        (new Li())
                            ->text('Themes')
                            ->items([
                                (new Ul())
                                    ->items([
                                        (new Li())
                                            ->text('theme 3 : src/Frontend.php'),
                                    ]),
                            ]),
                    ]),
            ]);

        return null;
    }
}
