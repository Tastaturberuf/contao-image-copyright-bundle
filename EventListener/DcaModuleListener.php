<?php

/**
 * ImageCopyright for Contao Open Source CMS
 *
 * @copyright   2016 – 2024 Tastaturberuf <tastaturberuf.de>
 * @author      Daniel Jahnsmüller <tastaturberuf.de>
 * @license     LGPL-3.0-or-later
 */

declare(strict_types=1);

namespace Tastaturberuf\ContaoImageCopyrightBundle\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Doctrine\DBAL\Connection;
use Tastaturberuf\ContaoImageCopyrightBundle\Controller\ImageCopyrightListController;
use function array_keys;


class DcaModuleListener
{

    private Connection $connection;


    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Priority must > 1 otherwise the callbacks are loaded too early
     */
    #[AsHook('loadDataContainer', priority: 1)]
    public function loadDataContainer(string $table): void
    {
        if ('tl_module' !== $table) {
            return;
        }

        $GLOBALS['TL_DCA'][$table]['palettes'][ImageCopyrightListController::TYPE] =
        '
            {title_legend},name,headline,type;
            {config_legend},imgSize,ic_folder,ic_order;
            {template_legend:hide},customTpl;
            {protected_legend:hide},protected;
            {expert_legend:hide},guests,cssID
        ';

        $GLOBALS['TL_DCA'][$table]['fields']['ic_folder'] = [
            'exclude'   => true,
            'inputType' => 'fileTree',
            'eval'      => [
                'fieldType' => 'radio',
                'tl_class'  => 'clr w50'
            ],
            'sql' => 'binary(16) NULL'
        ];

        $GLOBALS['TL_DCA'][$table]['fields']['ic_order'] = [
            'exclude'   => true,
            'inputType' => 'checkboxWizard',
            'eval'      => [
                'multiple' => true,
                'tl_class' => 'clr'
            ],
            'sql' => 'text NULL'
        ];

    }

    #[AsCallback('tl_module', 'fields.ic_order.options')]
    public function getOrderFields(): array
    {
        return array_keys($this->connection->createSchemaManager()->listTableColumns('tl_files'));
    }

}
