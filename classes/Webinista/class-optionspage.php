<?php
/**
 * Methods for rendering the options form input fields.
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

//phpcs:disable WordPress.WP.I18n.NonSingularStringLiteralText
declare(strict_types=1);
namespace Webinista;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * OptionsPage
 *
 * Adds settings controls to the settings page.
 */
final class OptionsPage {

	/**
	 * Returns the allowed array keys for options values.
	 *
	 * @return array Returns the array keys for self::DEFAULT_OPTION.
	 */
	public static function allowed_fields(): array {
		return array_keys( Settings::DEFAULT_OPTIONS );
	}

	/**
	 * Renders the AWS key field of the options form.
	 *
	 * @return void
	 */
	public static function readit_awskey(): void {

		[ '_awskey' => $_awskey ] = get_option(
			'webinista_wreadit_options',
			Settings::DEFAULT_OPTIONS
		);

		printf(
			'<label for="webinista_wreadit_options[_awskey]">%1$s</label>
			 <input
                type="text"
                id="webinista_wreadit_options[_awskey]"
                name="webinista_wreadit_options[_awskey]"
                value="%2$s"
				required
            >',
			esc_html__( 'AWS Key ID', 'webinista-wreadit' ),
			esc_attr( $_awskey ),
		);
	}

	/**
	 * Renders the introductory AWS Secret field of the options form.
	 *
	 * @return void
	 */
	public static function readit_awssecret(): void {

		$options                        = get_option( 'webinista_wreadit_options', Settings::DEFAULT_OPTIONS );
		[ '_awssecret' => $_awssecret ] = $options;

		printf(
			'<label for="webinista_wreadit_options[_awssecret]">%1$s</label>
			 <input
                type="password"
                id="webinista_wreadit_options[_awssecret]"
                name="webinista_wreadit_options[_awssecret]"
                value="%2$s"
				required
            >',
			esc_html__( 'Secret Access Key', 'webinista-wreadit' ),
			esc_attr( $_awssecret ),
		);
	}

	/**
	 * Renders the introductory AWS Secret field of the options form.
	 *
	 * @return void
	 */
	public static function readit_bucket_name(): void {

		$options                         = get_option( 'webinista_wreadit_options', Settings::DEFAULT_OPTIONS );
		[ '_awss3bucket' => $_s3bucket ] = $options;

		printf(
			'<label for="webinista_wreadit_options[_awss3bucket]">%1$s</label>
			 <input
                type="text"
                id="webinista_wreadit_options[_awss3bucket]"
                name="webinista_wreadit_options[_awss3bucket]"
                value="%2$s"
                required
            >',
			esc_html__( 'Bucket Name', 'webinista-wreadit' ),
			esc_attr( $_s3bucket ),
		);
	}

	/**
	 * Renders the path prefix field of the options form.
	 *
	 * @return void
	 */
	public static function readit_path_prefix(): void {

		[ '_path_prefix' => $_path_prefix ] = get_option(
			'webinista_wreadit_options',
			Settings::DEFAULT_OPTIONS
		);

		printf(
			'<label for="">%1$s</label>
			 <input
                type="text"
                id="webinista_wreadit_options[_path_prefix]"
                name="webinista_wreadit_options[_path_prefix]"
                value="%2$s"
                aria-describedby="webinista_wreadit_options_path_prefix_described"
             >
             <span id="webinista_wreadit_options_path_prefix_described">%3$s</span>',
			esc_html__( 'Path Prefix', 'webinista-wreadit' ),
			esc_attr( $_path_prefix ),
			esc_html__( 'Prefix audio file names with a directory (Optional). Note that changing this setting later could break existing URLs.', 'webinista-wreadit' )
		);
	}

	/**
	 * Renders the domain field of the options form.
	 *
	 * @return void
	 */
	public static function readit_domain(): void {

		[ '_domain' => $_domain ] = get_option(
			'webinista_wreadit_options',
			Settings::DEFAULT_OPTIONS
		);

		printf(
			'<label for="webinista_wreadit_options[_domain]">%1$s</label>
			 <input
                type="url"
                id="webinista_wreadit_options[_domain]"
                name="webinista_wreadit_options[_domain]"
                value="%2$s"
                pattern="^(http|https):.*$"
                aria-describedby="webinista_wreadit_options_domain_described"
            >
            <span id="webinista_wreadit_options_domain_described">%3$s</span>',
			esc_html__( 'Custom Host URL', 'webinista-wreadit' ),
			esc_attr( $_domain ),
			esc_html__( 'Include the https:// prefix. (Optional)', 'webinista-wreadit' )
		);
	}

