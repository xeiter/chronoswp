<?php

namespace ChronosWP;

class Admin {

    public function __construct(){

        $this->registerPluginAdmin();

        /*
        $json_settings = <<<MULTI
{
	"paths": {
	    "addons": "Addon",
	    "interfaces": "Interfaces",
	    "dependency": "Dependency"
	}
}
MULTI;

        $this->_process_settings( $json_settings );
        */

    }

    /**
     * Process settings
     *
     * @param string $jsonSettings
     *
     * @throws Exception
     */
    private function processSettings($jsonSettings ) {

        $settings = json_decode($jsonSettings );

        if ( ! $settings ) {
            throw new Exception( __( 'Failed to parse the settings file', 'chronoswp' ) );
        }

        foreach ( $settings as $key => $options ) {

            switch ( $key ) {

                case 'paths':

                    foreach ( $options as $option => $value ) {
                    }

                    break;

            }

        }

    }

    private function getSetting( $section, $option ) {

    }

    public function getAddonsPath() {

        return $this->getSetting( $section = 'paths', $option = 'addons' );

    }

    /**
     * Register plugin's setting with the WordPress installation
     */
    private function registerPluginAdmin() {

        add_filter( 'piklist_admin_pages', [ $this, 'registerPluginGeneralAdminSettings' ] );

    }

    /**
     * Register plugin's general admin with the WordPress installation
     *
     * @param array $pages
     * @return array
     */
    public function registerPluginGeneralAdminSettings( $pages ) {

        // Register top level admin settings page
        $pages[] = array(

            'page_title' => __('Dashboard'),
            'menu_title' => __('ChronosWP', 'chwp'),
            'capability' => 'manage_options',
            'menu_slug' => 'chronoswp',
            'setting' => 'chronoswp',
            'menu_icon' => 'dashicons-admin-network',
            'page_icon' => 'dashicons-admin-network',
            'save_text' => 'Save Settings',

        );

        $pages[] = array(

            'page_title' => __('General Settings'),
            'menu_title' => __('General Settings', 'chronoswp'),
            'sub_menu' => 'chronoswp',
            'capability' => 'manage_options',
            'menu_slug' => 'chronoswp-general-settings',
            'setting' => 'chronoswp-general-settings',
            'menu_icon' => plugins_url('piklist/parts/img/piklist-icon.png'),
            'page_icon' => plugins_url('piklist/parts/img/piklist-page-icon-32.png'),
            'save_text' => 'Save Settings',

        );

        $pages[] = array(

            'page_title' => __('Addons'),
            'menu_title' => __('Addons', 'chronoswp'),
            'sub_menu' => 'chronoswp',
            'capability' => 'manage_options',
            'menu_slug' => 'chronoswp-addons',
            'setting' => 'chronoswp-addons',
            'menu_icon' => plugins_url('piklist/parts/img/piklist-icon.png'),
            'page_icon' => plugins_url('piklist/parts/img/piklist-page-icon-32.png'),
            'save_text' => 'Save Settings',

        );

        return $pages;

    }

}