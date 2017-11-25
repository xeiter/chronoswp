<?php

namespace ChronosWP\Addon\Example1;

use ChronosWP\Interfaces as Interfaces;
use ChronosWP\Base as Base;

class Example1Addon extends Base\Addon implements Interfaces\Addon {

    /**
     * Class constructor
     */
    public function __construct()
	{


		// echo \App\template('partials.section-awards');
		// Set general options of the addon
        $this->reference = __('Example1', 'chwp');
        $this->name = __('Example 1', 'chwp');
        $this->description = __( 'Example addon', 'chwp' );
        $this->currentVersion = '0.1';
        $this->setDirectory(__DIR__);
		parent::__construct($this->directory);


        // Declare dependencies
        $dependencies = ['piklist'];
        $this->setDependencies($dependencies);

		// Declare available elements
		$elements = [
			'element-1' => 'Element 1',
			'element-2' => 'Element 2'
		];
		$this->setElements($elements);
    }

}
