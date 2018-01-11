<?php

namespace ChronosWP\Base;

class Controller
{
	/**
	 * Reference of the element
	 *
	 * @var null|string
	 */
	private $addon = null;

	/**
	 * Controllers arguments
	 *
	 * @var null|string
	 */
	protected $arguments = null;

	/**
	 * Controller's view object
	 *
	 * @var null|View
	 */
	public $view = null;

	/**
	 * Specifies whether the view should be rendered
	 *
	 * @var bool
	 */
	private $renderView = true;

	/**
	 * ID of the current post
	 *
	 * @var int
	 * @access private
	 */
	private $postId = null;

	/**
	 * Directory of the controller
	 * @var null
	 * @access protected
	 */
	private $directory = null;

	/**
	 * Core directory of the controller
	 * @var null
	 * @access protected
	 */
	private $coreDirectory = null;

	/**
	 * View file name suffix
	 *
	 * @var string
	 * @access private
	 */
	private $viewFilenameSuffix = '.php';

	/**
	 * Controller constructor.
	 */
	public function __construct($addon, $element)
	{
		$this->addon = $addon;
		$this->baseDirectory = dirname(__DIR__);

		$runMethod = 'run' . str_replace(' ', '', ucwords(str_replace('-', ' ', $element)));
		$runMethod = str_replace('Default', '', $runMethod);

		// Run controller method if it exists
		if (method_exists(get_called_class(), $runMethod)) {
			self::run($element);
			$this->$runMethod($element);
		}
	}

	/**
	 * Set the template for the view
	 *
	 * @param string $template
	 */
	public function setViewTemplate($template)
	{
		$this->view->setTemplate($template);
	}

	/**
	 * Run the controller
	 */
	public function run($element)
	{
		$this->view = new \ChronosWP\Base\View($this->addon, $element);
	}

	/**
	 * Initiate render of the view
	 *
	 * @param string $classes
	 * @param string $container
	 *
	 * @return string
	 */
	public function renderView($element)
	{
		if ($this->view && $this->renderView) {
			$this->view->setFilenameSuffix($this->viewFilenameSuffix);
			return $this->view->render($this->addon, $element);
		}
		return null;
	}

	/**
	 * Set variable
	 *
	 * @param string $name
	 * @param mixed $value
	 * @param string $description
	 */
	public function setVariable($name, $value, $description = '')
	{
		$this->view->setVariable($name, $value, $description);
	}

	/**
	 * Get view variable
	 *
	 * @param string $name
	 *
	 * @return mixed
	 */
	public function getVariable($name)
	{
		return $this->view->getVariable($name)['value'];
	}

	/**
	 * Get variables
	 *
	 * @param mixed $value
	 */
	public function getVariables()
	{
		return $this->view->getVariables();
	}

	/**
	 * Show/Hide hits
	 *
	 * @param mixed $value
	 */
	public function showHints($value = true)
	{
		return $this->view->showHints($value);
	}

	/**
	 * Set flag that instructs to render (or not) the view
	 *
	 * @param bool $value
	 */
	public function setRenderView($value)
	{
		$this->renderView = $value;
	}

	/**
	 * Get the current post's ID
	 *
	 * @access public
	 * @return int
	 */
	public function getPostID()
	{
		return $this->postId;
	}

	/**
	 * Get the value of the provided argument.
	 * Use default value if the arguments don't have one with provided name
	 *
	 * @param string $name
	 * @param mixed $defaultValue
	 *
	 * @return mixed
	 * @access public
	 */
	public function getArgumentValue($name, $defaultValue = null)
	{
		return isset($this->arguments[$name]) ? $this->arguments[$name] : $defaultValue;
	}

	/**
	 * Get directory
	 *
	 * @return null
	 * @access public
	 */
	public function getDirectory()
	{
		return $this->directory;
	}

	/**
	 * Set directory
	 *
	 * @param null $directory
	 *
	 * @access public
	 */
	public function setDirectory($directory)
	{
		$this->directory = $directory;
		if ($this->view) {
			$this->view->setDirectory($directory);
			$this->view->setCoreDirectory($this->baseDirectory);
		}
	}

	/**
	 * Get core directory
	 *
	 * @return null
	 * @access public
	 */
	public function getCoreDirectory()
	{
		return $this->coreDirectory;
	}

	/**
	 * Set core directory
	 *
	 * @param null $coreDirectory
	 *
	 * @access public
	 */
	public function setCoreDirectory($coreDirectory)
	{
		$this->coreDirectory = $coreDirectory;
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
