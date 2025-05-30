<?php

declare(strict_types=1);

namespace Tastaturberuf\ContaoImageCopyrightBundle\Tests\EventListener;

use PHPUnit\Framework\TestCase;
use Tastaturberuf\ContaoImageCopyrightBundle\EventListener\DcaFilesListener;

class DcaFilesListenerTest extends TestCase
{
    private DcaFilesListener $listener;
    private array $validImageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->listener = new DcaFilesListener(
            $this->validImageExtensions
        );
    }

    public function testConstructorWithReadonlyProperties(): void
    {
        // Create a ReflectionClass to access private properties
        $reflection = new \ReflectionClass(DcaFilesListener::class);
        
        // Check if validImageExtensions property is declared as readonly
        $this->assertTrue($reflection->getProperty('validImageExtensions')->isReadOnly());
    }

    public function testLoadDataContainerMethod(): void
    {
        // Save the global DCA if it exists
        $originalDca = $GLOBALS['TL_DCA']['tl_files'] ?? null;
        
        // Create an empty DCA structure
        $GLOBALS['TL_DCA']['tl_files'] = [
            'palettes' => ['default' => ''],
        ];
        
        // Call the method we want to test
        $this->listener->loadImageCopyrightField('tl_files');
        
        // Verify that the copyright field was added to the DCA
        $this->assertArrayHasKey('fields', $GLOBALS['TL_DCA']['tl_files']);
        $this->assertArrayHasKey('image_copyright', $GLOBALS['TL_DCA']['tl_files']['fields']);
    }
}