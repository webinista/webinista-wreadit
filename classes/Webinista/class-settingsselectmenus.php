<?php
/**
 * Constants for displaying text.
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
 * Webinista WreadIt: Adds a block and logic for creating audio files with Amazon Web
 * Services' Polly service.
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

/**
 * SettingsSelectMenus
 *
 * Creates select menus for the options page.
 */
final class SettingsSelectMenus {

	const AWS_POLLY_ENGINES = array(
		'standard' => 'Standard',
		'neural'   => 'Neural',
	);

	const AWS_POLLY_AUDIO = array(
		'mp3'        => 'MP3 (Recommended)',
		'ogg_vorbis' => 'Ogg Vorbis',
	);

	/**
	 * Validates the provided audio format.
	 *
	 * @param string $input The string to validate.
	 * @return bool Return boolean true/false.
	 */
	public static function is_valid_audio_format( string $input ): bool {
		return array_key_exists( $input, self::AWS_POLLY_AUDIO );
	}

	/**
	 * Validates the provided voice value
	 *
	 * @param string $input The string to validate.
	 * @return bool Return boolean true/false.
	 */
	public static function is_valid_voice( string $input ): bool {
		$languages = LangVoices::get_voices_for_engines( array( 'neural', 'standard' ) );
		$languages = array_column( $languages, 'voices' );
		$languages = array_column( array_merge( ...$languages ), 'name' );

		return in_array( $input, $languages, true );
	}

	/**
	 * Validates the provided engine value.
	 *
	 * @param string $input The string to validate.
	 * @return bool Return boolean true/false.
	 */
	public static function is_supported_engine( string $input ): bool {
		return ! empty( $input ) && array_key_exists( $input, self::AWS_POLLY_ENGINES );
	}
}
