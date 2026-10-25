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
use Dotclear\Helper\Html\Form\Single;
use Dotclear\Helper\Html\Form\Ul;
use Dotclear\Plugin\PreCheck\Helper;

class Version_2_40
{
    public static function check(): ?Component
    {
        $checks = [
            self::checkBackend(),
            self::checkWidget(),
        ];

        $checks = array_filter($checks, fn (?Component $v): bool => !is_null($v));

        return $checks !== []
            ? (new Set())
                ->separator((new Single('hr'))->render())
                ->items($checks)
            : null;
    }

    public static function checkBackend(): ?Component
    {
        // Check if there any reference to App::backend()->popup === 1 which should be replaced by App::backend()->getPopup()
        // and to App::backend()->plugin_id === 'admin.blog.theme' which should be replaced by MediaItem::isPopup()

        $search_terms = [
            'App::backend()->popup === 1'                        => '/App::backend\\(\\)->popup\\s*===?\\s*1/m',
            'App::backend()->plugin_id === \'admin.blog.theme\'' => '/App::backend\\(\\)->plugin_id\\s*===?\\s*\'admin\\.blog\\.theme\'/m',
        ];

        $plugins = Helper::getPlugins();
        $themes  = Helper::getThemes(exclude_distributed: false);   // We may have some old distributed themes copied in current themes folder

        $results_plugins = Helper::searchInPHPSourceFilesOfModules($plugins, $search_terms);
        $results_themes  = Helper::searchInPHPSourceFilesOfModules($themes, $search_terms);

        if ($results_plugins !== [] || $results_themes !== []) {
            $lines_modules = [];

            if ($results_plugins !== []) {
                $group_module  = (new Ul());
                $lines_plugins = [];

                foreach ($results_plugins as $plugin => $terms) {
                    $group_plugin = (new Ul());
                    $lines_terms  = [];

                    foreach ($terms as $term => $files) {
                        $group_term  = (new Ul());
                        $lines_files = [];

                        foreach ($files as $file) {
                            $lines_files[] = (new Li())
                                ->text($file);
                        }

                        $group_term
                            ->items($lines_files);
                        $lines_terms[] = (new Li())
                            ->text(sprintf(
                                __('<code>%s</code> still present in this file:', '<code>%s</code> still present in these files:', count($lines_files)),
                                $term
                            ))
                            ->items([
                                $group_term,
                            ]);
                    }

                    $group_plugin
                        ->items($lines_terms);
                    $lines_plugins[] = (new Li())
                        ->text(sprintf(__('<strong>%s</strong> plugin:'), $plugin))
                        ->items([
                            $group_plugin,
                        ]);
                }

                $group_module
                    ->items($lines_plugins);
                $lines_modules[] = (new Li())
                    ->text(__('Plugins:'))
                    ->items([
                        $group_module,
                    ]);
            }

            if ($results_themes !== []) {
                $group_module = (new Ul());
                $lines_themes = [];

                foreach ($results_themes as $theme => $terms) {
                    $group_theme = (new Ul());
                    $lines_terms = [];

                    foreach ($terms as $term => $files) {
                        $group_term  = (new Ul());
                        $lines_files = [];

                        foreach ($files as $file) {
                            $lines_files[] = (new Li())
                                ->text($file);
                        }

                        $group_term
                            ->items($lines_files);
                        $lines_terms[] = (new Li())
                            ->text(sprintf(
                                __('<code>%s</code> still present in this file:', '<code>%s</code> still present in these files:', count($lines_files)),
                                $term
                            ))
                            ->items([
                                $group_term,
                            ]);
                    }

                    $group_theme
                        ->items($lines_terms);
                    $lines_themes[] = (new Li())
                        ->text(sprintf(__('<strong>%s</strong> theme:'), $theme))
                        ->items([
                            $group_theme,
                        ]);
                }

                $group_module
                    ->items($lines_themes);
                $lines_modules[] = (new Li())
                    ->text(__('Themes:'))
                    ->items([
                        $group_module,
                    ]);
            }

            return (new Set())
                ->items([
                    (new Note())
                        ->class('warn')
                        ->text(__('The <code>App::backend()->popup</code> property is no longer accessible as of <strong>Dotclear 2.40</strong>, but is still present in the following modules.<br>You should review and update them (by replacing <code>App::backend()->popup === 1</code> with <code>App::backend()->isPopup()</code>).<br><br>Also the <code>App::backend()->plugin_id</code> property is no longer accessible as of <strong>Dotclear 2.40</strong>, but is still present in the following modules.<br>You should review and update them (by replacing <code>App::backend()->plugin_id === \'admin.blog.theme\'</code> with <code>MediaItem::isPopup()</code>).')),
                    (new Note())
                        ->class('info')
                        ->text(__('Note that these modifications do not cause any issues with Dotclear 2.40 <strong>if and only if</strong> the <var>DC_MODERN</var> constant is set to <samp>false</samp> (or does not appear at all) in the configuration file (<var>inc/config.php</var>).')),
                    (new Ul())
                        ->items($lines_modules),
                    (new Note())
                        ->class('info')
                        ->text(__('Some distributed themes may appear in this list if you copied them to a directory other than Dotclear\'s <var>themes</var> directory.<br>In that case, we recommend deleting them from your custom directory and using the button labeled “ Add the distributed themes here ” on the blog\'s appearance page.')),
                ]);
        }

        return null;
    }

