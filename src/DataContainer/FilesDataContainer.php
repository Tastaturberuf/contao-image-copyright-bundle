<?php // with ♥ and Contao

/**
 * ImageCopyright for Contao Open Source CMS
 *
 * @copyright   2016 – 2025 Tastaturberuf <tastaturberuf.de>
 * @author      Daniel Jahnsmüller <tastaturberuf.de>
 * @license     LGPL-3.0-or-later
 */

declare(strict_types=1);

namespace Tastaturberuf\ContaoImageCopyrightBundle\DataContainer;

use Contao\ArrayUtil;
use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\DataContainer;
use Contao\FilesModel;
use Contao\Image;
use Contao\Input;
use function array_replace_recursive;
use function is_string;


final class FilesDataContainer
{

    public function __construct(private readonly array $validImageExtensions)
    {
    }

    #[AsHook('loadDataContainer')]
    public function addFields(string $table): void
    {
        if ('tl_files' !== $table) {
            return;
        }

        $GLOBALS['TL_DCA'][$table]['config']['onload_callback'][] = $this->showFieldsOnValidFilesCallback(...);

        ArrayUtil::arrayInsert($GLOBALS['TL_DCA'][$table]['list']['operations'], count($GLOBALS['TL_DCA'][$table]['list']['operations']) - 1, [
            'ic_copyright_button' => [
                'icon' => 'bundles/tastaturberufcontaoimagecopyright/icon/copyright.svg',
                'button_callback' => $this->generateCopyrightButton(...)
            ]
        ]);

        $GLOBALS['TL_DCA'][$table]['fields'] = array_replace_recursive($GLOBALS['TL_DCA'][$table]['fields'],
            [
                'ic_copyright' => [
                    'exclude' => true,
                    'inputType' => 'text',
                    'eval' => [
                        'maxlength' => 128,
                        'tl_class' => 'w50'
                    ],
                    'sql' => "varchar(128) NOT NULL default ''"
                ],
                'ic_href' => [
                    'exclude' => true,
                    'inputType' => 'text',
                    'eval' => [
                        'rgxp' => 'url',
                        'maxlength' => 255,
                        'tl_class' => 'w50'
                    ],
                    'sql' => "varchar(255) NOT NULL default ''"
                ],
                'ic_hide' => [
                    'exclude' => true,
                    'inputType' => 'checkbox',
                    'eval' => [
                        'tl_class' => 'clear m12 w50'
                    ],
                    'sql' => "char(1) NOT NULL default ''"
                ],

            ]);
    }

    private function showFieldsOnValidFilesCallback(?DataContainer $dc = null): void
    {
        // make sure to have data container
        if (null === $dc) {
            return;
        }

        // render fields on edit all
        if ('editAll' === Input::get('act')) {
            $this->addFieldsToPalette($dc->table);
        }

        // render when valid image type
        if (is_string($dc->id) && $this->isValidImage($dc->id)) {
            $this->addFieldsToPalette($dc->table);
        }
    }

    private function isValidImage(string $id): bool
    {
        $imageExtension = \strtolower(\pathinfo($id, PATHINFO_EXTENSION));

        return \in_array($imageExtension, $this->validImageExtensions, true);
    }


    private function addFieldsToPalette(string $table): void
    {
        PaletteManipulator::create()
            ->addLegend('tastaturberuf_image_copyright_legend', 'meta')
            ->addField(['ic_copyright', 'ic_href', 'ic_hide'], 'tastaturberuf_image_copyright_legend')
            ->applyToPalette('default', $table);
    }

    private function generateCopyrightButton(
        array $row,
        ?string $href,
        string $label,
        string $missingLabel,
        ?string $icon,
    ): string
    {
        // Skip folders and non image files
        if ($row['type'] !== 'file' || !$this->isValidImage($row['id'])) {
            return '';
        }

        if ((null === $model = FilesModel::findByPath($row['id'])) || $model->ic_copyright === '') {
            $icon = str_replace('.svg', '--disabled.svg', $icon);
            return Image::getHtml($icon, $missingLabel, 'title="' . $missingLabel . '"') . ' ';
        }

        return Image::getHtml($icon, $label, 'title="' . $label . '"') . ' ';
    }

}
