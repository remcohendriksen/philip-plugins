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
 * Gives a random commit message
 *
 * @author Franck Nijhof <frenck@gmail.com>
 */
class WhatTheCommitPlugin extends AbstractPlugin
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
            '/^!(wtc|cm)/i',
            function (Event $event) use ($that) {
                $request = $event->getRequest();
                $data = $that->getCommitMessage();
                $event->addResponse(Response::msg($request->getSource(), $data));
            }
        );
    }

    /**
     * Get a commit message from WhatTheCommit.com
     *
     * @return string the commit message
     */
    public function getCommitMessage()
    {
        $httpClient = new Client('http://whatthecommit.com');
        $httpRequest = $httpClient->get('/index.txt');
        $httpResponse = $httpRequest->send();

        if ($httpResponse->isSuccessful() === true) {
            return ucfirst($httpResponse->getBody(true));
        }

        return 'Sorry! I was unable to scrape an awesome commit message from the interwebs :(';
    }

    /**
     * Returns plugin name
     *
     * @return string
     */
    public function getName()
    {
        return 'WhatTheCommitPlugin';
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
        return '!cm - Gives a random commit message.';
    }
}