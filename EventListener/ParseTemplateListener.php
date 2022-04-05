<?php

namespace Tastaturberuf\ContaoImageCopyrightBundle\EventListener;

use Contao\CoreBundle\Routing\ScopeMatcher;
use Contao\FilesModel;
use Contao\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class ParseTemplateListener
{
    private ScopeMatcher $scopeMatcher;
    private ?Request $request;

    public function __construct(ScopeMatcher $scopeMatcher, RequestStack $requestStack)
    {
        $this->scopeMatcher = $scopeMatcher;
        $this->request = $requestStack->getCurrentRequest();
    }

    /**
     * Add the copyright fields to the image template
     *
     * @param Template $template
     *
     * @return void
     */
    public function onParseTemplate(Template $template): void
    {
        if (null === $this->request) {
            return;
        }

        if (false === $this->scopeMatcher->isFrontendRequest($this->request)) {
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
