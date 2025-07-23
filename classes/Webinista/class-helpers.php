<?php
/**
 * Simple methods for validation
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

use Aws\Credentials\Credentials;
use Aws\Credentials\CredentialProvider;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;


/**
 * Simple methods for validation
 */
final class Helpers {

	/**
	 * Accepts a URL and returns the file name.
	 *
	 * @since 1.0
	 * @param string $url The URL to parse.
	 * @return string The filename portion of the URL without its extension.
	 */
	public static function get_filename_from_url( string $url ): string {
		$value = '';

		if ( self::is_valid_url( $url ) ) {
			$value = pathinfo( wp_parse_url( $url, PHP_URL_PATH ) );
		}

		return sprintf( '%s.%s', $value['filename'], $value['extension'] );
	}

	/**
	 * Checks that the URL is valid and begins with https
	 *
	 * @since 1.0
	 * @param string $url The URL to parse.
	 * @return boolean
	 */
	protected static function is_valid_url( string $url ): bool {
		$is_valid_url = filter_var(
			$url,
			FILTER_VALIDATE_URL,
			array( 'flags' => FILTER_FLAG_PATH_REQUIRED )
		);

		$ok_protocol = ( strpos( $url, 'https://' ) === 0 );

		return $is_valid_url && $ok_protocol;
	}

	/**
	 * Accepts a \Aws\Api\DateTimeResult object and returns a MySQL-formatted date
	 *
	 * @since 1.0
	 * @param \Aws\Api\DateTimeResult $date The date to format.
	 * @return string
	 */
	public static function mysql_from_datetime_result( \Aws\Api\DateTimeResult $date ): string {
		return $date->format( 'Y-m-d H:i:s' );
	}

	/**
	 * Return the correct MIME type based on the output format from the AWS response.
	 *
	 * @since 1.0
	 *
	 * @param string $output_format Should be 'ogg', 'pcm', or 'mp3'. Defaults to MP3.
	 * @return string
	 */
	public static function mime_type( string $output_format = 'mp3' ): string {
		$format = '';

		switch ( $output_format ) :
			case 'ogg':
				$format = 'audio/ogg';
				break;
			case 'pcm':
				$format = 'audio/pcm';
				break;
			default:
				$format = 'audio/mpeg';
				break;
		endswitch;

		return $format;
	}

	/**
	 * Concatenates the title, author, and post text for sending to converter.
	 *
	 * @since 1.0
	 *
	 * @param \WP_Post $post_data A \WP_Post object.
	 * @return string
	 */
	public static function build_text_for_audio( \WP_Post $post_data ): string {
		return sprintf(
			'%s %s by %s %s %s',
			sanitize_text_field( $post_data->post_title ),
			PHP_EOL . PHP_EOL,
			get_the_author_meta( 'display_name', $post_data->post_author ),
			PHP_EOL . PHP_EOL,
			sanitize_textarea_field( $post_data->post_content )
		);
	}

	/**
	 * Removes disallowed characters from the prefix string.
	 *
	 * @since 1.0
	 *
	 * @param string $prefix String to sanitize.
	 * @return string.
	 */
	protected static function sanitize_prefix( string $prefix ): string {
		// Remove all characters EXCEPT for the ones below.
		$pattern = "/[^0-9a-zA-Z\/\!\-_\.\*\'\(\):;\$@=+\,\?&]/";
		return preg_replace( $pattern, '', $prefix );
	}

	/**
	 * Makes the filename prefix for the audio file name.
	 *
	 * @since 1.0
	 *
	 * @param \WP_Post $post_data A \WP_Post object.
	 * @return string.
	 */
	public static function build_file_prefix( \WP_Post $post_data ): string {
		$pname = $post_data->post_name;

		$object_key = boolval( $pname ) ? $pname : sanitize_title( $post_data->post_title );

		/*
		If the _path_prefix is not blank, strip whatever trailing slash the user entered
		and prepend it to the object key.
		*/
		if ( Settings::get_option( '_path_prefix' ) !== '' ) {

			$pref = '';
			if ( function_exists( 'mb_rtrim' ) ) :
				$pref = mb_rtrim( Settings::get_option( '_path_prefix' ), '/' );
			else :
				$pref = rtrim( Settings::get_option( '_path_prefix' ), '/' );
			endif;

			$object_key = sprintf(
				'%s/%s',
				rawurlencode( $pref ),
				$object_key
			);
		}

		return self::sanitize_prefix( $object_key );
	}

