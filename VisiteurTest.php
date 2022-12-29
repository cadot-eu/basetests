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

    public function testArtisandontexist(): void
    {
        $this->Browser()
            ->visit('/artisan/qsuinexistepas')
            ->assertSeeIn('title', 'Moteur de recherche sur les entreprises sélectionnées par Picbleu')
        ;
    }
    public function testArtisanexist(): void
    {
        $entrepriseRepository = $this->getContainer()->get('App\Repository\EntrepriseRepository');
        $entreprise = $entrepriseRepository->findBy(['deletedAt' => null, 'etat' => 'en ligne']);
        $this->Browser()
            ->visit('/artisan/'.$entreprise[0]->getSlug())
            ->assertSeeIn('title', 'Entreprise '.$entreprise[0]->getNom().' sélectionnée par Picbleu')
        ;
    }
    
    public function testrediectionForum(): void
    {
        $this->Browser()
            ->visit('/forum-questions/tests')
            ->assertSeeIn('title', 'Moteur de recherche sur les questions posées à Picbleu')
        ;
    }
    
    public function testredirectioncontactdevis(): void
    {
        $this->Browser()
            ->visit('contact-devis')
            ->assertSeeIn('title', 'Formulaire de contact')
        ;
    }
}
