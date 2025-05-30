<?php // with ♥ and Contao

/**
 * ImageCopyright for Contao Open Source CMS
 *
 * @copyright   2016 – 2021 Tastaturberuf <tastaturberuf.de>
 * @author      Daniel Jahnsmüller <tastaturberuf.de>
 * @license     LGPL-3.0-or-later
 */

declare(strict_types=1);


namespace Tastaturberuf\ContaoImageCopyrightBundle\Contao\Translation;


$GLOBALS['TL_LANG']['tl_files'] = array_replace_recursive($GLOBALS['TL_LANG']['tl_files'],
[
    'tastaturberuf_image_copyright_legend' => 'Configuración de los derechos de autor',

    'ic_copyright' => ['Aviso de derechos de autor', 'Proporcionar un aviso de derechos de autor.'],
    'ic_href'      => ['Enlace', 'Objetivo del enlace para el aviso de derechos de autor.'],
    'ic_hide'      => ['Ocultar en la lista', 'No muestre este archivo en el módulo de derechos de autor.']
]);
