<?php

namespace ChronosWP\Base;

use ChronosWP\Exception;

/**
 * Class Addon
 * @package ChronosWP\Base
 */
class Addon
{

	/**
	 * Addon's dependencies
	 *
	 * @var array
	 */
	protected $dependencies = array();

	/**
	 * Reference of the addon
	 *
	 * @var string
	 * @access protected
	 */
	protected $reference;

	/**
	 * Dashed reference of the addon
	 *
	 * @var string
	 * @access protected
	 */
	protected $referenceDashed;

	/**
	 * Name of the addon
	 *
	 * @var string
	 * @access protected
	 */
	protected $name;

	/**
	 * Description of the addon
	 *
	 * @var string
	 * @access protected
	 */
	protected $description;

	/**
	 * Current version of addon
	 *
	 * @var string
	 * @access protected
	 */
	protected $currentVersion;

	/**
	 * List of database queries that create custom database tables
	 *
	 * @var array
	 * @access protected
	 */
	protected $customDBTableQueries = array();

	/**
	 * Directory of the addon
	 * @var null|string
	 */
	protected $directory = null;

	/**
	 * URL of the scripts directory
	 *
	 * @var null|string
	 * @access protected
	 */
	protected $scriptsDirectoryURL = null;

	/**
	 * URL of the CSS directory
	 *
	 * @var null|string
	 * @access protected
	 */
	protected $cssDirectoryURL = null;

	/**
	 * Specifies whether the addon is active or not
	 * @var bool
	 * @access protected
	 */
	protected $active = false;

	/**
	 * Specifies elements available in this addon
	 * @var bool
	 * @access protected
	 */
	protected $elements = false;

	/**
	 * CSS assets used by addon
	 *
	 * @var array
	 * @access protected
	 */
	protected $cssAssets = [];

	/**
	 * Script assets used by addon
	 *
	 * @var array
	 * @access protected
	 */
	protected $scriptAssets = [];

	/**
	 * View file name suffix
	 *
	 * @var string
	 * @access private
	 */
	private $viewFilenameSuffix = '.php';

	/**
	 * Class constructor
	 */
	public function __construct($directory)
	{
		// Set the directory of the addon
		$this->directory = $directory;

		// Validate the addon
		$errors = $this->validate();
		if (!$errors['valid']) {
			throw new Exception('Addon class (' . $this->getReference() . ') has not passed validation');
		}
	}

	/**
	 * Register an addon
	 *
	 * @param string $name
	 * @param array $options
	 */
	public function register($name, array $options)
	{
		$this->name = $name;
	}

	/**
	 * Get name of addon
	 *
	 * @return string
	 * @access public
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Get reference of addon
	 *
	 * @return string
	 * @access public
	 */
	public function getReference()
	{
		return $this->reference;
	}

	/**
	 * Get current version of addon
	 *
	 * @return string
	 * @access public
	 */
	public function getCurrentVersion()
	{
		return $this->currentVersion;
	}

	/**
	 * Get description of addon
	 *
	 * @return string
	 * @access public
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * Set addon's dependencies
	 *
	 * @param array $dependencies
	 *
	 * @access public
	 * @return null
	 */
	public function setDependencies(array $dependencies)
	{
		foreach ($dependencies as $dependency) {
			$namespace = '\\ChronosWP\\Dependency\\';
			$className = $namespace . $dependency;
			$depClass = new $className();
			$this->dependencies[] = $depClass;
		}
	}

	/**
	 * Get addon's dependencies
	 *
	 * @return array
	 * @access public
	 */
	public function getDependencies()
	{
		return $this->dependencies;
	}

	/**
	 *
	 *
	 * @param $value
	 */
	public function setDatabaseTablesQueries(array $value)
	{
		$this->customDBTableQueries = $value;
	}

	/**
	 * Run custom database queries for the addon
	 *
	 * @access protected
	 */
	protected function maybeUpdateDb()
	{
		global $wpdb;
		$classname = strtolower(get_class($this));
		$classname = str_replace('\\', '_', $classname);
		$optionName = $classname . '_db_version';
		$currentVersion = get_option($optionName, '0');
		foreach ($this->customDBTableQueries as $version => $queries) {
			if (version_compare($currentVersion, $version, '<')) {
				foreach ($queries as $query) {
					$wpdb->query($query);
				}
				update_option($optionName, $version);
				$currentVersion = $version;
			}
		}
	}

