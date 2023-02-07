<?php

namespace App\Tests\basetests;

use App\Service\base\FileHelper;
use App\Service\base\FileUploader;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\Panther\PantherTestCase;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;

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
            'upload' => $file,
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
   
    public function test_simplegallery(): void
    {
        copy('/app/tests/basetests/assets/image.jpg', '/app/public/uploads/test.jpg');
        $client = static::createClient();
        $uniqid = uniqid();
        $file = new \Symfony\Component\HttpFoundation\File\UploadedFile('/app/public/uploads/test.jpg', 'test.jpg', 'image/jpeg', null, true);
        $client->request('POST', '/simplegallery/upload' . $uniqid, [], [
            'file-0' => $file,
        ]);
        $retour = json_decode($client->getResponse()->getContent(), true)['result'];
        foreach ($retour as $key => $value) {
            //on contrôle que l'on a bien un https
            $retour_sans_id = explode('/uploads/', FileUploader::decodeFilename($value['url']))[1];
            $this->assertSame($retour_sans_id, 'upload' . $uniqid . '/test.jpg');
        }
        FileHelper::deleteDirectory_notempty('/app/public/uploads/upload' . $uniqid);
    }
    // public function test_createPngForTemplate(): void
    // {
    //     $client = static::createClient();
    //     $client->request('GET', '/tools/getPngForTemplate/coucou');
    //     $this->assertSame($client->getResponse()->getStatusCode(), 200);
    // }
}
