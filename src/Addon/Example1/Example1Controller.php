<?php

namespace ChronosWP\Addon\Example1;

use ChronosWP\Interfaces as Interfaces;
use ChronosWP\Base as Base;

class Example1Controller extends Base\Controller implements Interfaces\Controller {

    public function run($element)
	{
		// Prepare data for default element
		$this->setVariable('test', 'default');
    }

    public function runMasonry()
	{
		// Prepare data for Masonry post tiles element
		$this->setVariable('test', 'Masonry');
		$this->setVariable('test2', 'Lalala');
	}

	public function runTwoColumn()
	{
		// Prepare data for Two Column post tiles element
		$this->setVariable('test', 'Two column');
	}

}
