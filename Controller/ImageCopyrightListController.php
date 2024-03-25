<?php

/**
 * ImageCopyright for Contao Open Source CMS
 *
 * @copyright   2016 – 2022 Tastaturberuf <tastaturberuf.de>
 * @author      Daniel Jahnsmüller <tastaturberuf.de>
 * @license     LGPL-3.0-or-later
 */

declare(strict_types=1);


namespace Tastaturberuf\ContaoImageCopyrightBundle\Controller;


use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\Image\ImageFactoryInterface;
use Contao\CoreBundle\ServiceAnnotation\FrontendModule;
use Contao\FilesModel;
use Contao\Model\Collection;
use Contao\ModuleModel;
use Contao\StringUtil;
use Contao\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * @FrontendModule(category="miscellaneous")
 */
class ImageCopyrightListController extends AbstractFrontendModuleController
{

    public const TYPE = 'image_copyright_list';


    private ImageFactoryInterface $imageFactory;

    private string $rootDir;

    private array $validImageExtensions;


    public function __construct(ImageFactoryInterface $imageFactory, string $rootDir, array $validImageExtensions)
    {
        $this->imageFactory         = $imageFactory;
        $this->rootDir              = $rootDir;
        $this->validImageExtensions = $validImageExtensions;
    }


    protected function getResponse(Template $template, ModuleModel $model, Request $request): Response
    {
        if ( null !== $files = $this->getImages($model) )
        {
            $imgSize = StringUtil::deserialize($model->imgSize);

            foreach ($files as $file)
            {
                $image = $this->imageFactory->create($this->rootDir.DIRECTORY_SEPARATOR.$file->path, $imgSize);

                $file->src = $image->getUrl($this->rootDir);
                $file->dimensions = $image->getDimensions();
            }

            $template->files = $files;
        }

        return $template->getResponse();
    }


    private function getImages(ModuleModel $model): ?Collection
    {
        // mask all strings with single quotes to e.g. 'jpeg', 'webp', ...
        $extensions = array_map(function (string $extension): string {
            return "'$extension'";
        }, $this->validImageExtensions);

        $options =
        [
            'column' =>
            [
                // text is set
                "ic_copyright != ''",
                // type is file
                "type = 'file'",
                // is not hidden
                "ic_hide = ''",
                // is valid image type
                sprintf("extension IN (%s)", \implode(',', $extensions))
            ]
        ];

        // if a folder is set find only files in path und subfolders
        if ( $model->ic_folder && $folderModel = FilesModel::findByPk($model->ic_folder) )
        {
            $options['column'][] = sprintf("path LIKE '%s/%%'", $folderModel->path);
        }

        // order by columns
        if ( $model->ic_order )
        {
            $order = StringUtil::deserialize($model->ic_order);

            $options['order'] = \implode(',', $order);
        }

        return FilesModel::findAll($options);
    }

}
