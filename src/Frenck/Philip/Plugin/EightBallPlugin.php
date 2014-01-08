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
 * The famous eight ball! This one however is constant in it's output on the same input
 *
 * @author Franck Nijhof <frenck@gmail.com>
 */
class EightBallPlugin extends AbstractPlugin
{
    public $responses = array(
        "It is certain",
        "It is decidedly so",
        "Without a doubt",
        "Yes – definitely",
        "You may rely on it",
        "As I see it, yes",
        "Most likely",
        "Outlook good",
        "Signs point to yes",
        "Yes",
        "Reply hazy, try again",
        "Ask again later",
        "Better not tell you now",
        "Cannot predict now",
        "Concentrate and ask again",
        "Don't count on it",
        "My reply is no",
        "My sources say no",
        "Outlook not so good",
        "Very doubtful",
    );

    /**
     * Initializing the plugin's behavior
     *
     * @return void
     */
    public function init()
    {
        $that = $this;

        $this->bot->onChannel(
            '/^!(eightball|8|8ball) (.*)/i',
            function (Event $event) use ($that) {
                $request = $event->getRequest();
                $matches = $event->getMatches();
                $match = array_pop($matches);
                $event->addResponse(
                    Response::msg(
                        $request->getSource(),
                        $that->responses[(base_convert(strtolower($match), 36, 10) % count($that->responses))]
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
        return 'EightBallPlugin';
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
            '!8ball <question> - Ask the magic eight ball a question'
        );
    }
}