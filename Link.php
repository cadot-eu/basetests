<?php

namespace App\Tests\basetests;



trait link
{
    /**
     * It visits a page, gets all the links, and then visits them all, checking that they return a 200
     * status code
     * 
     * @param B the browser instance
     * @param start the url to start from
     * @param test if true, the function will test the links, if false, it will return an array of
     * links
     * @param descent the number of levels of recursion to go through. -1 means no recursion.
     * @param links the array of links already visited
     * 
     * @return an array of links.
     */
    function returnAllLinks($B, $start, $test = true, $descent = -1, $links = [])
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
                    $links = $this->returnAllLinks($B, $url, $test, $descent - 1, $links);
                } else {
                    $links[$url] = $link;
                }
            }
        }
        if ($test) {
            foreach ($links as $link) {
                dump('test:' . $link->getAttribute('href') . '|' .  str_replace(["\n", "\t"], "", $link->nodeValue));
                $B->visit($link->getAttribute('href'))->assertStatus(200);
            }
        } else {
            return $links;
        }
    }
}
