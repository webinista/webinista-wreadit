<?php
/**
 * Webinista WreadIt: Adds a block and logic for creating audio files with Amazon Web Services' Polly service.
 *
 * @author      Webinista Inc.
 * @copyright   Copyright (C) 2025 Webinista Inc.
 * @link        https://webinista.com/wread-it/
 * Plugin Name:       Webinista WreadIt
 * Description:       Turn your posts into audio with Webinista WreadIt and AWS Polly.
 * Version:           1.1.2
 * Requires at least: 6.7
 * Requires PHP:      8.0
 * Author:            Webinista, Inc.
 * Author URI: https://webinista.com/
 * License:           GPL-3.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       webinista-wreadit
 * Plugin URI:        https://wreadit.webinista.com/
 *
 * @package WebinistaWreadIt
 *
 * Note that this package includes libraries distributed with Apache 2.0 and MIT licenses.
 *
 * Webinista WreadIt: Adds a block and logic for creating audio files with Amazon Web Services' Polly service.
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
	exit; // Exit if accessed directly.
}

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/classes/load.php';

if ( ! class_exists( 'WreadIt' ) ) :
	$wreadit = new WreadIt();
	register_activation_hook( __FILE__, array( $wreadit, 'on_activation' ) );
endif;
