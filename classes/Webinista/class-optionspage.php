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
            // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
			TextStrings::AWS_KEY_LABEL,
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
           	// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
			TextStrings::AWS_SECRET_LABEL,
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
			TextStrings::AWS_BUCKET_LABEL,
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
			TextStrings::PATH_PREFIX,
			esc_attr( $_path_prefix ),
			// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
			TextStrings::PATH_PREFIX_DESCRIBED_BY
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
			TextStrings::DOMAIN_LABEL,
			esc_attr( $_domain ),
			esc_html( TextStrings::DOMAIN_DESCRIBED_BY )
		);
	}

	/**
	 * Callback for array_map in readit_pollyengine
	 *
	 * @param string $label The option label.
	 * @param string $value The option value.
	 *
	 * @return string
	 */
	protected static function make_engine_options( string $label, string $value ): string {
		$options                            = get_option( 'webinista_wreadit_options', Settings::DEFAULT_OPTIONS );
		['_polly_engine' => $_polly_engine] = $options;

		return sprintf(
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
		$opts = array_map(
			array( __CLASS__, 'make_engine_options' ),
			SettingsSelectMenus::AWS_POLLY_ENGINES,
			array_keys( SettingsSelectMenus::AWS_POLLY_ENGINES )
		);
		$opts = join( "\n", $opts );

		printf(
			'<label for="webinista_wreadit_options[_polly_engine]">%s</label>
			<select
                id="webinista_wreadit_options[_polly_engine]"
                name="webinista_wreadit_options[_polly_engine]"
            >
            %s
            </select>',
			esc_html( TextStrings::VOICE_ENGINES_LABEL ),
			$opts
		);
	}

	/**
	 * Callback for array_map in make_voice_options
	 *
	 * @param array  $option Language group to create an optgroup for.
	 * @param string $key Language group name.
	 * @return string
	 */
	protected static function make_voice_optgroups( array $option, string $key ): string {
		// Ensure that the voices are sorted in ascending alphabetical order.
		usort(
			$option['voices'],
			function ( $a, $b ) {
				return $a['name'] <=> $b['name'];
			}
		);

		$voices = array_map(
			array( __CLASS__, 'make_voice_options' ),
			$option['voices']
		);

		$voices = join( PHP_EOL, $voices );

		return sprintf(
			'<optgroup label="%s">%s</optgroup>',
			esc_attr( $key ),
			$voices
		);
	}

	/**
	 * Callback for array_map in make_voice_optgroups
	 *
	 * @param array $option Array of voices from the language group.
	 * @return string
	 */
	protected static function make_voice_options( array $option ): string {
		$settings             = get_option( 'webinista_wreadit_options', Settings::DEFAULT_OPTIONS );
		['_voice' => $_voice] = $settings;

		$engines = join( ':', $option['engines'] );

		return sprintf(
			'<option value="%2$s" data-engine="%3$s" %4$s>%1$s</option>',
			esc_html( $option['label'] ),
			esc_attr( $option['name'] ),
			esc_attr( $engines ),
			selected( $_voice, $option['name'], false ),
		);
	}

	/**
	 * Callback for array_merge in readit_regions
	 *
	 * @param array $item An array with label, name keys.
	 * @return string
	 */
	protected static function make_regions_options( array $item ): string {

		$options                      = get_option( 'webinista_wreadit_options', Settings::DEFAULT_OPTIONS );
		['_awsregion' => $_awsregion] = $options;

		return sprintf(
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

		$opts = array_map(
			array( __CLASS__, 'make_regions_options' ),
			$regions
		);

		$opts = join( "\n", $opts );

		printf(
			'<label for="webinista_wreadit_options[_awsregion]">%1$s</label>
			<select
                id="webinista_wreadit_options[_awsregion]"
                name="webinista_wreadit_options[_awsregion]"
                aria-describedby="webinista_wreadit_options_region_desc"
            >
            %2$s
            </select>
            <span id="webinista_wreadit_options_region_desc" hidden></span>',
			// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
			TextStrings::AWS_REGION,
			$opts
		);
	}

	/**
	 * Renders the Polly Engine select menu
	 *
	 * @return void
	 */
	public static function readit_pollyvoices(): void {

		$voices = LangVoices::get_voices_for_engines( array( 'neural', 'standard' ) );
		ksort( $voices, SORT_STRING );

		$opts = array_map(
			array( __CLASS__, 'make_voice_optgroups' ),
			$voices,
			array_keys( $voices )
		);

		$opts = join( PHP_EOL, $opts );

		printf(
			'<label for="webinista_wreadit_options[_voice]">%s</label>
			<select
                id="webinista_wreadit_options[_voice]"
                name="webinista_wreadit_options[_voice]"
            >
            %s
            </select>',
			esc_html( TextStrings::VOICE_LABEL ),
			$opts
		);
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
		printf( '<p>%s</p>', esc_html( TextStrings::POST_TYPES_INTRO_TEXT ) );
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

		$output = '<input type="hidden"
					id="webinista_wreadit_options[_post_types][post]"
					name="webinista_wreadit_options[_post_types][post]"
					value="1">';

		foreach ( $allowed_post_types as $key => $value ) :
			$name       = sprintf( 'webinista_wreadit_options[_post_types][%s]', $key );
			$is_checked = '';

			if ( 'post' === $value || array_key_exists( $value, $_post_types ) ) :
				$is_checked = ' checked ';
			endif;

			$disabled = ( 'post' === $key ) ? ' disabled ' : '';

			$output .= sprintf(
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
				$name,
				ucfirst( $key ),
				$is_checked,
				$disabled
			);

		endforeach;

		print $output;
	}

	/**
	 * Callback for array_merge in readit_pollyvoices
	 *
	 * @param string $label String to use for the option label.
	 * @param string $value String to use for the option value.
	 *
	 * @return string
	 */
	protected static function make_format_options( string $label, string $value ): string {
		$options                = get_option( 'webinista_wreadit_options', Settings::DEFAULT_OPTIONS );
		['_format' => $_format] = $options;

		return sprintf(
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
		$opts = array_map(
			array( __CLASS__, 'make_format_options' ),
			SettingsSelectMenus::AWS_POLLY_AUDIO,
			array_keys( SettingsSelectMenus::AWS_POLLY_AUDIO )
		);

		$opts = join( "\n", $opts );

		printf(
			'<label for="webinista_wreadit_options[_format]">%s</label>
			<select
                id="webinista_wreadit_options[_format]"
                name="webinista_wreadit_options[_format]"
            >
            %s
            </select>',
			esc_html( TextStrings::AWS_AUDIO_FORMAT ),
			$opts
		);
	}

	/**
	 * Creates button with icons.
	 *
	 * @param string $target_name The html ID to use as the popovertarget attribute.
	 * @param string $title_text The string to use for the button's title attribute.
	 * @param string $mode The mode to use for the button. Should be 'help' or 'close'.
	 * @return void
	 */
	public static function readit_help_trigger( string $target_name, string $title_text, string $mode = 'help' ): void {
		$icon  = '';
		$class = '';

		switch ( $mode ) {
			case 'close':
				$icon  = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-x-icon lucide-circle-x"><title>Close this panel</title><circle stroke-width="0" cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>';
				$class = 'webinista_wreadit--close';
				break;
			default:
				$icon  = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-help-icon lucide-circle-help"><circle stroke-width="0" cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/></svg>';
				$class = 'webinista_wreadit--help';
		}

		printf(
			'<button popovertarget="%1$s" type="button" title="%2$s" class="%3$s">
				%4$s
			</button>',
			esc_attr( $target_name ),
			esc_attr( $title_text ),
			esc_attr( $class ),
			$icon
		);
	}
}
