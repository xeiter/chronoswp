<?php

namespace ChronosWP\Base;

class View
{
	/**
	 * Location for the elements in the theme
	 */
	const ELEMENT_THEME_DIRECTORY = 'az-elements';
	const CHRONOSWP_TEMPLATE_DIR = 'templates/';

	/**
	 * Suffix for the view file name
	 */
	const ELEMENT_VIEW_FILE_NAME_SUFFIX = '-view.php';

	/**
	 * Template file
	 * @var null|string
	 */
	private $template = null;

	/**
	 * Element reference
	 * @var null|string
	 */
	private $element = null;

	/**
	 * Variables registered for the view
	 * @var array
	 */
	private $variables = array();

	/**
	 * Show hints or not
	 * @var bool
	 */
	private $showHints = false;

	/**
	 * View directory
	 * @var string
	 * @access private
	 */
	private $directory = null;

	/**
	 * Core view directory
	 * @var string
	 * @access private
	 */
	private $coreDirectory = null;

	/**
	 * View arguments
	 *
	 * @var array
	 * @access private
	 */
	private $arguments = [];

	/**
	 * View file name suffix
	 *
	 * @var string
	 * @access private
	 */
	private $filenameSuffix = '.php';

	/**
	 * View constructor.
	 *
	 * @param string $template
	 * @param array $arguments
	 * @param string $filenameSuffix
	 * @access public
	 */
	public function __construct($template, $arguments = [], $filenameSuffix = '.php')
	{
		// Set default template
		$this->template = self::CHRONOSWP_TEMPLATE_DIR . $this->camelCaseToDashed($template);

		// Save the element
		$this->element = $template;

		// Save the arguments
		$this->arguments = $arguments;

		// Save filename suffix
		$this->filenameSuffix = $filenameSuffix;
	}

	/**
	 * Set template for this view
	 *
	 * @param string $template
	 */
	public function setTemplate($template)
	{
		$this->template = $template;
	}

	/**
	 * Get template for this view
	 */
	public function getTemplate()
	{
		return $this->template;
	}

	/**
	 * Show hints in the view template
	 *
	 * @param  bool $value
	 */
	public function showHints($value)
	{
		$this->showHints = $value;
	}

	/**
	 * Render variables hint
	 *
	 * @return string
	 */
	protected function renderVariablesHint()
	{

		if ($this->showHints) {

			$variablesHtml = '';

			foreach ($this->variables as $variableName => $variableOptions) {
				$variablesHtml .= '<tr>';
				$variablesHtml .= '<td style="padding-left: 15px;"><var>$' . $variableName . '</var></td>';
				$variablesHtml .= '<td><pre>' . print_r($variableOptions['value'], true) . '</pre></td>';
				$variablesHtml .= '<td>' . $variableOptions['description'] . '</td>';
				$variablesHtml .= '</tr>';
			}

			foreach ($this->arguments as $variableName => $variableValue) {
				$variablesHtml .= '<tr>';
				$variablesHtml .= '<td style="padding-left: 15px;"><var>$' . $variableName . '</var></td>';
				$variablesHtml .= '<td>' . print_r($variableValue, true) . '</td>';
				$variablesHtml .= '<td>' . $variableOptions['description'] . '</td>';
				$variablesHtml .= '</tr>';
			}

			$html = <<<MULTI

<style type="text/css">
    .view__hints table { font-size: 12px; }
</style>

<div class="view__hints panel panel-default">

    <div class="panel-heading">
            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                Available variables <i class="glyphicon glyphicon-zoom-in"></i>
            </a>
    </div>

    <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
        <div class="panel-body">
            <table class="table table-hover">
                <tr>
                    <th style="padding-left: 15px;"">Variable</th>
                    <th>Value</th>
                    <th>Description</th>
                </tr>
                {$variablesHtml}
            </table>
        </div>
    </div>

</div>

MULTI;
			return $html;
		}
	}

