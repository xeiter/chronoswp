<?php

namespace ChronosWP\Interfaces;

/**
 * Interface for a Plugin Dependency
 *
 * @package   ChronosWP
 * @author    Anton Zaroutski <anton@zaroutski.com>
 */
interface Dependency {

    /**
     * Register a dependency
     *
     * @param array $name
     * @param array $options
     * @return mixed
     */
    public function register($name, array $options);

    /**
     * Get name
     *
     * @return string
     */
    public function getName();

    /**
     * Get plugin name
     *
     * @return string
     */
    public function getPluginName();

}