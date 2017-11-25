<?php

/**
 * Render template
 *
 * @param string $addonName
 * @return string
 */
function chwp_render($addonName, $element = 'default')
{
	// Build namespace
	$namespace = '\\ChronosWP\\Addon\\' . $addonName . '\\';

	// Build class name
	$addonClassName = $namespace . $addonName . 'Addon';

	if (class_exists($addonClassName)) {
		// Render template
		$addon = new $addonClassName();
		$addon->setActive(true);
		return $addon->render($element);
	} else {
		// Addon class not found - return empty string
		return '';
	}

}