<?php

/**
 * ImageCopyright for Contao Open Source CMS
 *
 * @copyright   2016 – 2020 Tastaturberuf <tastaturberuf.de>
 * @author      Daniel Jahnsmüller <tastaturberuf.de>
 * @license     LGPL-3.0-or-later
 */

declare(strict_types=1);


namespace Tastaturberuf\ContaoImageCopyrightBundle\EventListener;


use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\CoreBundle\ServiceAnnotation\Hook;
use Terminal42\ServiceAnnotationBundle\ServiceAnnotationInterface;


class DcaFilesListener implements ServiceAnnotationInterface
{

    /**
     * @Hook("loadDataContainer", priority=1)
     */
    public function onLoadCallback(string $table): void
    {
        if ( 'tl_files' !== $table )
        {
            return;
        }

        $this->extendPalette($table);
        $this->addFields($table);
    }


    private function extendPalette(string $table): void
    {
        PaletteManipulator::create()
            ->addLegend('tastaturberuf_image_copyright_legend', 'meta')
                ->addField(['ic_copyright', 'ic_href', 'ic_hide'], 'tastaturberuf_image_copyright_legend')
                ->applyToPalette('default', $table)
        ;
    }


    private function addFields(string $table): void
    {
        $GLOBALS['TL_DCA'][$table]['fields'] = array_replace_recursive($GLOBALS['TL_DCA'][$table]['fields'],
        [
            'ic_copyright' =>
            [
                'exclude'   => true,
                'inputType' => 'text',
                'eval'      =>
                [
                    'maxlength' => 128,
                    'tl_class'  => 'w50'
                ],
                'sql' => "varchar(128) NOT NULL default ''"
            ],
            'ic_href' =>
            [
                'exclude'   => true,
                'inputType' => 'text',
                'eval'      =>
                [
                    'rgxp'      => 'url',
                    'maxlength' => 255,
                    'tl_class'  => 'w50'
                ],
                'sql' => "varchar(255) NOT NULL default ''"
            ],
            'ic_hide' =>
            [
                'exclude'   => true,
                'inputType' => 'checkbox',
                'eval'      =>
                [
                    'tl_class' => 'clear m12 w50'
                ],
                'sql'       => "char(1) NOT NULL default ''"
            ],

        ]);
    }

}
