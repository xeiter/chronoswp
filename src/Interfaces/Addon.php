<?php

namespace ChronosWP\Interfaces;

/**
 * Interface for Addon
 *
 * @package   ChronosWP
 * @author    Anton Zaroutski <anton@zaroutski.com>
 */
interface Addon {

    /**
     * Register addon
     *
     * @since 1.0
     * @param string $name
     * @param array $options
     * @return mixed
     */
    public function register( $name, array $options );

    /**
     * Set addon's dependencies
     *
     * @since 1.0
     * @param array $dependency
     * @return mixed
     */
    public function setDependencies(array $dependency );

    /**
     * Get name of addon
     *
     * @since 1.0
     * @return string
     */
    public function getName();

	/**
	 * Get reference of addon
	 *
	 * @since 1.0
	 * @return string
	 */
	public function getReference();

    /**
     * Get current version of addon
     *
     * @since 1.0
     * @return string
     */
    public function getCurrentVersion();

    /**
     * Get addon's dependencies
     *
     * @since 1.0
     * @return mixed
     */
    public function getDependencies();

    /**
     * Install the addon
     *
     * @since 1.0
     * @return mixed
     */
    public function install();

    /**
     * Uninstall the addon
     *
     * @since 1.0
     * @return mixed
     */
    public function uninstall();

    /**
     * Activate the addon
     *
     * @since 1.0
     * @return mixed
     */
    public function activate();

    /**
     * Deactivate the addon
     *
     * @since 1.0
     * @return mixed
     */
    public function deactivate();

}