	/**
	 * Create post_content value based on the post title.
	 *
	 * @since 1.0
	 *
	 * @param int $post_id The ID of an existing post.
	 * @return string.
	 */
	public static function build_post_content( int $post_id ): string {
		$the_post     = get_post( intval( $post_id ) );
		$post_content = sprintf(
			// translators: "%1$s" should be the post title and (%2$s) is the post slug.
			__( 'Audio version of "%1$s" (%2$s)', 'webinista-wreadit'),
			esc_textarea( get_post_field( 'post_title', $the_post ) ),
			esc_textarea( get_post_field( 'post_name', $the_post ) )
		);

		return $post_content;
	}

	/**
	 * Whether the user is viewing the plugin's settings page.
	 *
	 * @since 1.0
	 *
	 * @return boolean.
	 */
	public static function is_wreadit_settings_page(): bool {
		// Ignoring nonce verification rule because this doesn't do anything destructive.
		//phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( ! array_key_exists( 'page', $_GET ) ) {
			return false;
		}

		//phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return is_admin() && ( 'wreadit' === $_GET['page'] );
	}

	/**
	 * Check whether the S3 Bucket name is valid
	 *
	 * @since 1.0
	 * @param string $bucket_name User input bucket name value.
	 * @return boolean.
	 */
	public static function is_valid_s3_bucket_name( string $bucket_name ): bool {
		if ( empty( $bucket_name ) ) {
			return false;
		}

		$regex = '/^(?!xn--|sthree-|amzn-s3-demo-|.*-s3alias$|.*--ol-s3$|.*--x-s3$)[a-z0-9][a-z0-9-]{1,61}[a-z0-9]$/';
		return boolval( preg_match( $regex, $bucket_name ) );
	}

	/**
	 * Deletes the current audio version of a post from S3.
	 *
	 * @since 1.0
	 * @param string|int $post_id ID of the post whose audio version should be deleted.
	 * @return \Aws\Result | string.
	 */
	public static function delete_file_from_storage( string|int $post_id ): \Aws\Result|string {
		$response = new \stdclass();
		$creds    = new Credentials(
			Settings::get_option( '_awskey' ),
			Settings::get_option( '_awssecret' )
		);

		$provider = CredentialProvider::fromCredentials( $creds );

		$file_url = get_the_guid( intval( $post_id ) );

		$s3 = new S3Client(
			array(
				'version'     => 'latest',
				'region'      => Settings::get_option( '_awsregion' ),
				'credentials' => $provider,
			)
		);

		$object_key = str_replace(
			Settings::get_option( '_awss3bucket' ) . '/',
			'',
			wp_parse_url( $file_url, PHP_URL_PATH )
		);

		try {

			$response = $s3->deleteObject(
				array(
					'Bucket' => Settings::get_option( '_awss3bucket' ),
					'Key'    => $object_key,
				)
			);

		} catch ( S3Exception $error ) {
			$response = $error->getAwsErrorMessage();
		}

		return $response;
	}

	/**
	 * Checks that $url is valid and begins with https
	 * Differs from is_valid_url because this function does not require a path and allows HTTPS
	 *
	 * @since 1.0
	 * @param string $url The URL to parse.
	 * @return boolean
	 */
	public static function is_valid_host( string $url ): bool {
		$response = false;

		if ( empty( $url ) ) {
			$response = true;
		} else {
			$is_valid_url = filter_var( $url, FILTER_VALIDATE_URL );
			$response     = ( $is_valid_url && preg_match( '~^http(|s):\/\/~', $url ) );
		}

		return $response;
	}

	/**
	 * Checks that the submitted $type exists.
	 *
	 * @since 1.0
	 * @param string $type The string or post type to check.
	 * @return boolean
	 */
	public static function is_valid_post_type( string $type ): bool {
		return boolval( post_type_exists( $type ) );
	}

	/**
	 * Checks that the submitted post is one that should be allowed to be converted to
	 * audio.
	 *
	 * @since 1.0
	 * @param int $post_id The post ID to check.
	 * @return boolean
	 */
	public static function is_convertible_post_type( int $post_id ): bool {
		$allowed   = Settings::get_option( '_post_types' );
		$post_type = get_post_type( $post_id );

		return array_key_exists( $post_type, $allowed );
	}


	/**
	 * Checks that ALL of the submitted post types exist.
	 *
	 * @since 1.0
	 * @param array $post_types Array of post types to check.
	 * @return boolean
	 */
	public static function all_valid_post_types( array $post_types ): bool {
		$submitted = array_keys( $post_types );

		// Check posts one-by-one.
		$valid = array_map( array( __CLASS__, 'is_valid_post_type' ), $submitted );

		// Filter out truthy values.
		$errors = array_filter(
			$valid,
			function ( $item ) {
				return false === $item;
			}
		);

		/*
		If there are falsy values, they'll be in the $errors array. Convert count($errors)
		to a boolean, then invert it with !.
		*/
		return ! boolval( count( $errors ) );
	}

	/**
	 * Builds an S3 URL for storing in the database.
	 *
	 * @since 1.0
	 * @param string $bucket S3 bucket name.
	 * @param string $region AWS region.
	 * @param string $path File / object path.
	 * @return string
	 * @throws \InvalidArgumentException If the bucket name contains invalid characters or
	 *                                  if the region is invalid.
	 */
	public static function make_s3_url( string $bucket, string $region, string $path = '' ): string {
		if ( ! self::is_valid_s3_bucket_name( $bucket ) ) :
			throw new \InvalidArgumentException( 'Your S3 bucket name contains one or more disallowed characters.' );
		endif;

		if ( ! Regions::is_valid_region( $region ) ) :
			throw new \InvalidArgumentException( 'Unsupported AWS region. Please check your settings.' );
		endif;

		$url = sprintf(
			'https://%1$s.s3.%2$s.amazonaws.com/%3$s',
			$bucket,
			$region,
			$path
		);

		return sanitize_url( $url );
	}

	/**
	 * Builds an S3 URL for storing in the database.
	 *
	 * @since 1.0
	 * @return string
	 */
	public static function get_locale(): string {
		$wp_locale = get_locale();
		return str_replace( '_', '-', $wp_locale );
	}

	/**
	 * Builds an S3 URL for display in the browser.
	 *
	 * @since 1.0
	 * @param string     $guid The guid value of the audio attachment.
	 * @param int|string $audio_id The post ID for the audio attachment.
	 * @return string
	 */
	public static function rewrite_url_with_custom_domain( string $guid, int|string $audio_id ): string {
		$url = '';

		if ( 'attachment' !== get_post_type( $audio_id ) ) {
			return '';
		}

		$path        = wp_parse_url( $guid, PHP_URL_PATH );
		$bucket_name = Settings::get_option( '_awss3bucket' );

		if ( $bucket_name ) {
			if ( function_exists( 'mb_trim' ) ) :
				$path = mb_trim( str_replace( $bucket_name, '', $path ), '/' );
			else :
				$path = trim( str_replace( $bucket_name, '', $path ), '/' );
			endif;
		}

		if ( Settings::get_option( '_domain' ) ) :
			$url = sprintf(
				'%1$s/%2$s',
				Settings::get_option( '_domain' ),
				$path
			);
		else :
			$url = self::make_s3_url(
				Settings::get_option( '_awss3bucket' ),
				Settings::get_option( '_awsregion' ),
				$path
			);
		endif;

		$protocols = array( 'http', 'https' );
		return esc_url( $url, $protocols );
	}

	/**
	 * Filter the GUID to return the URL with a custom domain.
	 *
	 * @since 1.0
	 * @param string     $guid The GUID of the attachment post / audio file.
	 * @param int|string $id The post ID of the audio post.
	 * @return string
	 */
	public static function filter_guid( string $guid, int|string $id = 0 ): string {

		add_filter(
			'get_the_guid',
			array( __CLASS__, 'rewrite_url_with_custom_domain' ),
			10,
			2
		);

		return apply_filters( 'get_the_guid', $guid, $id );
	}
}
