<?php
/**
 * Settings for Webinista WreadIt.
 *
 * PHP version 8
 *
 * @category  Plugin
 * @package   WebinistaWreadIt
 * @author    Webinista, Inc <readit@webinista.com>
 * @copyright 2025 Webinista, Inc
 * @license   MIT License
 * @link      https://wreadit.webinista.com/
 * @since     File available since Release 1.0.0
 *
 * Note that this plugin includes libraries distributed with Apache 2.0 and MIT licenses.
 *
 * Webinista WreadIt: Adds a block and logic for creating audio files with
 * Amazon Web Services' Polly service.
 *
 * Copyright (C) 2025  Tiffany B. Brown, Webinista Inc.
 *
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

declare(strict_types=1);
namespace Webinista;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Settings and constants used throughout the plugin.
 * Also see: class-settingsselect-menus.php and class-regions.php
 *
 * @package Webinista_WreadIt
 * @version 0.1.0
 */
final class Settings {
	const AUDIO_TYPE        = 'mp3';
	const OPTIONS_PAGE_NAME = 'wreadit';

	const DEFAULT_OPTIONS = array(
		'_awskey'       => '',
		'_awssecret'    => '',
		'_awss3bucket'  => '',
		'_awsregion'    => 'us-east-1',
		'_polly_engine' => 'standard',
		'_voice'        => 'Kendra',
		'_post_types'   => array( 'post' => '1' ),
		'_format'       => 'mp3',
		'_path_prefix'  => '',
		'_domain'       => '',
		'_sample_rate'  => '24000',
	);

	/**
	 * Gets all options for the plugin.
	 *
	 * @return array Returns the webinista_wreadit_options array from the database.
	 */
	public static function user_options(): array {
		return get_option( 'webinista_wreadit_options' );
	}

	/**
	 * Returns the value of a single array key from webinista_wreadit_options.
	 *
	 * @param string $name The requested setting value / array key.
	 * @return string|array The value returned will be either a string or an array.
	 */
	public static function get_option( string $name ): string|array {
		$options = self::user_options();
		$opt_val = '';

		if ( array_key_exists( $name, $options ) ) {
			$opt_val = $options[ $name ];
		}

		return $opt_val;
	}

	/**
	 * Checks whether the Access Key, Secret Key and bucket name are set.
	 *
	 * @return boolean True or False
	 */
	public static function setup_is_complete(): bool {
		return (
			boolval( self::get_option( '_awskey' ) ) &&
			boolval( self::get_option( '_awssecret' ) ) &&
			boolval( self::get_option( '_awss3bucket' ) )
		);
	}
}
