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


use Contao\CoreBundle\ServiceAnnotation\Callback;
use Contao\DataContainer;
use Tastaturberuf\ContaoImageCopyrightBundle\Controller\ImageCopyrightListController;
use Terminal42\ServiceAnnotationBundle\ServiceAnnotationInterface;


class DcaModuleListener implements ServiceAnnotationInterface
{

    /**
     * @Callback(table="tl_module", target="config.onload")
     */
    public function onLoadCallback(DataContainer $dc): void
    {
        $GLOBALS['TL_DCA'][$dc->table]['palettes'][ImageCopyrightListController::TYPE] =
        '
            {title_legend},name,headline,type;
            {config_legend},imgSize;
            {template_legend:hide},customTpl;
            {protected_legend:hide},protected;
            {expert_legend:hide},guests,cssID
        ';
    }

}
