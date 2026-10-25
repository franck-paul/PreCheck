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
L10n::$locales['The <code>App::backend()->popup</code> property is no longer accessible as of <strong>Dotclear 2.40</strong>, but is still present in the following modules.<br>You should review and update them (by replacing <code>App::backend()->popup === 1</code> with <code>App::backend()->isPopup()</code>).<br><br>Also the <code>App::backend()->plugin_id</code> property is no longer accessible as of <strong>Dotclear 2.40</strong>, but is still present in the following modules.<br>You should review and update them (by replacing <code>App::backend()->plugin_id === \'admin.blog.theme\'</code> with <code>MediaItem::isPopup()</code>).'] = '';
L10n::$locales['Note that these modifications do not cause any issues with Dotclear 2.40 <strong>if and only if</strong> the <var>DC_MODERN</var> constant is set to <samp>false</samp> (or does not appear at all) in the configuration file (<var>inc/config.php</var>).'] = '';
L10n::$locales['The 2nd and 3rd argument of <code>WidgetsElement::checkHomeOnly()</code> are no longer taken into account as of <strong>Dotclear 2.40</strong>.'] = '';
L10n::$locales['<code>dcCore</code> and <code>dcUtils</code> are deprecated since <strong>Dotclear 2.28</strong> (published on November 15, 2023) and are still present in the following modules.<br>You should review and update them.'] = '';
