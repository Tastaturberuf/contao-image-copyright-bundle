<?php

declare(strict_types=1);

namespace Tastaturberuf\ContaoImageCopyrightBundle\Tests\EventListener;

use Doctrine\DBAL\Connection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tastaturberuf\ContaoImageCopyrightBundle\EventListener\DcaModuleListener;

class DcaModuleListenerTest extends TestCase
{
    private DcaModuleListener $listener;
    private Connection|MockObject $connection;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->connection = $this->createMock(Connection::class);
        $this->listener = new DcaModuleListener(
            $this->connection
        );
    }

    public function testConstructorWithReadonlyProperties(): void
    {
        // Create a ReflectionClass to access private properties
        $reflection = new \ReflectionClass(DcaModuleListener::class);
        
        // Check if connection property is declared as readonly
        $this->assertTrue($reflection->getProperty('connection')->isReadOnly());
    }

    public function testLoadDataContainerMethod(): void
    {
        // Save the global DCA if it exists
        $originalDca = $GLOBALS['TL_DCA']['tl_module'] ?? null;
        
        // Create an empty DCA structure
        $GLOBALS['TL_DCA']['tl_module'] = [
            'palettes' => [],
        ];
        
        // Call the method we want to test
        $this->listener->loadImageCopyrightListFields('tl_module');
        
        // Verify that the palette was added to the DCA
        $this->assertArrayHasKey('image_copyright_list', $GLOBALS['TL_DCA']['tl_module']['palettes']);
    }
}