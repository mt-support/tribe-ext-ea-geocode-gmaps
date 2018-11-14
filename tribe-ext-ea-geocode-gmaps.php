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
			$this->add_required_plugin( 'Tribe__Events__Main', '4.6.25' );
		}

		/**
		 * Extension initialization and hooks.
		 */
		public function init() {
			// Load plugin textdomain
			load_plugin_textdomain( PLUGIN_TEXT_DOMAIN, false, basename( dirname( __FILE__ ) ) . '/languages/' );

			// Don't resolve geolocation through EA Service at all.
			add_filter( 'tribe_aggregator_resolve_geolocation', '__return_false' );
		}

	} // end class
} // end if class_exists check