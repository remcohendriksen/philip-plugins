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

/**
 * Motivate somebody! http://motivate.im/
 *
 * @author Franck Nijhof <frenck@gmail.com>
 */
class MotivatePlugin extends AbstractPlugin
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
            '/^!m(otivate)? (.*)/i',
            function (Event $event) use ($that) {
                $request = $event->getRequest();
                $matches = $event->getMatches();
                $match = array_pop($matches);
                $event->addResponse(
                    Response::msg(
                        $request->getSource(),
                        sprintf(
                            'You\'re doing good work, %s!',
                            trim($match)
                        )
                    )
                );
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
        return 'MotivatePlugin';
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
        return '!m <nick> - Motivate someone.';
    }
}