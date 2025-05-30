<?php

declare(strict_types=1);

namespace Tastaturberuf\ContaoImageCopyrightBundle\Tests\EventListener;

use Contao\CoreBundle\Routing\ScopeMatcher;
use Contao\FilesModel;
use Contao\Template;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Tastaturberuf\ContaoImageCopyrightBundle\EventListener\ParseTemplateListener;

class ParseTemplateListenerTest extends TestCase
{
    private ParseTemplateListener $listener;
    private ScopeMatcher|MockObject $scopeMatcher;
    private RequestStack|MockObject $requestStack;
    private Request|MockObject $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->scopeMatcher = $this->createMock(ScopeMatcher::class);
        $this->requestStack = $this->createMock(RequestStack::class);
        $this->request = $this->createMock(Request::class);
        
        $this->listener = new ParseTemplateListener(
            $this->scopeMatcher,
            $this->requestStack
        );
    }

    public function testConstructorWithReadonlyProperties(): void
    {
        // Create a ReflectionClass to access private properties
        $reflection = new \ReflectionClass(ParseTemplateListener::class);
        
        // Check if properties are declared as readonly
        $this->assertTrue($reflection->getProperty('scopeMatcher')->isReadOnly());
        $this->assertTrue($reflection->getProperty('requestStack')->isReadOnly());
    }

    public function testOnParseTemplateWithNullRequest(): void
    {
        // Configure requestStack to return null for current request
        $this->requestStack->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn(null);
        
        // The scopeMatcher should not be called if request is null
        $this->scopeMatcher->expects($this->never())
            ->method('isFrontendRequest');
        
        $template = $this->createMock(Template::class);
        
        // Call the method
        $this->listener->onParseTemplate($template);
        
        // No assertions needed as we're just verifying the early return
    }

    public function testOnParseTemplateWithNonFrontendRequest(): void
    {
        // Configure requestStack to return a request
        $this->requestStack->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn($this->request);
        
        // Configure scopeMatcher to identify this as not a frontend request
        $this->scopeMatcher->expects($this->once())
            ->method('isFrontendRequest')
            ->with($this->request)
            ->willReturn(false);
        
        $template = $this->createMock(Template::class);
        
        // Call the method
        $this->listener->onParseTemplate($template);
        
        // No assertions needed as we're just verifying the early return
    }
    
    public function testOnParseTemplateWithNonImageTemplate(): void
    {
        // Create a template with a name that doesn't start with 'image'
        $template = $this->getMockBuilder(Template::class)
            ->disableOriginalConstructor()
            ->getMock();
        $template->expects($this->once())->method('getName')->willReturn('text_template');
        
        $this->listener->onParseTemplate($template);
    }
}