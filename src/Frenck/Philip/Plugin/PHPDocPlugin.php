<?php
/**
 * This file is part of Frenck's Philip Plugins Package
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 *
 * @copyright 2014 Franck Nijhof <frenck@gmail.com>
 * @link      http://frenck.nl
 */

namespace Frenck\Philip\Plugin;

use Philip\AbstractPlugin;
use Philip\IRC\Response;
use Philip\IRC\Event;
use Goutte\Client;

/**
 * Shows PHP Documentation
 *
 * @author Franck Nijhof <frenck@gmail.com>
 */
class PhpDocPlugin extends AbstractPlugin
{
    /**
     * Initializing the plugin's behavior
     *
     * @return void
     */
    public function init()
    {
        $that = $this;

        $this->bot->onChannel(
            '/^!php(doc)? (.*)/i',
            function (Event $event) use ($that) {
                $request = $event->getRequest();
                $matches = $event->getMatches();
                $match = array_pop($matches);

                $client = new Client();
                $crawler = $client->request(
                    'GET',
                    sprintf(
                        'http://www.php.net/%s',
                        str_replace('_', '-', $match)
                    )
                );

                if ($crawler->filter('.refnamediv h1.refname')->count() !== 0) {
                    $function = $crawler->filter('.refnamediv h1.refname')->first()->text();
                    $description = $crawler->filter('.refnamediv span.dc-title')->first()->text();
                    $version = $crawler->filter('.refnamediv p.verinfo')->first()->text();
                    $synopsis = $crawler->filter('.methodsynopsis')->first()->text();

                    $synopsis = preg_replace('/\s+/', ' ', $synopsis);
                    $synopsis = preg_replace('/(\r\n|\n|\r)/m', ' ', $synopsis);
                    $synopsis = trim($synopsis);

                    $event->addResponse(
                        Response::msg(
                            $request->getSource(),
                            sprintf(
                                '%s - %s %s',
                                $function,
                                $description,
                                $version
                            )
                        )
                    );

                    $event->addResponse(
                        Response::msg(
                            $request->getSource(),
                            sprintf(
                                'Synopsis: %s',
                                $synopsis
                            )
                        )
                    );

                } else {
                    $suggestion = $crawler->filter('#quickref_functions li a b')->first()->text();
                    $event->addResponse(
                        Response::msg(
                            $request->getSource(),
                            sprintf(
                                'Could not find the requested PHP function. Did you mean: %s?',
                                $suggestion
                            )
                        )
                    );
                }



            }
        );
    }

    /**
     * Returns plugin name
     *
     * @return string
     */
    public function getName()
    {
        return 'PhpDocPlugin';
    }

    /**
     * Returns a help message for the plugin.
     *
     * @param Event $event The event
     *
     * @return string|array A simple help message.
     */
    public function displayHelp(Event $event)
    {
        return array(
            '!phpdoc <function> - PHP Documentation'
        );
    }
}