	/**
	 * Callback for array_map in readit_pollyengine
	 *
	 * @param string $label The option label.
	 * @param string $value The option value.
	 *
	 * @return void
	 */
	protected static function make_engine_options( string $label, string $value ): void {
		$options                            = get_option( 'webinista_wreadit_options', Settings::DEFAULT_OPTIONS );
		['_polly_engine' => $_polly_engine] = $options;

		printf(
			'<option value="%1$s" %2$s>%3$s</option>',
			esc_attr( $value ),
			selected( $_polly_engine, $value, false ),
			esc_html( $label )
		);
	}

	/**
	 * Renders the Polly Engine select menu
	 *
	 * @return void
	 */
	public static function readit_pollyengine(): void {
		$engines = SettingsSelectMenus::AWS_POLLY_ENGINES;

		printf(
			'<label for="webinista_wreadit_options[_polly_engine]">%s</label>
			<select
                id="webinista_wreadit_options[_polly_engine]"
                name="webinista_wreadit_options[_polly_engine]"
            >',
			esc_html__( 'Voice Engine', 'webinista-wreadit' )
		);

		array_walk(
			$engines,
			array( __CLASS__, 'make_engine_options' ),
			array_keys( $engines )
		);

		print '</select>';
	}
	/**
	 * Callback for array_map in readit_pollyvoices
	 *
	 * @param array  $option Language group to create an optgroup for.
	 * @param string $key Language group name.
	 * @return void
	 */
	protected static function make_voice_optgroups( array $option, string $key ): void {
		// Ensure that the voices are sorted in ascending alphabetical order.
		usort(
			$option['voices'],
			function ( $a, $b ) {
				return $a['name'] <=> $b['name'];
			}
		);
		printf( '<optgroup label="%s">', esc_attr( $key ) );
		array_walk(
			$option['voices'],
			array( __CLASS__, 'make_voice_options' )
		);
		print '</optgroup>';
	}

	/**
	 * Callback for array_map in make_voice_optgroups
	 *
	 * @param array $option Array of voices from the language group.
	 * @return void
	 */
	protected static function make_voice_options( array $option ): void {
		$settings             = get_option( 'webinista_wreadit_options', Settings::DEFAULT_OPTIONS );
		['_voice' => $_voice] = $settings;

		printf(
			'<option value="%1$s" %2$s data-engine="%4$s">%3$s</option>',
			esc_html( $option['label'] ),
			selected( $_voice, $option['name'], false ),
			esc_attr( $option['name'] ),
			esc_attr( join( '|', $option['engines'] ) )
		);
	}

	/**
	 * Callback for array_merge in readit_regions
	 *
	 * @param array $item An array with label, name keys.
	 * @return void
	 */
	protected static function make_regions_options( array $item ): void {

		$options                      = get_option( 'webinista_wreadit_options', Settings::DEFAULT_OPTIONS );
		['_awsregion' => $_awsregion] = $options;

		printf(
			'<option value="%1$s" %2$s data-engine="%4$s">%3$s</option>',
			esc_attr( $item['name'] ),
			selected( $_awsregion, $item['name'], false ),
			esc_html( $item['label'] ),
			esc_attr( $item['engine'] )
		);
	}
	/**
	 * Renders the AWS regions available to Polly
	 *
	 * @return void
	 */
	public static function readit_regions(): void {
		$regions_class = new Regions();
		$regions       = get_class_vars( get_class( $regions_class ) );

		printf(
			'<label for="webinista_wreadit_options[_awsregion]">%s</label>
			<select
                id="webinista_wreadit_options[_awsregion]"
                name="webinista_wreadit_options[_awsregion]"
                aria-describedby="webinista_wreadit_options_region_desc"
            >',
			esc_html__( 'AWS Region', 'webinista-wreadit' )
		);

		array_walk( $regions, array( __CLASS__, 'make_regions_options' ) );
		print '</select>';
	}

