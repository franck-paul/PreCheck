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

use DirectoryIterator;
use Dotclear\App;
use Dotclear\Module\ModuleDefine;

/**
 * Plugin definitions
 */
class Helper
{
    // Helper methods

    /**
     * @param  bool   $enabled_only Get only enabled plugins
     *
     * @return ModuleDefine[]
     */
    public static function getPlugins(bool $enabled_only = false, bool $exclude_distributed = true): array
    {
        $list   = [];
        $search = $enabled_only ? ['state' => ModuleDefine::STATE_ENABLED] : [];
        foreach (App::plugins()->getDefines($search) as $define) {
            if (!$exclude_distributed || !$define->get('distributed')) {
                // Not distributed module
                if ($define->get('name') !== My::id()) {
                    // Not this one so add it
                    $list[] = $define;
                }
            }
        }

        return $list;
    }

    /**
     * @param  bool   $enabled_only Get only enabled themes
     *
     * @return ModuleDefine[]
     */
    public static function getThemes(bool $enabled_only = false, bool $exclude_distributed = true): array
    {
        $list   = [];
        $search = $enabled_only ? ['state' => ModuleDefine::STATE_ENABLED] : [];
        foreach (App::themes()->getDefines($search) as $define) {
            if (!$exclude_distributed || !$define->get('distributed')) {
                // Not distributed module
                $list[] = $define;
            }
        }

        return $list;
    }

    /**
     * Search list of terms in a list of module and return an array of found PHP source files containing these terms
     * The result (list of source files) will be provided as an array with these keys:
     * - module name (primary)
     * - term (secondary)
     *
     * @param  ModuleDefine[]           $modules        List of module to check
     * @param  array<string, string>    $search_terms   List of terms to search for in modules array<label, regular expression>
     *
     * @return array<string, array<string, string[]>>
     */
    public static function searchInPHPSourceFilesOfModules(array $modules, array $search_terms): array
    {
        $results = [];

        foreach ($modules as $module) {
            $root = $module->get('root');
            $name = $module->get('name');
            if (is_string($name) && is_string($root) && is_dir($root)) {
                // Scan root folder
                $results = self::searchInPHPSourceFilesOfModulesSubDir($results, $root, '', $name, $search_terms);

                // Scan root/src folder and its sub folders
                $results = self::searchInPHPSourceFilesOfModulesSubDir($results, $root, 'src', $name, $search_terms, true);
            }
        }

        return $results;
    }

    /**
     * Search list of terms in a list of module and return an array of found PHP source files (in one subdir) containing these terms
     * The result (list of source files) will be provided as an array with these keys:
     * - module name (primary)
     * - term (secondary)
     *
     * @param  array<string, array<string, string[]>>   $results        Current results
     * @param  string                                   $root           Root folder to search in
     * @param  string                                   $suffix         Main sub-folder
     * @param  string                                   $name           Module name
     * @param  array<string, string>                    $search_terms   List of terms to search for in modules array<label, regular expression>
     *
     * @return array<string, array<string, string[]>>
     */
    private static function searchInPHPSourceFilesOfModulesSubDir(
        array $results,
        string $root,
        string $suffix,
        string $name,
        array $search_terms,
        bool $recurse = false
    ): array {
        $folder = $root . ($suffix !== '' ? DIRECTORY_SEPARATOR . $suffix : '');
        if (is_dir($folder)) {
            foreach (new DirectoryIterator($folder) as $fileInfo) {
                if ($fileInfo->isDot()) {
                    continue;
                }

                if ($fileInfo->isDir()) {
                    if ($recurse) {
                        $results = self::searchInPHPSourceFilesOfModulesSubDir(
                            $results,
                            $root,
                            ($suffix !== '' ? $suffix . DIRECTORY_SEPARATOR : '') . $fileInfo->getFilename(),
                            $name,
                            $search_terms
                        );
                    }

                    continue;
                }

                if ($fileInfo->getExtension() !== 'php') {
                    continue;
                }

                $realpath = $fileInfo->getRealPath();
                if ($realpath !== false) {
                    $content = file_get_contents($realpath);
                    if ($content !== false) {
                        foreach ($search_terms as $key => $term) {
                            if (preg_match($term, $content)) {
                                $results[$name][$key][] = ($suffix !== '' ? $suffix . DIRECTORY_SEPARATOR : '') . $fileInfo->getFilename();
                            }
                        }
                    }
                }
            }
        }

        return $results;
    }
}
