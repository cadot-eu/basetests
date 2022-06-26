<?php

namespace App\Tests\basetests;



trait link
{
    function returnAllLinks($B, $start, $descent = -1, $links = [])
    {
        $exlinks = $links;
        $crawler = $B->visit($start)->crawler();
        //on regarde les liens de la page
        foreach ($crawler->filter('a[href]') as $link) { // on ne prend pas les liens sans href
            /** @var DOMElement $link */
            $url = $link->getAttribute('href');
            // on ne prend pas les liens déjà pris, ni les mailto ni les url extérieures
            if (!in_array(explode(':', $url)[0], ['mailto', 'http', 'https']) && explode('.', $url)[0] != 'www' &&  !isset($exlinks[$url])) {
                if ($descent > -1) { // si on est dans une récursivité acceptée
                    $links = $this->returnAllLinks($B, $url, $descent - 1, $links);
                } else {
                    $links[$url] = $link;
                }
            }
        }
        return $links;
    }
    public function testLinksByAttr($B, $start, $descent = 0)
    {
        dd($this->returnAllLinks($B, $start, $descent));
    }
}