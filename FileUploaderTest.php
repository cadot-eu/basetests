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
        $this->assertSame('image-de-laraignee.jpg', FileUploader::decodeFilename($encode));
    }
}
