<?php

namespace ChronosWP;

class Core {
    /**
     * Contains list of addons
     *
     * @var array
     * @access private
     */
    private $_addons = array();

    public function __construct()
    {
    	// Validate installation
	    $this->piklistEnabled();
        // Parse all existing plugins
        $this->parseAddons();
    }

    protected function piklistEnabled()
    {

    }

    /**
     * Get list of all available addons and get them ready use
     *
     * @access private
     */
    private function parseAddons() {

    	$locationsToParse = [
		    CHRONOSWP_ADDONS_PATH,
		    CHRONOSWP_THEME_SRC_PATH
	    ];

	    $addonFiles = array();

    	foreach($locationsToParse as $location) {

		    $addonsDirs = @ opendir( $location );

		    // Scan addons directory
		    if ( $addonsDirs ) {

			    while (($file = readdir( $addonsDirs ) ) !== false ) {

				    if (is_dir($location . '/' . $file)) {

					    $addons = @opendir($location . '/' . $file);

					    while (($file_nested = readdir( $addons ) ) !== false ) {
						    if ( substr($file_nested, 0, 1) == '.' ) {
							    continue;
						    }
						    if (substr($file_nested, -4) == '.php' && strpos($file_nested, 'Addon.php') !== false) {
							    $addonFiles[] = $file . '\\' . $file_nested;
						    }
					    }

					    closedir( $addons );

				    }
			    }

			    closedir( $addonsDirs );

		    }

	    }

        $classes = [];
        $namespacePrefix = '\\ChronosWP\\Addon\\';

        // Generate list of addons
        foreach ( $addonFiles as $addonFile ) {

            $justClassName = str_replace( '.php', '', $addonFile );
            $className = $namespacePrefix . $justClassName;

            $addonClass = new $className();
            $classes[ $justClassName ] = $addonClass;

        }

        // Sort addons by name alphabetically
        ksort( $classes );

	    $this->_addons = $classes;

        return $classes;

    }

    /**
     * Get addon by name
     *
     * @param string $name
     * @return mixed
     */
    public function getAddon( $name ) {

        if ( isset( $this->_addons[ $name ] ) ) {
            return $this->_addons[ $name ];
        }

        return false;

    }

    /**
     * Get all addons
     *
     * @return array
     * @access public
     */
    public function getAllAddons() {
        return $this->_addons;
    }
}
