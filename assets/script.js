/*
 * Webinista WreadIt
 * script.js: JavaScript for the settings page.
 *
 * Note that this package includes libraries distributed with Apache 2.0 and MIT licenses.
 *
 * Copyright (C) 2025  Tiffany B. Brown, Webinista Inc.
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

'use strict';

class WebinistaWreadIt {
	static is_valid_s3_bucket_name( bucket_name ) {
		if ( ! bucket_name ) {
			return false;
		}

		const regex = new RegExp(
			/^(?!xn--|sthree-|amzn-s3-demo-|.*-s3alias$|.*--ol-s3$|.*--x-s3$)[a-z0-9][a-z0-9-]{1,61}[a-z0-9]$/
		);

		return regex.test( bucket_name );
	}

	static is_valid_domain( domain_name ) {
		if ( ! domain_name ) {
			return false;
		}

		const regex = new RegExp(
			/(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z0-9][a-z0-9-]{0,61}[a-z0-9]/
		);

		return regex.test( domain_name );
	}

	static disable_standard( regions_menu ) {
		var opts = Array.from( regions_menu.querySelectorAll( 'option' ) );
		opts.forEach( ( o ) => {
			if ( o?.dataset?.engine === 'standard' ) {
				o.disabled = true;
			} else {
				o.disabled = false;
			}
		} );
	}

	static enable_standard( regions_menu ) {
		var opts = Array.from( regions_menu.querySelectorAll( 'option' ) );
		opts.forEach( ( o ) => ( o.disabled = false ) );
	}
}

( ( w, d ) => {
	var f = d.querySelector( '#webinista_wreadit--options' );
	var voices = d.getElementById( 'webinista_wreadit_options[_polly_engine]' );
	var regions = d.getElementById( 'webinista_wreadit_options[_awsregion]' );

	f.addEventListener( 'focusout', ( event ) => {
		var { target } = event;
		var { type, nodeName, value } = target;

		if ( nodeName === 'INPUT' && type === 'text' ) {
			target.value = value.toString().trim();
		}
	} );

	voices.addEventListener( 'change', ( event ) => {
		if ( 'neural' == event.target.value ) {
			WebinistaWreadIt.disable_standard( regions );
		} else {
			WebinistaWreadIt.enable_standard( regions );
		}
	} );

	window.addEventListener( 'load', () => {
		if ( 'neural' == voices.value ) {
			WebinistaWreadIt.disable_standard( regions );
		}
	} );
} )( window, document );
