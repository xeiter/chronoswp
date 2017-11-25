<?php

namespace ChronosWP\Dependency;

use ChronosWP\Interfaces as Interfaces;
use ChronosWP\Base as Base;

class Piklist extends Base\Dependency implements Interfaces\Dependency {

    /**
     * Class constructor
     */
    public function __construct() {

        $this->name       = 'Piklist';
        $this->pluginName = 'piklist';

    }

}