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

L10n::$locales['Pre check current Dotclear installation before upgrading it'] = 'Vérifiez au préalable votre installation actuelle de Dotclear avant de la mettre à jour';
L10n::$locales['Some verifications requested by Dotclear <strong>%1$s</strong> should be done before upgrading:'] = 'Quelques vérifications requises par Dotclear <strong>%1$s</strong> doivent être effectuées avant la mise à jour :';
L10n::$locales['Pre check'] = 'Vérification préalable';
L10n::$locales['Everything looks fine; you should be able to perform the update without any problems.'] = 'Tout semble en ordre ; vous devriez pouvoir effectuer la mise à jour sans aucun problème.';
L10n::$locales['<code>%s</code> still present in this file:'] = [
	'<code>%s</code> toujours présents dans ce fichier :',
	'<code>%s</code> toujours présents dans ces fichiers :',
	'<code>%s</code> toujours présents dans ces fichiers :',
];
L10n::$locales['<strong>%s</strong> plugin:'] = 'Plugin <strong>%s</strong> :';
L10n::$locales['Plugins:'] = 'Plugins :';
L10n::$locales['<strong>%s</strong> theme:'] = 'Thème <strong>%s</strong> :';
L10n::$locales['Themes:'] = 'Thèmes :';
L10n::$locales['The <code>App::url()->type</code> property is no longer accessible as of <strong>Dotclear 2.39</strong> (released on August 13, 2026), but is still present in the following modules.<br>You should review and update them (by replacing <code>App::url()->type</code> with <code>App::url()->getType()</code>).'] = 'La propriété <code>App::url()->type</code> n\'est plus accessible depuis <strong>la version 2.39 de Dotclear</strong> (publiée le 13 août 2026), mais elle est toujours présente dans les modules suivants :<br>Vous devriez les passer en revue et les mettre à jour (en remplaçant <code>App::url()->type</code> par <code>App::url()->getType()</code>).';
L10n::$locales['Some distributed themes may appear in this list if you copied them to a directory other than Dotclear\'s <var>themes</var> directory.<br>In that case, we recommend deleting them from your custom directory and using the button labeled “ Add the distributed themes here ” on the blog\'s appearance page.'] = 'Certains thèmes distribués peuvent apparaître dans cette liste si vous les avez copiés dans un répertoire autre que le répertoire <var>des thèmes</var> de Dotclear.<br>Dans ce cas, nous vous recommandons de les supprimer de votre répertoire personnalisé et d\'utiliser le bouton intitulé « Ajouter les thèmes distribués ici » sur la page d\'apparence du blog.';
L10n::$locales['<code>dcCore</code> and <code>dcUtils</code> are deprecated since <strong>Dotclear 2.28</strong> (published on November 15, 2023) and are still present in the following modules.<br>You should review and update them.'] = '<code>dcCore</code> et <code>dcUtils</code> sont obsolètes depuis <strong>Dotclear 2.28</strong> (publiée le 15 novembre 2023) et sont toujours présents dans les modules suivants :<br>. Nous vous recommandons de les passer en revue et de les mettre à jour.';
