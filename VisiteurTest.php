<?php

namespace App\Tests\basetests;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Panther\PantherTestCaseTrait;
use Zenstruck\Browser\PantherBrowser;
use Symfony\Component\Panther\PantherTestCase;
use Zenstruck\Browser\Test\HasBrowser;

/** @var \Zenstruck\Browser\PantherBrowser $pantherBrowser **/
class VisiteurTest extends PantherTestCase
{
    use HasBrowser;
    public function testGenerationSitemaps(): void
    {
        $this->Browser()
            ->visit('/sitemap.xml')
            ->assertSuccessful()
            ->assertXml()
        ;
    }
 
}