    public static function checkWidget(): ?Component
    {
        // Check if there any usage of WidgetsElement::checkHomeOnly() with 2nd and/or 3rd argument which are no longer taken into account

        $search_terms = [
            'WidgetsElement::checkHomeOnly(<2 arguments>)' => '/checkHomeOnly\(\s*(?:[^,)]*+,?\s*){1}\K[^,)]++/m',
            'WidgetsElement::checkHomeOnly(<3 arguments>)' => '/checkHomeOnly\(\s*(?:[^,)]*+,?\s*){2}\K[^,)]++/m',
        ];

        $plugins = Helper::getPlugins();
        $themes  = Helper::getThemes(exclude_distributed: false);   // We may have some old distributed themes copied in current themes folder

        $results_plugins = Helper::searchInPHPSourceFilesOfModules($plugins, $search_terms);
        $results_themes  = Helper::searchInPHPSourceFilesOfModules($themes, $search_terms);

        if ($results_plugins !== [] || $results_themes !== []) {
            $lines_modules = [];

            if ($results_plugins !== []) {
                $group_module  = (new Ul());
                $lines_plugins = [];

                foreach ($results_plugins as $plugin => $terms) {
                    $group_plugin = (new Ul());
                    $lines_terms  = [];

                    foreach ($terms as $term => $files) {
                        $group_term  = (new Ul());
                        $lines_files = [];

                        foreach ($files as $file) {
                            $lines_files[] = (new Li())
                                ->text($file);
                        }

                        $group_term
                            ->items($lines_files);
                        $lines_terms[] = (new Li())
                            ->text(sprintf(
                                __('<code>%s</code> still present in this file:', '<code>%s</code> still present in these files:', count($lines_files)),
                                $term
                            ))
                            ->items([
                                $group_term,
                            ]);
                    }

                    $group_plugin
                        ->items($lines_terms);
                    $lines_plugins[] = (new Li())
                        ->text(sprintf(__('<strong>%s</strong> plugin:'), $plugin))
                        ->items([
                            $group_plugin,
                        ]);
                }

                $group_module
                    ->items($lines_plugins);
                $lines_modules[] = (new Li())
                    ->text(__('Plugins:'))
                    ->items([
                        $group_module,
                    ]);
            }

            if ($results_themes !== []) {
                $group_module = (new Ul());
                $lines_themes = [];

                foreach ($results_themes as $theme => $terms) {
                    $group_theme = (new Ul());
                    $lines_terms = [];

                    foreach ($terms as $term => $files) {
                        $group_term  = (new Ul());
                        $lines_files = [];

                        foreach ($files as $file) {
                            $lines_files[] = (new Li())
                                ->text($file);
                        }

                        $group_term
                            ->items($lines_files);
                        $lines_terms[] = (new Li())
                            ->text(sprintf(
                                __('<code>%s</code> still present in this file:', '<code>%s</code> still present in these files:', count($lines_files)),
                                $term
                            ))
                            ->items([
                                $group_term,
                            ]);
                    }

                    $group_theme
                        ->items($lines_terms);
                    $lines_themes[] = (new Li())
                        ->text(sprintf(__('<strong>%s</strong> theme:'), $theme))
                        ->items([
                            $group_theme,
                        ]);
                }

                $group_module
                    ->items($lines_themes);
                $lines_modules[] = (new Li())
                    ->text(__('Themes:'))
                    ->items([
                        $group_module,
                    ]);
            }

            return (new Set())
                ->items([
                    (new Note())
                        ->class('warn')
                        ->text(__('The 2nd and 3rd argument of <code>WidgetsElement::checkHomeOnly()</code> are no longer taken into account as of <strong>Dotclear 2.40</strong>.')),
                    (new Ul())
                        ->items($lines_modules),
                ]);
        }

        return null;
    }
}
