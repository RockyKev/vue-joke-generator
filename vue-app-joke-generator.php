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

		// this will pass as a flatten array
	// {
	// 	"blacklist-nsfw": "",
	// 	"blacklist-political": "",
	// 	"blacklist-racist": "on",
	// 	"blacklist-religious": "",
	// 	"blacklist-sexist": "",
	// 	"category-christmas": "on",
	// 	"category-miscellaneous": "on",
	// 	"category-programming": "on",
	// 	"category-pun": "on",
	// 	"category-spooky": "on",
	// 	"joke-range_1": "1",
	// 	"joke-range_2": "10",
	// 	"joketype": "any"
	// }


	// $phpData = [
	// 	'category' => [
	// 		'Programming' => true,
	// 		'Miscellaneous' => false,
	// 		'Pun' => false,
	// 		'Spooky' => true,
	// 		'Christmas' => false
	// 	],
	// 	'blacklist' => [
	// 		'nsfw' => true,
	// 		'religious' => false,
	// 		'political' => true,
	// 		'racist' => false,
	// 		'sexist' => false,
	// 		'explicit' => false
	// 	],
	// 	'jokeType' => [
	// 		'single' => true,
	// 		'twopart' => true
	// 	],
	// 	'range' => [
	// 		'from' => 0,
	// 		'to' => 20
	// 	],
	// ];

	// shape the array 
	$options_data = get_option("vue-jokes");

	// reshape the array into it's own pieces
	$blacklist_array = get_values_with_key($options_data, "blacklist-");
	$category_array = get_values_with_key($options_data, "category-");

	// clean up
	$blacklist_array = replace_key_names($blacklist_array, "blacklist-", "");
	$category_array = replace_key_names($category_array, "category-", "");

	$php_data = [
		'category' => $category_array,
		'blacklist' => $blacklist_array,
		'jokeType' => $options_data['joketype'],
		'range' => [
			'from' =>  $options_data['joke-range1'],
			'to' => $options_data['joke-range2']
		],
	];

	print_r($php_data);

	// get Vue libs 
	wp_register_script('vue-app-vendors',  plugins_url('app/dist/js/chunk-vendors.js', __FILE__), array(), '1.0.0');
	wp_register_script('my-vue-app', plugins_url('app/dist/js/app.js', __FILE__), array('vue-app-vendors'), '1.0.0');

	// pass php data
	// wp_localize_script('my-vue-app', 'phpData', $phpData);
	wp_localize_script('my-vue-app', 'phpData', $php_data);	

	// pass the scripts to WP
	wp_enqueue_script('vue-app-vendors');
	wp_enqueue_script('my-vue-app');
	wp_enqueue_style('my-vue-app',  plugins_url('app/dist/css/app.css', __FILE__),   array(),  '1.0.0');

	return '<div id="app"></div>';
}

function replace_key_names($array, $search_string, $replace_with) {
    $matches = [];

    foreach ($array as $key => $value) {
        if (strstr($key, $search_string)) {
			$newKey = str_replace($search_string, "", $key);
            $matches[$newKey] = $value;
        }
    }

    return $matches;	
}

function get_values_with_key($array, $search_string) {
    $matches = [];

    foreach ($array as $key => $value) {
        if (strstr($key, $search_string)) {
            $matches[$key] = $value;
        }
    }

    return $matches;
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
