<?php

namespace App\basetests\Tests;

use App\Service\base\FileUploader;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FileUploaderTest extends KernelTestCase
{
    public function testEncodeDecodeFilename(): void
    {
        $kernel = self::bootKernel();

        $this->assertSame('test', $kernel->getEnvironment());
        $encode = FileUploader::encodeFilename('image de l\'araignée.jpg');
        $this->assertSame('image de l\'araignée.jpg', FileUploader::decodeFilename($encode));
    }
    public function testEncodeDecodeFilenameWithUniqid(): void
    {
        $kernel = self::bootKernel();

        $this->assertSame('test', $kernel->getEnvironment());
        $encode = FileUploader::encodeFilename('image de l\'araignée.jpg');
        $this->assertSame('image de l\'araignée-' . explode('-', $encode)[1], FileUploader::decodeFilename($encode, false));
    }
}