	/**
	 * Perform actions during the install process of a plugin
	 */
	public function install()
	{
		$this->maybeUpdateDb();
	}

	public function uninstall()
	{
	}

	public function activate()
	{
	}

	public function deactivate()
	{
	}

	/**
	 * Render view using the controller's data
	 *
	 * @param string $addon
	 *
	 * @return mixed
	 */
	public function render($element)
	{
		// Do not render if addon is inactive
		if ( ! $this->isActive()) {
			return false;
		}

		$addon = $this->getReference();

		if ($controllerLoaded = $this->locateController($addon)) {
			// Enqueue the assets
			$this->enqueueCSSAssets();
			$this->enqueueScriptAssets();

			$controllerClassName = '\\ChronosWP\\Addon\\' . $addon . '\\' . self::prepareControllerClassName($addon);
			$controller = new $controllerClassName($addon, $element);
			$controller->setDirectory($this->getDirectory());
			$controller->setViewFilenameSuffix($this->viewFilenameSuffix);

			return $controller->renderView($element);
		}

		return false;

	}

	/**
	 * Check that controller file exists in the theme and load it
	 *
	 * @param string $element
	 *
	 * @return bool
	 * @static
	 */
	private function prepareControllerClassName($element)
	{
		return str_replace(' ', '_',
				ucwords(str_replace('-', ' ', $element))) . CHRONOSWP_ELEMENT_CONTROLLER_CLASS_NAME_SUFFIX;
	}

	/**
	 * Check that cont    roller file exists in the theme and load it
	 *
	 * @param string $element
	 *
	 * @return bool
	 * @static
	 */
	private function locateController($element)
	{
		// $controller_file_name = CHRONOSWP_ELEMENTS_PATH . '/' . $element . 'Controller.php';

		return true;

	}

	/**
	 * Set addon's directory
	 *
	 * @param null|string $directory
	 *
	 * @access public
	 */
	public function setDirectory($directory)
	{
		$this->directory = $directory;

		$urlPartial = str_replace('/app/public', '', $directory);
		$this->setCssDirectoryURL($urlPartial . '/assets/css/');
		$this->setScriptsDirectoryURL($urlPartial . '/assets/scripts/');
	}

	/**
	 * Get addon's directory
	 * @return null|string
	 * @access public
	 */
	public function getDirectory()
	{
		return $this->directory;
	}

	/**
	 * Check if the addon is active
	 *
	 * @return bool
	 * @access public
	 */
	public function isActive()
	{
		return $this->active;
	}

	/**
	 * Set the active flag of the addon
	 *
	 * @param bool $active
	 *
	 * @access public
	 */
	public function setActive($active)
	{
		$this->active = $active;
	}

	/**
	 * Declare available in this addon elements
	 *
	 * @param array $elements
	 *
	 * @access protected
	 */
	protected function setElements($elements)
	{
		$defaultElements = [
			'default' => 'Default',
			'empty'   => 'Empty'
		];
		$this->elements  = array_merge($defaultElements, $elements);
	}

	/**
	 * Get available in this addon elements
	 *
	 * @return array
	 */
	public function getElements()
	{
		return $this->elements;
	}

	/**
	 * Get CSS assets
	 *
	 * @return mixed
	 * @access public
	 */
	public function getCSSAssets()
	{
		return $this->cssAssets;
	}

	/**
	 * Add CSS asset
	 *
	 * @param string $reference
	 * @param array $path
	 * @param array $dependencies
	 * @param string $version
	 * @param string $media
	 *
	 * @access public
	 */
	public function registerCSSAsset($reference, $path, $dependencies = [], $version = null, $media = 'all')
	{
		$cssAsset = [
			'reference'    => $reference,
			'path'         => $path,
			'dependencies' => $dependencies,
			'version'      => $version,
			'media'        => $media,
		];

		$this->cssAssets[] = $cssAsset;
	}

	/**
	 * Enqueue CSS assets
	 *
	 * @param mixed $assets
	 *
	 * @access public
	 */
	public function enqueueCSSAssets()
	{
		if ( ! is_array($this->cssAssets)) {
			return;
		}

		array_map(function ($asset) {
			\wp_enqueue_style(
				$asset['reference'],
				$this->getCSSDirectoryURL() . $asset['path'],
				$asset['dependencies'],
				$asset['version'],
				$asset['media']
			);
		}, $this->cssAssets);
	}

