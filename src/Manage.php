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

use Dotclear\App;
use Dotclear\Helper\File\Files;
use Dotclear\Helper\Html\Form\Component;
use Dotclear\Helper\Html\Form\Details;
use Dotclear\Helper\Html\Form\Div;
use Dotclear\Helper\Html\Form\Note;
use Dotclear\Helper\Html\Form\Summary;
use Dotclear\Helper\Html\Html;
use Dotclear\Helper\Process\TraitProcess;

class Manage
{
    use TraitProcess;

    private static Div $div;

    private static bool $status;

    /**
     * Initializes the page.
     */
    public static function init(): bool
    {
        return self::status(My::checkContext(My::MANAGE));
    }

    /**
     * Processes the request(s).
     */
    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        self::$div    = (new Div('precheck'));
        self::$status = true;
        $results      = [];

        // Run pre checks
        // --------------

        // Get current Dotclear version and found all precheck needed to be done
        $current = App::version()->getVersion();

        // Get list of existing prechecks
        $prechecks = self::getPreCheckVersions();

        // Execute each precheck and add its result to the main result (if their version is higher than current)
        foreach ($prechecks as $precheck) {
            if (version_compare($current, $precheck['version']) === -1) {
                require_once $precheck['file'];
                $result = $precheck['class']::check();
                if ($result instanceof Component) {
                    // Some actions should be taken before upgrading
                    $results[] = (new Details('precheck-' . $precheck['version']))
                        ->open(true)
                        ->summary((new Summary(sprintf(__('Some verifications requested by Dotclear <strong>%1$s</strong> should be done before upgrading:'), $precheck['version']))))
                        ->items([$result]);
                    // Change status to show details in render
                    self::$status = false;
                }
            }
        }

        // Add all results to the main Div
        self::$div->items($results);

        return true;
    }

    /**
     * Renders the page.
     */
    public static function render(): void
    {
        if (!self::status()) {
            return;
        }

        App::backend()->page()->openModule(
            My::name(),
            My::cssLoad('backend.css')
        );

        echo App::backend()->page()->breadcrumb(
            [
                Html::escapeHTML(App::blog()->name()) => '',
                __('Pre check')                       => '',
            ]
        );
        echo App::backend()->notices()->getNotices();

        if (self::$status) {
            echo (new Note())
                ->text(__('Everything looks fine; you should be able to perform the update without any problems.'))
            ->render();
        } else {
            // Display result of pre checks if any
            echo self::$div->render();
        }

        App::backend()->page()->closeModule();
    }

    /**
     * @return  list<array{version: string, file: non-falsy-string, class: non-falsy-string}>
     */
    private static function getPreCheckVersions(): array
    {
        // Prepare upgrades scan
        $path      = 'Version';
        $dir       = implode(DIRECTORY_SEPARATOR, [__DIR__, $path, '']);
        $namespace = implode('\\', [__NAMESPACE__, $path, '']);

        // Scan GrowUp folder to find available upgrades
        $prechecks = [];
        foreach (Files::scandir($dir) as $file) {
            // Need only pre check files
            if (!str_starts_with($file, $path . '_')) {
                continue;
            }

            if (!str_contains($file, '.php')) {
                continue;
            }

            // Remove unwanted file name parts and split it by _
            $parts = explode('_', substr($file, strlen($path) + 1, -4));    // Remove .php extension

            $version = '';
            foreach ($parts as $part) {
                if ($version === '') {
                    $version = $part;
                } else {
                    $version .= (is_numeric($part) ? '.' : '-') . $part;
                }
            }

            // set growup version info
            $prechecks[] = [
                'version' => $version,
                'file'    => $dir . $file,
                'class'   => $namespace . substr($file, 0, -4), // Remove .php extension
            ];
        }

        // Sort pre check versions
        usort($prechecks, fn (array $a, array $b): int => version_compare($a['version'], $b['version'], '>') ? 1 : -1);

        return $prechecks;
    }
}
