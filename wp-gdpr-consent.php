<?php
/**
 * Plugin Name: GDPR Consent
 * Plugin URI: https://github.com/firstandthird/wp-gdpr-consent
 * Description: A consent banner that blocks GA and FB pixels until the user has consented
 * Version: 0.0.2
 * Author: First and Third
 *
 * @package gdprconsent
 */

require_once(__DIR__ . '/inc/settings-page.php');
require_once(__DIR__ . '/inc/banner.php');

add_action('wp_enqueue_scripts', function() {
  wp_enqueue_style('gdprconsent', plugin_dir_url(__FILE__) . 'assets/gdprconsent.css');
  wp_enqueue_script('gdprconsent', plugin_dir_url(__FILE__) . 'assets/gdprconsent.js', array(), '1.0.0', false);
});
