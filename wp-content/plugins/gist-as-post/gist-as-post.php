<?php
/**
 * Plugin Name: Gist as Post
 * Plugin URI: http://www.ianmoffitt.co/gist-as-post/
 * Description: Adds "Gist" post type, and imports all gists from your github as drafts (or published).
 * Version: 0.5.0
 * Author: Ian Moffitt
 * Author URI: http://www.ianmoffitt.co/
 * License: MIT
 */

/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-15 Ian Moffitt
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

defined('ABSPATH') or die("No script kiddies please!");

if(!class_exists('Gist_Post'))
{
    class Gist_Post
    {
        /**
         * Construct the plugin object
         */
        public function __construct()
        {
            // register actions
            add_action('admin_init', array(&$this, 'admin_init'));
			add_action('admin_menu', array(&$this, 'add_menu'));
        } // END public function __construct

        /**
         * Activate the plugin
         */
        public static function activate()
        {
            // Do nothing
        } // END public static function activate

        /**
         * Deactivate the plugin
         */
        public static function deactivate()
        {
            // Do nothing
        } // END public static function deactivate

        /**
         * hook into WP's admin_init action hook
         */
        public function admin_init()
        {
            // Set up the settings for this plugin
            $this->init_settings();
            // Possibly do additional admin_init tasks
        } // END public static function activate

        /**
         * Initialize some custom settings
         */
        public function init_settings()
        {
            // register the settings for this plugin
            register_setting('gist-as-post-group', 'setting_a');
            register_setting('gist-as-post-group', 'setting_b');
        } // END public function init_custom_settings()

        /**
         * add a menu
         */
        public function add_menu()
        {
            add_options_page('Gist as Post Settings', 'Gist as Post', 'manage_options', 'gist-as-post', array(&$this, 'plugin_settings_page'));
        } // END public function add_menu()

        /**
         * Menu Callback
         */
        public function plugin_settings_page()
        {
            if(!current_user_can('manage_options'))
            {
                wp_die(__('You do not have sufficient permissions to access this page.'));
            }

            // Render the settings template
            include(sprintf("%s/templates/settings.php", dirname(__FILE__)));
        } // END public function plugin_settings_page()

    } // END class Gist_Post
} // END if(!class_exists('Gist_Post'))

if(class_exists('Gist_Post'))
{
    // Installation and uninstallation hooks
    register_activation_hook(__FILE__, array('Gist_Post', 'activate'));
    register_deactivation_hook(__FILE__, array('Gist_Post', 'deactivate'));

    // instantiate the plugin class
    $gist_post = new Gist_Post();

    // Add a link to the settings page onto the plugin page
    if(isset($gist_post))
    {
        // Add the settings link to the plugins page
        function plugin_settings_link($links)
        {
            $settings_link = '<a href="options-general.php?page=gist_post">Settings</a>';
            array_unshift($links, $settings_link);
            return $links;
        }

        $plugin = plugin_basename(__FILE__);
        add_filter("plugin_action_links_$plugin", 'plugin_settings_link');
    }
}
