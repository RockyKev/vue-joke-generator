<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              #
 * @since             1.0.0
 * @package           Vue Joke Generator
 *
 * @wordpress-plugin
 * Plugin Name:       Vue Joke Generator
 * Plugin URI:        #
 * Description:       This calls the Joke API and has a settings page. 
 * Version:           1.0.0
 * Author:            Rocky
 * Author URI:        #
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       joke-generator-vue
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

/**
 * Currently plugin version.
 */
define('AUTHOR_GENERATOR_VUE', '1.0.0');

// Invoke Shortcode
function vue_google_maps()
{

	// let flags = "?blacklistFlags=nsfw";
	// let jokeType = "&type=single";
	// let jokeRange = "&idRange=50-100";

	$phpData = [
		'category' => [
			'Programming' => true,
			'Miscellaneous' => false,
			'Pun' => false,
			'Spooky' => true,
			'Christmas' => false
		],
		'blacklist' => [
			'nsfw' => true,
			'religious' => false,
			'political' => true,
			'racist' => false,
			'sexist' => false,
			'explicit' => false
		],
		'jokeType' => [
			'single' => true,
			'twopart' => true
		],
		'range' => [
			'from' => 0,
			'to' => 20
		],
	];

	// get Vue libs 
	wp_register_script('vue-app-vendors',  plugins_url('app/dist/js/chunk-vendors.js', __FILE__), array(), '1.0.0');
	wp_register_script('my-vue-app', plugins_url('app/dist/js/app.js', __FILE__), array('vue-app-vendors'), '1.0.0');

	// pass php data
	wp_localize_script('my-vue-app', 'phpData', $phpData);

	// pass the scripts to WP
	wp_enqueue_script('vue-app-vendors');
	wp_enqueue_script('my-vue-app');
	wp_enqueue_style('my-vue-app',  plugins_url('app/dist/css/app.css', __FILE__),   array(),  '1.0.0');

	return '<div id="app"></div>';
}


// include the options page -- v1
// include 'wp-options-tutorial.php';

// include the options page -- v2
// include 'wp-twilio-tutorial.php';
// $optionsInstance = new Sendex();
// // add the new settings
// add_action("admin_menu", [$optionsInstance , "addSendexAdminOption"]);

// // save and update settings
// add_action("admin_init", [$optionsInstance , "sendexAdminSettingsSave"]);

include 'vue-app-admin-settings-page.php';
$settingsInstance = new SettingsForVuePlugin();
add_action("admin_menu", [$settingsInstance, "addVueJokesAdminOptions"]); // hook into to WP
add_action("admin_init", [$settingsInstance, "saveVueJokesAdminOptions"]); // hook into saving values


// TODO: Change this shortcode call sign
// https://presscoders.com/wordpress-settings-api-explained/

add_shortcode('generate-maps-vue', 'vue_google_maps');
// add_action('init', 'initilize_plugin');
