<?php

/**
 * ImageCopyright for Contao Open Source CMS
 *
 * @copyright   2016 – 2020 Tastaturberuf <tastaturberuf.de>
 * @author      Daniel Jahnsmüller <tastaturberuf.de>
 * @license     LGPL-3.0-or-later
 */

declare(strict_types=1);


namespace Tastaturberuf\ContaoImageCopyrightBundle\Controller;


use Contao\Config;
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


    /**
     * @var ImageFactoryInterface
     */
    private $imageFactory;

    /**
     * @var string
     */
    private $rootDir;

    /**
     * @var array
     */
    private $validImageExtensions;


    public function __construct(ImageFactoryInterface $imageFactory, string $rootDir, array $validImageExtensions)
    {
        $this->imageFactory         = $imageFactory;
        $this->rootDir              = $rootDir;
        $this->validImageExtensions = $validImageExtensions;
    }


    protected function getResponse(Template $template, ModuleModel $model, Request $request): ?Response
    {
        if ( null !== $files = $this->getImages() )
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


    private function getImages(): ?Collection
    {
        // mask all strings to e.g. 'jpeg', 'webp', ...
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
                sprintf("extension IN (%s)", join(',', $extensions))
            ]
        ];

        return FilesModel::findAll($options);
    }

}
