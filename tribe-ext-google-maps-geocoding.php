<?php
/**
 * Plugin Name:       The Events Calendar Extension: Google Maps Geocoding
 * Plugin URI:        https://theeventscalendar.com/extensions/google-maps-geocoding/
 * GitHub Plugin URI: https://github.com/mt-support/tribe-ext-google-maps-geocoding/
 * Description:       When doing imports, use your local Google Maps API key for geocoding of events and venues.
 * Version:           1.0.0
 * Extension Class:   Tribe__Extension__Google_Maps_Geocoding
 * Author:            Modern Tribe, Inc.
 * Author URI:        http://m.tri.be/1971
 * License:           GPL version 3 or any later version
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
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

// Do not load unless Tribe Common is fully loaded and our class does not yet exist.
if (
	! class_exists( 'Tribe__Extension' )
	|| class_exists( 'Tribe__Extension__Google_Maps_Geocoding' )
) {
	return;
}

class Tribe__Extension__Google_Maps_Geocoding extends Tribe__Extension {
	/**
	 * Extension initialization and hooks.
	 */
	public function init() {
		// Don't resolve geolocation through EA Service at all.
		add_filter( 'tribe_aggregator_resolve_geolocation', '__return_false' );
	}
}
