<?php
/**
 * This file is part of Frenck's Philip Plugins Package
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 *
 * @copyright 2014 Remco Hendriksen <remco.hendriksen@webprint.nl>
 * @link      http://remcohendriksen.nl
 */

namespace Frenck\Philip\Plugin;

use Philip\AbstractPlugin;
use Philip\IRC\Response;
use Philip\IRC\Event;
use Guzzle\Http\Client;

/**
 * Returns random Family Guy quotes
 *
 * @author Franck Nijhof <frenck@gmail.com>
 */
class PeterGriffinPlugin extends AbstractPlugin
{
    /**
     * Initializing the plugin's behavior
     *
     * @return void
     */
    public function init()
    {
        $that = $this;
				$file = file_get_contents(__DIR__.'/PeterGriffinPlugin/quotes.txt');
				$this->quotes = explode("\n%\n", $file);
				
        $this->bot->onChannel(
            '/^!(pt|petergriffin)(.*)/i',
            function (Event $event) use ($that) {
                $request = $event->getRequest();
								$matches = $event->getMatches();
								$match = array_pop($matches);
								$quoteLines = explode("\n", $this->getQuote($match));
								
								foreach($quoteLines AS $quoteLine)
								{
                	$event->addResponse(Response::msg($request->getSource(), $quoteLine));
								}
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
    public function getQuote($match)
    {
			
			shuffle($this->quotes);
			if(!empty($match))
			{
				foreach($this->quotes AS $quote)
				{
					if(strpos($quote, $match))
					{
						return $quote;
					}
				}
			}
			
			return $this->quotes[0];
    }

    /**
     * Returns plugin name
     *
     * @return string
     */
    public function getName()
    {
        return 'PeterGriffinPlugin';
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
            '!bbad - Gives a random quote from Family Guy',
        );
    }
}