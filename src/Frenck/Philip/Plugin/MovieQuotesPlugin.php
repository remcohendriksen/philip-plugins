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
use Guzzle\Http\Client;

/**
 * Returns random movie quotes
 *
 * @author Franck Nijhof <frenck@gmail.com>
 */
class MovieQuotesPlugin extends AbstractPlugin
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
            '/^!lotr/i',
            function (Event $event) use ($that) {
                $request = $event->getRequest();
                $event->addResponse(Response::msg($request->getSource(), $that->getMovieQuote('lotr')));
            }
        );

        $this->bot->onChannel(
            '/^!tarantino/i',
            function (Event $event) use ($that) {
                $request = $event->getRequest();
                $event->addResponse(Response::msg($request->getSource(), $that->getMovieQuote('qt')));
            }
        );

        $this->bot->onChannel(
            '/^!b(reaking)?bad/i',
            function (Event $event) use ($that) {
                $request = $event->getRequest();
                $event->addResponse(Response::msg($request->getSource(), $that->getMovieQuote('bb')));
            }
        );

    }

    /**
     * Get a random movie quote from
     *
     * @param string $source Movie source to get the quote from
     *
     * @return string the commit message
     */
    public function getMovieQuote($source)
    {
        $httpClient = new Client('http://www.randquotes.com/');
        $httpRequest = $httpClient->get('/' . $source);
        $httpResponse = $httpRequest->send();

        if ($httpResponse->isSuccessful() === true) {
            return $httpResponse->getBody(true);
        }

        return 'Sorry! I was unable to scrape the greatest movie quote ever :(';
    }

    /**
     * Returns plugin name
     *
     * @return string
     */
    public function getName()
    {
        return 'MovieQuotePlugin';
    }

    /**
     * Returns a help message for the plugin.
     *
     * @param Event $event The event
     *
     * @return string A simple help message.
     */
    public function displayHelp(Event $event)
    {
        return array(
            '!bbad - Gives a random quote from Breaking Bad',
            '!lotr - Gives a random quote from the Lord of the Rings movies.',
            '!tarantino - Gives a random quote from movies made by Quentin Tarantino',
        );
    }
}