	/**
	 * Get filename of the view
	 *
	 * @return string
	 * @access public
	 */
	public function getViewFilename($element, $base = true)
	{
		$element   = str_replace('default', '', $element);
		$directory = $base ? $this->coreDirectory . '/Addon/' . $this->element : $this->directory;

		if (empty($element)) {
			$viewFileName = $directory . '/' . $this->template . $this->filenameSuffix;
		} else {
			$viewFileName = $directory . '/' . $this->template . '-' . $element . $this->filenameSuffix;
		}

		return $viewFileName;
	}

	/**
	 * Render view template
	 *
	 * @param string $addon
	 * @param string $classes
	 * @param string $container
	 * @param array $args
	 *
	 * @return string
	 */
	public function render($addon, $element)
	{
		if ($this->template && ! preg_match('/-empty$/', $this->template)) {

			$viewFileName = $this->getViewFilename($element, false);
			$viewFileNameCore = $this->getViewFilename($element);

			if (file_exists($viewFileName)) {
				$viewFileName = $viewFileName;
			} elseif (file_exists($viewFileNameCore)) {
				$viewFileName = $viewFileNameCore;
			} else {
				$viewFileName = false;
			}

			if ($viewFileName) {

				$data = [];
				ob_start();

				echo $this->renderVariablesHint();

				foreach ($this->variables as $variableName => $variableOptions) {
					${$variableName} = $variableOptions['value'];
					$data[$variableName] = $variableOptions['value'];
				}

				/*foreach ( $this->arguments as $variable_name => $variable_value ) {
					${$variable_name} = $variable_value;
				}*/

				// $class = 'element element__' . $addon . ' element-view__' . $this->_template . ' ' . $classes;
				$class = '';

				/*if ( ! is_null( $container ) ) {
					echo '<' . $container . ' class="' . $class . ' ' . $bottom_margin . '">';
				}*/

				if (function_exists('\App\sage')) {
					echo \App\template($viewFileName, $data);
				} else {
					require $viewFileName;
				}

				/*if ( ! is_null( $container ) ) {
					echo '</' . $container . '>';
				}*/

				$templateContent = str_replace('``', '"', ob_get_contents());

				$templateContent = '<!------- Element start: ' . $addon . ' -------->' . "\n" . $templateContent;

				ob_end_clean();

				return $templateContent;

			} else {
				$this->processError('View template file was not found: ' . $viewFileName);
			}

		}

		return false;
	}

	/**
	 * Set view variable
	 *
	 * @param string $name
	 * @param mixed $value
	 * @param string $description
	 */
	public function setVariable($name, $value, $description = null)
	{

		$this->variables[$name] = ['value' => $value, 'description' => $description];

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
		return $this->variables[$name];
	}

	/**
	 * Process an error
	 *
	 * @param string $message
	 *
	 * @throws Exception
	 * @access private
	 */
	public function processError($message)
	{
		throw new \ChronosWP\Exception($message);
	}

	/**
	 * Convert class to string
	 *
	 * @return string
	 * @access public
	 */
	public function __toString()
	{
		return $this->render();
	}

	/**
	 * Get variables
	 *
	 * @param mixed $value
	 */
	public function getVariables()
	{
		return $this->variables;
	}

	/**
	 * Convert camel-cased string to dashed string
	 *
	 * @param string $subject
	 *
	 * @return string
	 * @access protected
	 */
	protected function camelCaseToDashed($subject)
	{
		return strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $subject));
	}

	/**
	 * Get view directory
	 *
	 * @return string
	 * @access public
	 */
	public function getDirectory()
	{
		return $this->directory;
	}

	/**
	 * Set view directory
	 *
	 * @param string $directory
	 *
	 * @access public
	 */
	public function setDirectory($directory)
	{
		$this->directory = $directory;
	}

	/**
	 * Get core view directory
	 *
	 * @return string
	 * @access public
	 */
	public function getCoreDirectory()
	{
		return $this->coreDirectory;
	}

	/**
	 * Set core view directory
	 *
	 * @param string $directory
	 *
	 * @access public
	 */
	public function setCoreDirectory($directory)
	{
		$this->coreDirectory = $directory;
	}

	/**
	 * Set filename suffix
	 *
	 * @param string $filenameSuffix
	 * @access public
	 */
	public function setFilenameSuffix($filenameSuffix)
	{
		$this->filenameSuffix = $filenameSuffix;
	}

}
