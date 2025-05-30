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
    'tastaturberuf_image_copyright_legend' => 'Copyright settings',

    'ic_copyright' => ['Copyright notice', 'Insert a copyright hint.'],
    'ic_href'      => ['Link', 'Link target for the copyright notice.'],
    'ic_hide'      => ['Hide in list', 'Don’t show this file in the copyright module.']
]);
