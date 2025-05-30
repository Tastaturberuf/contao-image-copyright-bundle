<?php

declare(strict_types=1);

namespace Tastaturberuf\ContaoImageCopyrightBundle\Tests\Controller;

use Contao\CoreBundle\Image\ImageFactoryInterface;
use Contao\FilesModel;
use Contao\ModuleModel;
use Contao\StringUtil;
use Contao\System;
use Contao\Template;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Tastaturberuf\ContaoImageCopyrightBundle\Controller\ImageCopyrightListController;

class ImageCopyrightListControllerTest extends TestCase
{
    private ImageCopyrightListController $controller;
    private ImageFactoryInterface|MockObject $imageFactory;
    private string $rootDir = '/var/www';
    private array $validImageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    protected function setUp(): void
    {
        parent::setUp();

        $this->imageFactory = $this->createMock(ImageFactoryInterface::class);
        $this->controller = new ImageCopyrightListController(
            $this->imageFactory,
            $this->rootDir,
            $this->validImageExtensions
        );
    }

    public function testConstructorWithReadonlyProperties(): void
    {
        // Create a ReflectionClass to access private properties
        $reflection = new \ReflectionClass(ImageCopyrightListController::class);
        
        // Check if properties are declared as readonly
        $this->assertTrue($reflection->getProperty('imageFactory')->isReadOnly());
        $this->assertTrue($reflection->getProperty('rootDir')->isReadOnly());
        $this->assertTrue($reflection->getProperty('validImageExtensions')->isReadOnly());
    }

    /**
     * Test the arrow function used in getImages method for mapping extensions
     */
    public function testArrowFunctionForExtensionMapping(): void
    {
        // Create a mock for ModuleModel
        $moduleModel = $this->createMock(ModuleModel::class);
        $moduleModel->ic_folder = null;
        $moduleModel->ic_order = null;

        // Create a mock collection with some dummy files
        $filesCollection = $this->getMockBuilder(\Contao\Model\Collection::class)
            ->disableOriginalConstructor()
            ->getMock();

        // Mock the FilesModel to return our collection
        $filesModelAdapter = $this->getMockBuilder(FilesModel::class)
            ->disableOriginalConstructor()
            ->addMethods(['findBy'])
            ->getMock();
        $filesModelAdapter->expects($this->once())
            ->method('findBy')
            ->willReturn($filesCollection);

        // Set up the System class to return our adapter
        System::setContainer($this->createMock(\Symfony\Component\DependencyInjection\ContainerInterface::class));
        
        // Use reflection to access the private getImages method
        $reflection = new \ReflectionClass(ImageCopyrightListController::class);
        $getImagesMethod = $reflection->getMethod('getImages');
        $getImagesMethod->setAccessible(true);

        // Override the static class
        FilesModel::$strTable = 'tl_files';
        
        // Call the method and assert the result
        $result = $getImagesMethod->invoke($this->controller, $moduleModel);
        
        // The assertion here is mainly that the method completes without error
        // as we're mocking the database interaction
        $this->assertSame($filesCollection, $result);
    }

    /**
     * Test the controller type constant
     */
    public function testControllerType(): void
    {
        $this->assertSame('image_copyright_list', ImageCopyrightListController::TYPE);
    }
    
    /**
     * Test getResponse method (partial test)
     */
    public function testGetResponseMethod(): void
    {
        // This is a simplified test to check the method's structure
        // A complete test would require more extensive mocking
        $method = new \ReflectionMethod(ImageCopyrightListController::class, 'getResponse');
        $parameters = $method->getParameters();
        
        $this->assertCount(3, $parameters);
    }
}