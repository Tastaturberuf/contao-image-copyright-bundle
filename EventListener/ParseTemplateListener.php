<?php // with ♥ and Contao

/**
 * ImageCopyright for Contao Open Source CMS
 *
 * @copyright   2016 – 2022 Tastaturberuf <tastaturberuf.de>
 * @author      Daniel Jahnsmüller <tastaturberuf.de>
 * @license     LGPL-3.0-or-later
 */

declare(strict_types=1);


namespace Tastaturberuf\ContaoImageCopyrightBundle\EventListener;


use Contao\CoreBundle\Routing\ScopeMatcher;
use Contao\FilesModel;
use Contao\Template;
use Symfony\Component\HttpFoundation\RequestStack;


class ParseTemplateListener
{

    public function __construct(private readonly ScopeMatcher $scopeMatcher, private readonly RequestStack $requestStack)
    {
    }

    /**
     * Add the copyright fields to the image template
     */
    public function onParseTemplate(Template $template): void
    {
        if (null === $request = $this->requestStack->getCurrentRequest()) {
            return;
        }

        if (false === $this->scopeMatcher->isFrontendRequest($request)) {
            return;
        }

        // Only the image template should be used
        if (false === str_starts_with($template->getName(), 'image')) {
            return;
        }

        // Load the image with the given uuid
        $uuid = $template->__get('uuid');
        $file = FilesModel::findByUuid($uuid);
        if (null === $file) {
            return;
        }

        // Add the file data to the template
        $template->__set('ic_href', $file->__get('ic_href'));
        $template->__set('ic_copyright', $file->__get('ic_copyright'));
    }
}
