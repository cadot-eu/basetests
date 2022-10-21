<?php

namespace App\Tests\basetests;

use PHPUnit\Framework\TestCase;
use Zenstruck\Browser\Test\HasBrowser;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Factory\CompteFactory;
use App\Service\base\FileHelper;
use App\Service\base\FileUploader;
use App\Service\base\TestHelper;
use App\Tests\basetests\Link;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;
use ReflectionClass;
use ReflectionProperty;
use Symfony\Component\Panther\PantherTestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Panther\Client;
use DOMNode;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @method AppBrowser browser()
 */
class ToolsControllerTest extends PantherTestCase
{
    use HasBrowser;
    use Factories;




    public function test_upload_without_filter(): void
    {
        copy('/app/tests/basetests/assets/image.jpg', '/app/public/uploads/test.jpg');
        $client = static::createClient();
        $file = new \Symfony\Component\HttpFoundation\File\UploadedFile('/app/public/uploads/test.jpg', 'test.jpg', 'image/jpeg', null, true);
        $uniqid = uniqid();
        $client->request('POST', '/upload/upload' . $uniqid, [], [
            'upload' => $file
        ]);
        $retour = json_decode($client->getResponse()->getContent(), true)['url'];
        $retour_sans_id = FileUploader::decodeFilename($retour);
        $this->assertSame($retour_sans_id, '/uploads/upload' . $uniqid . '/test.jpg');
        FileHelper::deleteDirectory_notempty('/app/public/uploads/upload' . $uniqid);
    }
    /**
     * test_upload_with_filter
     *
     * @return void
     */
    public function test_upload_with_filter(): void
    {
        copy('/app/tests/basetests/assets/image.jpg', '/app/public/uploads/test.jpg');
        $client = static::createClient();
        $uniqid = uniqid();
        $file = new \Symfony\Component\HttpFoundation\File\UploadedFile('/app/public/uploads/test.jpg', 'test.jpg', 'image/jpeg', null, true);
        $client->request('POST', '/upload/upload' . $uniqid . '/moyen', [], [
            'upload' => $file
        ]);
        $retour = json_decode($client->getResponse()->getContent(), true)['url'];
        $retour_sans_id = FileUploader::decodeFilename($retour);
        $this->assertSame($retour_sans_id, '/uploads/upload' . $uniqid . '/test-moyen.jpg');
        FileHelper::deleteDirectory_notempty('/app/public/uploads/upload' . $uniqid);
    }
    public function test_simplegallery(): void
    {
        copy('/app/tests/basetests/assets/image.jpg', '/app/public/uploads/test.jpg');
        $client = static::createClient();
        $uniqid = uniqid();
        $file = new \Symfony\Component\HttpFoundation\File\UploadedFile('/app/public/uploads/test.jpg', 'test.jpg', 'image/jpeg', null, true);
        $client->request('POST', '/simplegallery/upload' . $uniqid, [], [
            'upload' => $file
        ]);
        $retour = json_decode($client->getResponse()->getContent(), true)['urls'];
        foreach ($retour as $key => $value) {
            $retour_sans_id = explode('/uploads/', FileUploader::decodeFilename($value))[1];
            $this->assertSame($retour_sans_id, 'upload' . $uniqid . '/test.jpg');
        }
        FileHelper::deleteDirectory_notempty('/app/public/uploads/upload' . $uniqid);
    }
}
