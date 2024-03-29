<?php

namespace App\Tests\basetests;

use Faker\Factory;
use Zenstruck\Mailer\Test\Bridge\Zenstruck\Browser\MailerComponent;
use Zenstruck\Mailer\Test\TestEmail;

trait Form
{
    private RouterInterface $router;

   
    
    /**
     * It fills a form with random data, and then clicks the submit button
     * 
     * @param B the browser object
     * @param url the url of the page you want to test
     * @param erreurCaptcha if true, the captcha will be filled with the wrong value.
     * @param champEmailVrai if you want to fill the email field with a real email address, pass it
     * here.
     * 
     * @return The return value of the last statement executed in the function.
     */
    public function remplisFormulaire($B, $url, $erreurCaptcha = false, $champEmailVrai = false, $language = 'fr_FR',$addchamps=[])
    {
        $language = $language ?? 'fr_FR';
        $faker = Factory::create($language);
        $visit = $B->visit($url);
        $crawler = $visit->crawler();
        $form = $crawler->selectButton('bouton_submit')->form();
        dd($form);
        $champs = $form->getValues();
        $champs = array_merge($champs,$addchamps);
        foreach ($champs as $nom => $value) {
            /** @var DOMElement $node */
            $node = $crawler->selectButton($nom)->getNode(0);
            if ($node) {
                $type = $node->getAttribute('type');
                switch ($type) {
                    case 'text':
                        switch (true) {
                            case $this->strInArray($nom, ['nom', 'name']):
                                $visit->fillField($nom, $faker->name());
                                break;
                            case $this->strInArray($nom, ['email', 'mail']):
                                if ($champEmailVrai) {
                                    $visit->fillField($nom, $champEmailVrai);
                                } else {
                                    $visit->fillField($nom, $faker->safeEmail());
                                }
                                break;
                            case 'captcha':
                                if ($erreurCaptcha) {
                                    $valCaptcha = $crawler->selectButton('try')->getNode(0)->getAttribute('value');
                                } else {
                                    $valCaptcha = strrev($crawler->selectButton('try')->getNode(0)->getAttribute('value'));
                                }
                                $visit->fillField('captcha', $valCaptcha);
                                break;
                            default:
                                dump("type de champ ($nom) non défini à ajouter dans le switch de text");
                                break;
                        }

                        break;

                    case 'email':
                        if ($champEmailVrai) {
                            $visit->fillField($nom, $champEmailVrai);
                        } else {
                            $visit->fillField($nom, $faker->safeEmail());
                        }
                        break;
                    case 'tel':
                        $visit->fillField($nom, '0132652545');//+33..
                        break;
                    case 'inconnu':
                    case 'hidden':
                        break;
                        case 'checkbox':
                            $visit->checkField($nom);
                            break;
                    default:
                        dd("type de champ '$type' inconnu à ajouter dans le switch");
                        break;
                }
            } else { //pour les textareas
                $visit->fillField($nom, $faker->realText(50));
            }
        }
        dd($visit->clickAndIntercept('bouton_submit')->profile()->getCollector('db'));
        ; //verify 200
    }

    public function strInArray($string, $arrayOfSubstring)
    {
        foreach ($arrayOfSubstring as $sub) {
            if (strpos($string, $sub) !== false) {
                return true;
            }
            //test sur []
            preg_match('#\[(.*?)\]#', $string, $match);
            if (isset($match[1]) && $match[1] == $sub) {
                return true;
            }
        }

        return false;
    }
}
