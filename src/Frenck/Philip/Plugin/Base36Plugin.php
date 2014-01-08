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
 * Encodes / Decodes a string using Base36 encoding
 *
 * @author Franck Nijhof <frenck@gmail.com>
 */
class Base36Plugin extends AbstractPlugin
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
            '/^!b(ase)?36( e)?(ncode)?( me)? (\d*)/i',
            function (Event $event) use ($that) {
                $request = $event->getRequest();
                $matches = $event->getMatches();
                $match = array_pop($matches);
                $event->addResponse(Response::msg($request->getSource(), base_convert($match, 10, 36)));
                $event->stopPropagation();
            }
        );

        $this->bot->onChannel(
            '/^!b(ase)?36( d)?(ecode)?( me)? (.*)/i',
            function (Event $event) use ($that) {
                $request = $event->getRequest();
                $matches = $event->getMatches();
                $match = array_pop($matches);
                $event->addResponse(Response::msg($request->getSource(), base_convert($match, 36, 10)));
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
        return 'Base36Plugin';
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
            '!base36 encode <number> - Encode a string using base36.',
            '!base36 decode <string> - Decode a string using base36.',
        );
    }
}