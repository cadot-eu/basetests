<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use Zenstruck\Browser\Test\HasBrowser;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Factory\CompteFactory;
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



    public function test_upload_without_get_file(): void
    {
        $file = new \Symfony\Component\HttpFoundation\File\UploadedFile('/app/tests/basetests/assets/image.png', 'test.jpg', 'image/jpeg', null, true);
        $name = 'upload';
        $filter = null;
        $this->Browser()->post('/upload/' . $name, [], [])
            ->assertJsonMatches('error', 'not get file');
    }
    public function test_upload_without_filter(): void
    {
        copy('/app/tests/basetests/assets/image.png', '/app/public/uploads/test.jpg');
        $client = static::createClient();
        $file = new \Symfony\Component\HttpFoundation\File\UploadedFile('/app/public/uploads/test.jpg', 'test.jpg', 'image/jpeg', null, true);
        $uniqid = uniqid();
        $client->request('POST', '/upload/upload' . $uniqid, [], [
            'upload' => $file
        ]);
        $retour = json_decode($client->getResponse()->getContent(), true)['url'];
        $retour_sans_id = FileUploader::decodeFilename($retour);
        $this->assertSame($retour_sans_id, '/uploads/upload' . $uniqid . '/test.jpg');
        //la présence réelle du fichier est vérifié par fileuploader
    }
    public function test_upload_with_filter(): void
    {
        copy('/app/tests/basetests/assets/image.png', '/app/public/uploads/test.jpg');
        $client = static::createClient();
        $uniqid = uniqid();
        $file = new \Symfony\Component\HttpFoundation\File\UploadedFile('/app/public/uploads/test.jpg', 'test.jpg', 'image/jpeg', null, true);
        $client->request('POST', '/upload/upload' . $uniqid . '/moyen', [], [
            'upload' => $file
        ]);
        $retour = json_decode($client->getResponse()->getContent(), true)['url'];
        $retour_sans_id = FileUploader::decodeFilename($retour);
        $this->assertSame($retour_sans_id, '/uploads/upload' . $uniqid . '/test-moyen.jpg');
        //la présence réelle du fichier est vérifié par fileuploader
    }
}
