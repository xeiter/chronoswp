<?php

/**
 * Render template
 *
 * @param string $addonName
 * @param string $element
 * @param string $viewTemplateSuffix The suffix of the filename of the template (i.e. .php or .blade.php)
 * @return string
 */
function chwp_render($addonName, $element = 'default', $viewTemplateSuffix = '.php')
{
	// Build namespace
	$namespace = '\\ChronosWP\\Addon\\' . $addonName . '\\';

	// Build class name
	$addonClassName = $namespace . $addonName . 'Addon';

	if (class_exists($addonClassName)) {
		// Render template
		$addon = new $addonClassName();
		$addon->setActive(true);
		$addon->setViewFilenameSuffix($viewTemplateSuffix);
		return $addon->render($element);
	} else {
		// Addon class not found - return empty string
		return '';
	}

}