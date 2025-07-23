<?php
/**
 * Loads class files.
 *
 * PHP version 8
 *
 * @category  Plugin
 * @package   WebinistaWreadIt
 * @author    Webinista, Inc <readit@webinista.com>
 * @copyright 2024 Webinista, Inc
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

/**
 * Simple class with helper methods.
 */
final class Utils {
	/**
	 * Builds the file path that will be included by the require directive.
	 * @param string $file The file name, without the extension.
	 * @return Bar
	 */
	public static function file_name( $file ): string {
		return sprintf(
			'%1$s%2$sWebinista%2$sclass-%3$s.php',
			__DIR__,
			DIRECTORY_SEPARATOR,
			$file
		);
	}
}

require Utils::file_name( 'helpers' );
require Utils::file_name( 'settings' );
require Utils::file_name( 'wreadit' );
require Utils::file_name( 'regions' );
require Utils::file_name( 'optionspage' );
require Utils::file_name( 'textstrings' );
require Utils::file_name( 'settingsselectmenus' );
require Utils::file_name( 'langvoices' );
