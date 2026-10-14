<?php
declare(strict_types=1);

/**
 * @package Dotclear
 *
 * @copyright Olivier Meunier & Association Dotclear
 */
#
# DOT NOT MODIFY THIS FILE !
#

use Dotclear\Helper\L10n;

L10n::$locales['Pre check current Dotclear installation before upgrading it'] = '';
L10n::$locales['Some verifications requested by Dotclear <strong>%1$s</strong> should be done before upgrading:'] = '';
L10n::$locales['Pre check'] = '';
L10n::$locales['Everything looks fine; you should be able to perform the update without any problems.'] = '';
L10n::$locales['<code>%s</code> still present in this file:'] = [
	'',
	'',
];
L10n::$locales['<strong>%s</strong> plugin:'] = '';
L10n::$locales['Plugins:'] = '';
L10n::$locales['<strong>%s</strong> theme:'] = '';
L10n::$locales['Themes:'] = '';
L10n::$locales['The <code>App::url()->type</code> property is no longer accessible as of <strong>Dotclear 2.39</strong> (released on August 13, 2026), but is still present in the following modules.<br>You should review and update them (by replacing <code>App::url()->type</code> with <code>App::url()->getType()</code>).'] = '';
L10n::$locales['Some distributed themes may appear in this list if you copied them to a directory other than Dotclear\'s <var>themes</var> directory.<br>In that case, we recommend deleting them from your custom directory and using the button labeled “ Add the distributed themes here ” on the blog\'s appearance page.'] = '';
L10n::$locales['<code>dcCore</code> and <code>dcUtils</code> are deprecated since <strong>Dotclear 2.28</strong> (published on November 15, 2023) and are still present in the following modules.<br>You should review and update them.'] = '';
