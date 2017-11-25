<?php

namespace ChronosWP\Base;

class Dependency
{

	/**
	 * Name of the dependency
	 *
	 * @var string
	 * @access protected
	 */
	protected $name;

	/**
	 * Plugin name of the dependency
	 *
	 * @var string
	 * @access protected
	 */
	protected $pluginName;

	/**
	 * Get name of dependency
	 *
	 * @return string
	 * @access public
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Set plugin's name
	 *
	 * @return string
	 */
	public function getPluginName()
	{
		return $this->pluginName;
	}

	/**
	 * Register a dependency
	 *
	 * @param string $name
	 * @param array $options
	 */
	public function register($name, array $options)
	{
	}

}