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
use Dotclear\Plugin\PreCheck\Helper;

class Version_2_41
{
    public static function check(): ?Component
    {
        // Check usage of dcCore and dcUtils in modules (plugins/themes)

        $search_terms = [
            'dcCore'  => '/\\bdcCore/m',
            'dcUtils' => '/\\bdcUtils/m',
        ];

        $plugins = Helper::getPlugins();
        $themes  = Helper::getThemes(exclude_distributed: false);   // We may have some old distributed themes copied in current themes folder

        $results_plugins = Helper::searchInSourceFilesOfModules($plugins, $search_terms);
        $results_themes  = Helper::searchInSourceFilesOfModules($themes, $search_terms);

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
                                __('<code>%s</code> still used in this file:', '<code>%s</code> still used in these files:', count($lines_files)),
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
                                __('<code>%s</code> still used in this file:', '<code>%s</code> still used in these files:', count($lines_files)),
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
                        ->text(__('<code>dcCore</code> and <code>dcUtils</code> are deprecated since <strong>Dotclear 2.28</strong> (published on November 15, 2023) and are still used.<br>You should review or/and update the following modules.')),
                    (new Ul())
                        ->items($lines_modules),
                    (new Note())
                        ->class('info')
                        ->text(__('Some distributed themes may appear in this list if you copied them to a directory other than Dotclear\'s <var>themes</var> directory.<br>In that case, we recommend deleting them from your custom directory and using the button labeled “ Add the distributed themes here ” on the blog\'s appearance page.')),
                ]);
        }

        return null;
    }
}