	/**
	 * Enqueue script assets
	 *
	 * @param mixed $assets
	 *
	 * @access public
	 */
	public function enqueueScriptAssets()
	{
		if ( ! is_array($this->scriptAssets)) {
			return;
		}
		array_map(function ($asset) {
			\wp_enqueue_script(
				$asset['reference'],
				$this->getScriptsDirectoryURL() . $asset['path'],
				$asset['dependencies'],
				$asset['version'],
				$asset['in-footer']
			);
		}, $this->scriptAssets);
	}

	/**
	 * Get Script assets
	 *
	 * @return mixed
	 * @access public
	 *
	 */
	public function getScriptAssets()
	{
		return $this->scriptAssets;
	}

	/**
	 * Add Script asset
	 *
	 * @param string $reference
	 * @param array $path
	 * @param array $dependencies
	 * @param string $version
	 * @param string $inFooter
	 *
	 * @access public
	 */
	public function registerScriptAsset($reference, $path, $dependencies = [], $version = null, $inFooter = false)
	{
		$scriptAsset = [
			'reference'    => $reference,
			'path'         => $path,
			'dependencies' => $dependencies,
			'version'      => $version,
			'in-foooter'   => $inFooter,
		];

		$this->scriptAssets[] = $scriptAsset;
	}

	/**
	 * Set Script assets
	 *
	 * @param mixed $assets
	 *
	 * @access public
	 */
	public function setScriptAssets($assets)
	{
		$this->scriptAssets = $assets;
	}

	/**
	 * Get scripts directory URL
	 *
	 * @return mixed
	 * @access public
	 */
	public function getScriptsDirectoryURL()
	{
		return $this->scriptsDirectoryURL;
	}

	/**
	 * Set scripts directory URL
	 *
	 * @param mixed $scriptsDirectoryURL
	 *
	 * @access public
	 */
	public function setScriptsDirectoryURL($scriptsDirectoryURL)
	{
		$this->scriptsDirectoryURL = $scriptsDirectoryURL;
	}

	/**
	 * Get CSS directory URL
	 *
	 * @return null|string
	 * @access public
	 */
	public function getCssDirectoryURL()
	{
		return $this->cssDirectoryURL;
	}

	/**
	 * Set CSS directory URL
	 *
	 * @param null|string $cssDirectoryURL
	 *
	 * @access public
	 */
	public function setCssDirectoryURL($cssDirectoryURL)
	{
		$this->cssDirectoryURL = $cssDirectoryURL;
	}

	/**
	 * Set addon's reference
	 *
	 * @param string $reference
	 *
	 * @access public
	 */
	public function setReference($reference)
	{
		$this->reference = $reference;
	}

	/**
	 * Set addon's name
	 *
	 * @param string $name
	 *
	 * @access public
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * Set addon's description
	 *
	 * @param string $description
	 *
	 * @access public
	 */
	public function setDescription($description)
	{
		$this->description = $description;
	}

	/**
	 * Set addon's current version
	 *
	 * @param string $currentVersion
	 *
	 * @access public
	 */
	public function setCurrentVersion($currentVersion)
	{
		$this->currentVersion = $currentVersion;
	}

	/**
	 * Validate the addon class
	 *
	 * @return mixed
	 * @access protected
	 */
	protected function validate()
	{
		$validationResult = [];

		$validationResult['valid']  = true;
		$validationResult['errors'] = [];

		$requiredProperties = [
			'reference',
			'name',
			'currentVersion',
			'description',
		];

		foreach ($requiredProperties as $property) {
			if (empty($this->$property)) {
				$error = [];
				$error['description'] = __('Value of required is empty', 'chwp');
				$error['property'] = $property;
				$validationResult['errors'][] = $error;
				$validationResult['valid'] = false;
			}
		}

		return $validationResult;

	}

	/**
	 * Set view filename suffix
	 *
	 * @param string $viewFilenameSuffix
	 * @access public
	 */
	public function setViewFilenameSuffix($viewFilenameSuffix)
	{
		$this->viewFilenameSuffix = $viewFilenameSuffix;
	}

}
