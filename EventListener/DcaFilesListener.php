<?php // with ♥ and Contao

/**
 * ImageCopyright for Contao Open Source CMS
 *
 * @copyright   2016 – 2021 Tastaturberuf <tastaturberuf.de>
 * @author      Daniel Jahnsmüller <tastaturberuf.de>
 * @license     LGPL-3.0-or-later
 */

declare(strict_types=1);


namespace Tastaturberuf\ContaoImageCopyrightBundle\EventListener;


use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\CoreBundle\ServiceAnnotation\Callback;
use Contao\CoreBundle\ServiceAnnotation\Hook;
use Contao\DataContainer;
use Contao\Input;


class DcaFilesListener
{

    /**
     * @var array
     */
    private $validImageExtensions;


    public function __construct(array $validImageExtensions)
    {
        $this->validImageExtensions = $validImageExtensions;
    }


    /**
     * @Hook("loadDataContainer")
     */
    public function addFields(string $table): void
    {
        if ( 'tl_files' !== $table )
        {
            return;
        }

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


    /**
     * @Callback(table="tl_files", target="config.onload")
     */
    public function onLoadCallback(DataContainer $dc = null): void
    {
        // make sure to have data container
        if ( null === $dc )
        {
            return;
        }

        // render fields on edit all
        if ( 'editAll' === Input::get('act') )
        {
            $this->addFieldsToPalette($dc->table);
        }

        // render when valid image type
        if ( null !== $dc->id )
        {
            $fileExtension = \strtolower(pathinfo((string) $dc->id)['extension']);

            if ( in_array($fileExtension, $this->validImageExtensions) )
            {
                $this->addFieldsToPalette($dc->table);
            }
        }
    }


    private function addFieldsToPalette(string $table): void
    {
        PaletteManipulator::create()
            ->addLegend('tastaturberuf_image_copyright_legend', 'meta')
            ->addField(['ic_copyright', 'ic_href', 'ic_hide'], 'tastaturberuf_image_copyright_legend')
            ->applyToPalette('default', $table)
        ;
    }

}
