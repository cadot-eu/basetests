<?php

namespace App\Tests\basetests;

use PHPUnit\Framework\TestCase;
use Zenstruck\Browser\Test\HasBrowser;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Factory\UserFactory;
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
use Symfony\Bundle\FrameworkBundle\Test\MailerAssertionsTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @method AppBrowser browser()
 */
class MailTest extends WebTestCase
{

    use MailerAssertionsTrait;

    public function testMailIsSentAndContentIsOk()
    {

        //$client = $this->createClient();
        //$client->request('GET', '/testmail/contact@picbleu.fr');
        //$this->assertResponseIsSuccessful();

        // $this->assertEmailCount(1);

        // $email = $this->getMailerMessage();
        // $this->assertEmailTextBodyContains($email, 'Sending emails is fun again!');
    }
}