	/**
	 * Renders the Polly Engine select menu
	 *
	 * @return void
	 */
	public static function readit_pollyvoices(): void {

		$voices = LangVoices::get_voices_for_engines( array( 'neural', 'standard' ) );
		// SORT_STRING is a PHP option constant for ksort.
		ksort( $voices, SORT_STRING );

		printf(
			'<label for="webinista_wreadit_options[_voice]">%s</label>
			<select
                id="webinista_wreadit_options[_voice]"
                name="webinista_wreadit_options[_voice]"
            >',
			esc_html__( 'Voice', 'webinista-wreadit' ),
		);

		array_walk(
			$voices,
			array( __CLASS__, 'make_voice_optgroups' ),
			array_keys( $voices )
		);

		print '</select>';
	}

	/**
	 * Determines which post types authors can use with Polly.
	 *
	 * @return array
	 */
	public static function allow_audio_for_post_types(): array {
		$types = get_post_types(
			array(
				'public'             => 1,
				'publicly_queryable' => 1,
			),
			'names'
		);

		/*
		 * Excludes attachments, wreadit_audio, wreadit_revision post types.
		 * Probably unnecessary for the latter two types since they shouldn't be public.
		 */
		unset( $types['attachment'] );
		unset( $types['wreadit_audio'] );
		unset( $types['wreadit_revision'] );

		return $types;
	}

	/**
	 * Renders the introductory text for the Post Type section of Settings.
	 *
	 * @return void
	 */
	public static function readit_post_types_heading(): void {
		printf(
			'<p>%s</p>',
			esc_html__(
				'Select which post types can be converted to audio.',
				'webinista-wreadit'
			)
		);
	}

	/**
	 * Prints the checkbox for each post type.
	 *
	 * @param string $id String for ID attribute.
	 * @param string $key String to use as the _post_types array key.
	 * @param string $attrs String of attributes to add to the checkbox.
	 * @return string
	 */
	public static function make_post_type_field( string $id, string $key = '', string $attrs = '' ): string {
		return sprintf(
			'
            <input
                type="checkbox"
                name="webinista_wreadit_options[_post_types][%1$s]"
                id="%2$s"
                value="1"
                %3$s
            >
            ',
			$key,
			$id,
			$attrs
		);
	}

	/**
	 * Prints a checkbox for each post type.
	 *
	 * @return void
	 */
	public static function readit_post_types(): void {
		$allowed_post_types = self::allow_audio_for_post_types();

		$options = get_option( 'webinista_wreadit_options', Settings::DEFAULT_OPTIONS );

		[ '_post_types' => $_post_types ] = $options;

		foreach ( $allowed_post_types as $key => $value ) :
			$name       = sprintf( 'webinista_wreadit_options[_post_types][%s]', $key );
			$is_checked = '';

			if ( 'post' === $value || array_key_exists( $value, $_post_types ) ) :
				$is_checked = ' checked ';
			endif;

			$disabled = ( 'post' === $key ) ? ' disabled ' : '';

			printf(
				'<p class="webinista_wreadit--posts">
					<input
						type="checkbox"
						id="%1$s"
						name="%1$s"
						value="1"
						%3$s
						%4$s
					>
					<label for="%1$s">%2$s</label>
				</p>',
				esc_attr( $name ),
				esc_html( ucfirst( $key ) ),
				esc_attr( $is_checked ),
				esc_attr( $disabled )
			);

		endforeach;
	}

	/**
	 * Callback for array_merge in readit_pollyvoices
	 *
	 * @param string $label String to use for the option label.
	 * @param string $value String to use for the option value.
	 *
	 * @return void
	 */
	protected static function make_format_options( string $label, string $value ): void {
		$options                = get_option( 'webinista_wreadit_options', Settings::DEFAULT_OPTIONS );
		['_format' => $_format] = $options;

		printf(
			'<option value="%1$s" %2$s>%3$s</option>',
			esc_attr( $value ),
			selected( $_format, $value, false ),
			esc_html( $label )
		);
	}

	/**
	 * Creates the audio format selection menu
	 *
	 * @return void
	 */
	public static function readit_audio_format(): void {
		printf(
			'<label for="webinista_wreadit_options[_format]">%s</label>
			<select
                id="webinista_wreadit_options[_format]"
                name="webinista_wreadit_options[_format]"
            >',
			esc_html__( 'Audio Format', 'webinista-wreadit' )
		);

		$formats = SettingsSelectMenus::AWS_POLLY_AUDIO;

		array_walk(
			$formats,
			array( __CLASS__, 'make_format_options' ),
			array_keys( $formats )
		);

		print '</select>';
	}
}
