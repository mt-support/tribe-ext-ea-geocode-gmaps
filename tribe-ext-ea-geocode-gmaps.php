<?php
/**
 * Plugin Name:       Event Aggregator Extension: Google Maps Geocoding
 * Plugin URI:        https://theeventscalendar.com/extensions/ea-google-maps-geocoding/
 * GitHub Plugin URI: https://github.com/mt-support/tribe-ext-ea-geocode-gmaps/
 * Description:       When doing The Events Calendar's Event Aggregator imports, use your local Google Maps API key for geocoding of events and venues.
 * Version:           1.0.0
 * Extension Class:   Tribe\Extensions\EA_Geocode_GMaps\Main
 * Author:            Modern Tribe, Inc.
 * Author URI:        http://m.tri.be/1971
 * License:           GPL version 3 or any later version
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       tribe-ext-ea-geocode-gmaps
 *
 *     This plugin is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     any later version.
 *
 *     This plugin is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *     GNU General Public License for more details.
 */

namespace Tribe\Extensions\EA_Geocode_GMaps;

use Tribe__Extension;
use Tribe__Settings;

/**
 * Define Constants
 */

if ( ! defined( __NAMESPACE__ . '\NS' ) ) {
	define( __NAMESPACE__ . '\NS', __NAMESPACE__ . '\\' );
}

if ( ! defined( NS . 'PLUGIN_TEXT_DOMAIN' ) ) {
	// `Tribe\Extensions\EA_Geocode_GMaps\PLUGIN_TEXT_DOMAIN` is defined
	define( NS . 'PLUGIN_TEXT_DOMAIN', 'tribe-ext-ea-geocode-gmaps' );
}

// Do not load unless Tribe Common is fully loaded and our class does not yet exist.
if (
	class_exists( 'Tribe__Extension' )
	&& ! class_exists( NS . 'Main' )
) {
	/**
	 * Extension main class, class begins loading on init() function.
	 */
	class Main extends Tribe__Extension {

		/**
		 * Setup the Extension's properties.
		 *
		 * This always executes even if the required plugins are not present.
		 */
		public function construct() {
			// Version 4.6.24 is when tribe_is_using_basic_gmaps_api() was added.
			// Version 4.6.25 is when the `tribe_aggregator_resolve_geolocation` filter was added.
			$this->add_required_plugin( 'Tribe__Events__Main', '4.6.25' );
		}

		/**
		 * Extension initialization and hooks.
		 */
		public function init() {
			// Load plugin textdomain
			load_plugin_textdomain( PLUGIN_TEXT_DOMAIN, false, basename( dirname( __FILE__ ) ) . '/languages/' );

			if ( ! $this->php_version_check() ) {
				return;
			}

			if ( tribe_is_using_basic_gmaps_api() ) {
				$this->admin_notice_non_custom_gmaps_api_key();
			} else {
				// Do not resolve geolocation through EA Service at all.
				add_filter( 'tribe_aggregator_resolve_geolocation', '__return_false' );
			}
		}

		/**
		 * Check if we have a sufficient version of PHP. Admin notice if we don't and user should see it.
		 *
		 * @link https://theeventscalendar.com/knowledgebase/php-version-requirement-changes/ All extensions require PHP 5.6+.
		 *
		 * @return bool
		 */
		private function php_version_check() {
			$php_required_version = '5.6';

			if ( version_compare( PHP_VERSION, $php_required_version, '<' ) ) {
				if (
					is_admin()
					&& current_user_can( 'activate_plugins' )
				) {
					$message = '<p>';

					$message .= sprintf( __( '%s requires PHP version %s or newer to work. Please contact your website host and inquire about updating PHP.', PLUGIN_TEXT_DOMAIN ), $this->get_name(), $php_required_version );

					$message .= sprintf( ' <a href="%1$s">%1$s</a>', 'https://wordpress.org/about/requirements/' );

					$message .= '</p>';

					tribe_notice( PLUGIN_TEXT_DOMAIN . '-php-version', $message, [ 'type' => 'error' ] );
				}

				return false;
			}

			return true;
		}

		/**
		 * Display admin notice if a custom Google Maps API key is not in use.
		 */
		private function admin_notice_non_custom_gmaps_api_key() {
			if (
				is_admin()
				&& current_user_can( 'manage_options' )
			) {
				$api_settings_tab_link = __( 'wp-admin > Events > Settings > APIs', PLUGIN_TEXT_DOMAIN );
				$api_settings_tab_link = sprintf(
					'<a href="%s">%s</a>',
					Tribe__Settings::instance()->get_url( [ 'tab' => 'addons' ] ) . '#tribe-field-google_maps_js_api_key',
					$api_settings_tab_link
				);

				$message = '<p>';

				$message .= sprintf( __( '%s only works when you are using your own custom Google Maps API key. Please enter it at %s, or deactivate this extension plugin to use the default geocoding solution, which is likely less accurate.', PLUGIN_TEXT_DOMAIN ), $this->get_name(), $api_settings_tab_link );

				$message .= '</p>';

				tribe_notice( PLUGIN_TEXT_DOMAIN . '-non-custom-gmaps-api-key', $message, [ 'type' => 'error' ] );
			}
		}
	} // end class
} // end if class_exists check