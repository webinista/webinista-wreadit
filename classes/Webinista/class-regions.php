<?php
/**
 * AWS Regions. See https://docs.aws.amazon.com/general/latest/gr/s3.html
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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Static constants for AWS region names and identifiers.
 * Not a complete list. Only includes regions common to Standard and Neural voices.
 */
final class Regions {
	// phpcs:disable Squiz.Commenting.VariableComment.Missing
	public $us_east_1 = array(
		'label'  => 'US East (N. Virginia)',
		'name'   => 'us-east-1',
		'engine' => 'all',
	);

	public $us_east_2 = array(
		'label'  => 'US East (Ohio)',
		'name'   => 'us-east-2',
		'engine' => 'standard',
	);

	public $us_west_1 = array(
		'label'  => 'US West (N. California)',
		'name'   => 'us-west-1',
		'engine' => 'standard',
	);


	public $us_west_2 = array(
		'label'  => 'US West (Oregon)',
		'name'   => 'us-west-2',
		'engine' => 'all',
	);

	public $af_south_1 = array(
		'label'  => 'Africa (Cape Town)',
		'name'   => 'af-south-1',
		'engine' => 'all',
	);

	public $ap_east_1 = array(
		'label'  => 'Asia Pacific (Hong Kong)',
		'name'   => 'ap-east-1',
		'engine' => 'standard',
	);

	public $ap_northeast_1 = array(
		'label'  => 'Asia Pacific (Tokyo)',
		'name'   => 'ap-northeast-1',
		'engine' => 'all',
	);

	public $ap_northeast_2 = array(
		'label'  => 'Asia Pacific (Seoul)',
		'name'   => 'ap-northeast-2',
		'engine' => 'all',
	);

	public $ap_northeast_3 = array(
		'label'  => 'Asia Pacific (Osaka)',
		'name'   => 'ap-northeast-3',
		'engine' => 'all',
	);

	public $ap_south_1 = array(
		'label'  => 'Asia Pacific (Mumbai)',
		'name'   => 'ap-south-1',
		'engine' => 'all',
	);

	public $ap_southeast_1 = array(
		'label'  => 'Asia Pacific (Singapore)',
		'name'   => 'ap-southeast-1',
		'engine' => 'all',
	);

	public $ap_southeast_2 = array(
		'label'  => 'Asia Pacific (Sydney)',
		'name'   => 'ap-southeast-2',
		'engine' => 'all',
	);

	public $ap_southeast_5 = array(
		'label'  => 'Asia Pacific (Malaysia)',
		'name'   => 'ap-southeast-5',
		'engine' => 'all',
	);

	public $cn_northwest_1 = array(
		'label'  => 'China (Ningxia)',
		'name'   => 'cn-northwest-1',
		'engine' => 'standard',
	);

	public $ca_central_1 = array(
		'label'  => 'Canada (Central)',
		'name'   => 'ca-central-1',
		'engine' => 'all',
	);

	public $eu_central_1 = array(
		'label'  => 'Europe (Frankfurt)',
		'name'   => 'eu-central-1',
		'engine' => 'all',
	);

	public $eu_west_1 = array(
		'label'  => 'Europe (Ireland)',
		'name'   => 'eu-west-1',
		'engine' => 'all',
	);

	public $eu_west_2 = array(
		'label'  => 'Europe (London)',
		'name'   => 'eu-west-2',
		'engine' => 'all',
	);

	public $eu_west_3 = array(
		'label'  => 'Europe (Paris)',
		'name'   => 'eu-west-3',
		'engine' => 'all',
	);

	public $eu_south_2 = array(
		'label'  => 'Europe (Spain)',
		'name'   => 'eu-south-2',
		'engine' => 'all',
	);

	public $eu_north_1 = array(
		'label'  => 'Europe (Stockholm)',
		'name'   => 'eu-north-1',
		'engine' => 'standard',
	);

	public $me_south_1 = array(
		'label'  => 'Middle East (Bahrain)',
		'name'   => 'me-south-1',
		'engine' => 'standard',
	);

	public $sa_east_1 = array(
		'label'  => 'South America (São Paulo)',
		'name'   => 'sa-east-1',
		'engine' => 'standard',
	);
	// phpcs:enable

	/**
	 * Determine whether the provided S3 region is a valid one.
	 *
	 * @param string $region Region identifier to compare.
	 * @return bool Returns boolean true/false value
	 */
	public static function is_valid_region( string $region ): bool {
		$region_arrays = get_class_vars( __CLASS__ );
		$reg           = str_replace( '-', '_', $region );
		return ! empty( $region ) && array_key_exists( $reg, $region_arrays );
	}
}
