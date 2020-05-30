<?php

/**
 * ImageCopyright for Contao Open Source CMS
 *
 * @copyright   2016 – 2020 Tastaturberuf <tastaturberuf.de>
 * @author      Daniel Jahnsmüller <tastaturberuf.de>
 * @license     LGPL-3.0-or-later
 */

declare(strict_types=1);


namespace Tastaturberuf\ContaoImageCopyrightBundle\Contao\Translation;


$GLOBALS['TL_LANG']['tl_files'] = array_replace_recursive($GLOBALS['TL_LANG']['tl_files'],
[
    'tastaturberuf_image_copyright_legend' => "Paramètres des droits d'auteur",

    'ic_copyright' => ["Avis de droit d'auteur", "Fournir un avis de droit d'auteur."],
    'ic_href'      => ['Lien', "Lien cible pour l'avis de droit d'auteur"],
    'ic_hide'      => ["Cacher dans la liste", "N'affichez pas ce fichier dans le module Droits d'auteur."]
]);
