<?php
/**
 * Sets up and initiates the WreadIt plugin.
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

use Aws\Credentials\Credentials;
use Aws\Credentials\CredentialProvider;
use Aws\Polly\PollyClient;

//phpcs:disable Squiz.Commenting.ClassComment.Missing
final class WreadIt {

	const PLUGIN_FILE = 'webinista-wreadit/webinista-wreadit.php';

	/**
	 * Instantiates class, sets up routes, registers the sidebar, and enqueues assets
	 *
	 * @since 1.0
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'sidebar_register' ) );
		add_action( 'init', array( $this, 'setup_post_types' ) );
		add_action( 'rest_api_init', array( $this, 'setup_meta_key' ) );
		add_action( 'rest_api_init', array( $this, 'setup_routes' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_assets' ) );

		add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_init', array( $this, 'enqueue_settings_assets' ) );

		add_filter( 'plugin_action_links', array( $this, 'set_plugin_action_links' ), 10, 3 );
		add_filter( 'wp_get_attachment_url', array( $this, 'rewrite_media_urls' ), 20, 1 );
	}

	/**
	 * Registers a default set of options upon plugin activation.
	 *
	 * @since 1.0
	 */
	public static function on_activation(): void {
		add_option(
			'webinista_wreadit_options',
			Settings::DEFAULT_OPTIONS,
			'',
			false
		);
	}

	/**
	 * Adds a custom wreadit_revision post type.
	 *
	 * @since 1.0
	 */
	public function setup_post_types(): void {
		$args_revisions = array(
			'public'              => false,
			'exclude_from_search' => true,
			//phpcs:disable WordPress.WP.I18n.NonSingularStringLiteralDomain
			'label'               => esc_html__( 'Manage Audio Versions', 'webinista-wreadit' ), //phpcs:disable WordPress.WP.I18n.NonSingularStringLiteralDomain
			'menu_icon'           => 'none',
			'show_in_nav_menus'   => false,
			'capabilities'        => array(
				'create_posts' => 'do_not_allow',
			),
			'map_meta_cap'        => false,
		);

		register_post_type( 'wreadit_revision', $args_revisions );
	}

	/**
	 * Adds a meta key to attachment post types.
	 *
	 * @since 1.0
	 */
	public function setup_meta_key(): void {
		register_post_meta(
			'wreadit_revision',
			'_webinista',
			array(
				'label'        => 'Webinista Inc',
				'type'         => 'object',
				'show_in_rest' => array(
					'schema' => array(
						'type'       => 'object',
						'properties' => array(
							'readit' => array( 'type' => 'boolean' ),
						),
					),
				),
				'default'      => new \stdClass(),
				'single'       => true,
			)
		);
	}

	/**
	 * Establishes the REST API route for the plugin
	 *
	 * @since 1.0
	 */
	public function setup_routes(): void {
		register_rest_route(
			'wreadit/v1',
			'/audio',
			array(
				'methods'             => \WP_REST_Server::ALLMETHODS,
				'callback'            => array( $this, 'route_callback' ),
				'permission_callback' => array( $this, 'permissions_check' ),
				'args'                => array(
					'post_id' => array(
						'required'          => true,
						'validate_callback' => array( $this, 'validate_post_id' ),
						'sanitize_callback' => array( $this, 'sanitize_post_id' ),
					),
				),
			)
		);

		register_rest_route(
			'wreadit/v1',
			'/audio/delete',
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'route_detach_callback' ),
				'permission_callback' => array( $this, 'permissions_check' ),
				'args'                => array(
					'post_id' => array(
						'required'          => true,
						'validate_callback' => array( $this, 'validate_post_id' ),
						'sanitize_callback' => array( $this, 'sanitize_post_id' ),
					),
				),
			)
		);
	}

	/**
	 * Registers the plugin in the sidebar.
	 *
	 * @since 1.0
	 */
	public function sidebar_register(): void {
		wp_register_script(
			'webinista-wreadit',
			// Using dirname to get the parent directory of the current directory.
			//phpcs:ignore Modernize.FunctionCalls.Dirname.FileConstant
			plugins_url( '/build/index.js', dirname( __DIR__ ) ),
			array( 'wp-plugins', 'wp-editor', 'react' ),
			Settings::READIT_VERSION,
			array( 'in_footer' => false )
		);
	}

	/**
	 * Callback for the add_action('admin_init') item hook. Registers options
	 * for plugin.
	 *
	 * @return void
	 */
	public function register_settings(): void {
		$args = array(
			'type'              => 'array',
			'sanitize_callback' => array( $this, 'validate_webinista_wreadit_options' ),
			'default'           => Settings::DEFAULT_OPTIONS,
			'show_in_rest'      => false,
		);

		register_setting(
			'webinista_wreadit_options',
			'webinista_wreadit_options',
			$args
		);
	}

	/**
	 * Validates the submitted webinista_wreadit_options settings.
	 *
	 * @param array $input The user-submitted settings data.
	 */
	public function validate_webinista_wreadit_options( array $input ) {

		// Was an AWS Key ID submitted?
		if ( empty( sanitize_text_field( $input['_awskey'] ) ) ) :
			add_settings_error(
				'webinista_wreadit_options',
				'webinista_wreadit_options_error--awskey',
				//phpcs:disabled WordPress.WP.I18n.NonSingularStringLiteralText
				esc_html__( 'I need an AWS Key ID.', 'webinista-wreadit' )
			);

		endif;

		// Was a Secret Key submitted?
		if ( empty( sanitize_text_field( $input['_awssecret'] ) ) ) :
			add_settings_error(
				'webinista_wreadit_options',
				'webinista_wreadit_options_error--awssecret',
				//phpcs:disabled WordPress.WP.I18n.NonSingularStringLiteralText
				esc_html__( 'I need a Secret Access Key.', 'webinista-wreadit' )
			);

		endif;

		// Is the bucket name valid?
		if ( ! Helpers::is_valid_s3_bucket_name( sanitize_text_field( $input['_awss3bucket'] ) ) ) :
			add_settings_error(
				'webinista_wreadit_options',
				'webinista_wreadit_options_error--awss3bucket',
				//phpcs:disabled WordPress.WP.I18n.NonSingularStringLiteralText
				esc_html__( 'Your S3 bucket name does not follow AWS rules.', 'webinista-wreadit' )
			);

		endif;

		// Is the region known?
		if ( ! Regions::is_valid_region( sanitize_text_field( $input['_awsregion'] ) ) ) :
			add_settings_error(
				'webinista_wreadit_options',
				'webinista_wreadit_options_error--awsregion',
				esc_html__( 'Please enter a known region name.', 'webinista-wreadit' )
			);

		endif;

		// Is the domain valid?
		if ( ! Helpers::is_valid_host( sanitize_text_field( $input['_domain'] ) ) ) :
			add_settings_error(
				'webinista_wreadit_options',
				'webinista_wreadit_options_error--domain',
				esc_html__( 'Please enter a valid host name.', 'webinista-wreadit' )
			);

		endif;

		// Is the provided audio format valid?
		if ( ! SettingsSelectMenus::is_valid_audio_format( sanitize_text_field( $input['_format'] ) ) ) :
			add_settings_error(
				'webinista_wreadit_options',
				'webinista_wreadit_options_error--awsaudio',
				esc_html__( 'Unsupported audio format.', 'webinista-wreadit' )
			);

		endif;

		// Is the _voice an accepted one?
		if ( ! SettingsSelectMenus::is_valid_voice( sanitize_text_field( $input['_voice'] ) ) ) :
			add_settings_error(
				'webinista_wreadit_options',
				'webinista_wreadit_options_error--voice',
				esc_html__( 'Unsupported voice option.', 'webinista-wreadit' )
			);

		endif;

		// Is the _polly_engine an accepted one?
		if ( ! SettingsSelectMenus::is_supported_engine( sanitize_text_field( $input['_polly_engine'] ) ) ) :
			add_settings_error(
				'webinista_wreadit_options',
				'webinista_wreadit_options_error--engine',
				esc_html__( 'Unsupported engine option.', 'webinista-wreadit' )
			);

		endif;

		// Do all of the the _post_types submitted exist?
		if ( ! Helpers::all_valid_post_types( $input['_post_types'] ) ) :
			add_settings_error(
				'webinista_wreadit_options',
				'webinista_wreadit_options_error--post_types',
				esc_html__( 'One or more of the post types selected does not exist.', 'webinista-wreadit' )
			);

		endif;

		return array_merge( Settings::DEFAULT_OPTIONS, $input );
	}

	/**
	 * Callback for the add_action('admin_menu') item hook.
	 *
	 * @return void
	 */
	public function add_settings_page(): void {
		add_options_page(
			'Webinista WreadIt',
			'Webinista WreadIt',
			'manage_options',
			Settings::OPTIONS_PAGE_NAME,
			array( $this, 'render_settings_page' )
		);
	}

	/**
	 * Renders the settings form on the options page.
	 *
	 * @return void
	 */
	public static function render_settings_page(): void {

		$page = sprintf(
			'%s/views/options_form.php',
			dirname( plugin_dir_path( __DIR__ ) )
		);

		if ( ! file_exists( $page ) ) {
			print '<h1>Failed to load the settings page view.</h1>';
			return;
		}

		include $page;
	}

	/**
	 * Callback function for the plugin_action_links hook.
	 * Adds a delete link to the plugin's row on wp-admin/plugins.php.
	 * See: <https://developer.wordpress.org/reference/hooks/plugin_action_links/>.
	 *
	 * @param array  $plugin_actions An array of plugin action links.
	 * @param string $plugin_file Path to the plugin file relative to the plugins directory.
	 * @param array  $plugin_data An array of plugin data.
	 */
	public static function set_plugin_action_links( $plugin_actions, $plugin_file, $plugin_data ): array {
		$new_actions = array();
		$args        = array( 'page' => 'wreadit' );

		if ( self::PLUGIN_FILE === $plugin_file ) :
			$new_actions['settings'] = sprintf(
				'<a href="%1$s">%2$s</a>',
				esc_url( add_query_arg( $args, menu_page_url( 'wreadit', false ) ) ),
				esc_html__( 'Settings', 'webinista-wreadit' )
			);

			$new_actions['support'] = sprintf(
				'<a href="%1$s" target="_blank">%2$s</a>',
				esc_url( $plugin_data['PluginURI'] . 'support/' ),
				esc_html__( 'Support', 'webinista-wreadit' ),
			);
		endif;

		return array_merge( $new_actions, $plugin_actions );
	}

	/**
	 * Enqueue assets style sheets for sidebar.
	 *
	 * @since 1.0
	 */
	public function enqueue_assets(): void {
		wp_register_style(
			'webinista-wreadit',
			// Using dirname to get the parent directory of the current directory.
			plugins_url( '/build/style-index.css', dirname( __DIR__ ) ),
			array(),
			Settings::READIT_VERSION
		);

		wp_enqueue_script( 'webinista-wreadit' );
		wp_enqueue_style( 'webinista-wreadit' );
	}

	/**
	 * Enqueue assets style sheets for sidebar.
	 *
	 * @since 1.0
	 * @return void
	 */
	public function enqueue_settings_assets(): void {

		if ( is_admin() && Helpers::is_wreadit_settings_page() ) :
			wp_register_script(
				'webinista-wreadit-admin',
				// Using dirname to get the parent directory of the current directory.
				//phpcs:ignore Modernize.FunctionCalls.Dirname.FileConstant
				plugins_url( '/assets/script.js', dirname( __DIR__ ) ),
				array(),
				Settings::READIT_VERSION,
				array( 'in_footer' => true )
			);
			wp_enqueue_script( 'webinista-wreadit-admin' );

			wp_register_style(
				'webinista-wreadit-admin',
				// Using dirname to get the parent directory of the current directory.
				//phpcs:ignore Modernize.FunctionCalls.Dirname.FileConstant
				plugins_url( '/assets/style.css', dirname( __DIR__ ) ),
				array( 'forms' ),
				Settings::READIT_VERSION
			);

			wp_enqueue_style( 'webinista-wreadit-admin' );

			// Removes maintenance and update nag boxes.
			remove_action( 'admin_notices', 'maintenance_nag', 10 );
			remove_action( 'admin_notices', 'update_nag', 3 );

		endif;
	}

	/**
	 * Validates that the post id is correctly formatted and for an existing post
	 *
	 * @since 1.0
	 * @param string $post_id String of digits representing the post_id to convert to audio or
	 *               retrieve audio version.
	 * @return \WP_Error|bool Returns WP_Error if the string can't pass `ctype_digit`
	 *                        test. Boolean true otherwise.
	 */
	public function validate_post_id( string $post_id ): \WP_Error|bool {
		$response = false;

		if ( ! ctype_digit( $post_id ) ) {
			$response = new \WP_Error(
				'400',
				esc_html__( 'Bad Request', 'webinista-wreadit' ),
				array( 'status' => 400 )
			);
		} elseif ( get_post_type( $post_id ) === false ) {
			$response = new \WP_Error(
				'404',
				esc_html__( 'Post not found.', 'webinista-wreadit' ),
				array( 'status' => 404 )
			);
		} else {
			$response = true;
		}

		return $response;
	}

	/**
	 * Forces post_id to be an integer. Probably overkill since the value gets validated
	 * first.
	 *
	 * @since 1.0
	 *
	 * @param string | int $param Should be a string that returns true for `ctype_digit`.
	 * @return int Returns an integer.
	 */
	public function sanitize_post_id( string|int $param ): int {
		return intval( $param );
	}

	/**
	 * Is the user logged in and can they publish posts?
	 *
	 * @since 1.0
	 *
	 * @return bool Returns boolean true or false.
	 */
	public function permissions_check(): bool {
		return is_user_logged_in() && current_user_can( 'publish_posts' );
	}

	/**
	 * Responds to the request.
	 *
	 * @since 1.0
	 *
	 * @param \WP_REST_Request $request A WP_REST_Request object.
	 * @return \WP_REST_Response|\WP_Error Returns WP_REST_Response or WP_Error.
	 */
	public function route_callback( \WP_REST_Request $request ): \WP_REST_Response|\WP_Error {
		$response = array();

		if ( ! Settings::setup_is_complete() ) {
			$response = new \WP_Error(
				'wreadit_empty_settings',
				__(
					wp_kses(
						'An <b>AWS Key ID</b>, <b>Secret Access Key</b>, and <b>S3 Bucket Name</b> are required. <a href="wp-admin/options-general.php?page=wreadit">WreadIt Settings</a>',
						'b,a,div' // Allowed tags.
					),
					'webinista-wreadit'
				)
			);
		} elseif ( $request->get_method() === \WP_REST_Server::CREATABLE ) {
			$response = $this->process_post_request( $request );
		} else {
			$response = $this->process_get_request( $request );
		}

		return rest_ensure_response( $response );
	}

	/**
	 * Responds to delete route requests.
	 *
	 * @since 1.0
	 *
	 * @param \WP_REST_Request $request A WP_REST_Request object.
	 * @return \WP_REST_Response|\WP_Error Returns WP_REST_Response or WP_Error.
	 */
	public function route_detach_callback( \WP_REST_Request $request ): \WP_REST_Response|\WP_Error {
		$response       = array();
		$request_params = $request->get_params();

		if ( ! is_user_logged_in() || ! current_user_can( 'delete_posts' ) ) {
			wp_die(
				esc_html__(
					'You do not have permission to perform that action.',
					'webinista-wreadit'
				),
				null,
				403
			);
		}

		if ( ! wp_verify_nonce(
			sanitize_text_field( $request_params['_wpnonce'] ),
			'wreadit_detach_audio'
		)
		) {
			wp_die(
				esc_html__( 'Your token is invalid.', 'webinista-wreadit' ),
				null,
				403
			);
		}

		$request_params = $request->get_json_params();
		$audio_id       = intval( $request_params['audio_id'] );

		wp_update_post(
			array(
				'ID'          => $audio_id,
				'post_status' => 'trash',
				'post_type'   => 'wreadit_revision',
			)
		);

		$del = Helpers::delete_file_from_storage(
			$audio_id,
			Settings::get_option( '_awss3bucket' ),
		);

		$read_it         = new \stdClass();
		$read_it->readit = false;

		$response['success'] = update_post_meta(
			intval( $request_params['audio_id'] ),
			'_webinista',
			$read_it
		);

		return rest_ensure_response( $response );
	}

	/**
	 * Processes GET requests.
	 *
	 * @since 1.0
	 *
	 * @param \WP_REST_Request $request A WP_REST_Request object.
	 * @return Object Returns WP_REST_Response.
	 */
	protected function process_get_request( \WP_REST_Request $request ): \WP_REST_Response {
		$response = array();

		$post_id = $request->get_param( 'post_id' );

		// If $post_id is empty, return an error message.
		if ( empty( $post_id ) ) :
			$response = new \WP_Error(
				'wreadit_empty_post_id',
				esc_html_e( 'Missing a post ID.', 'webinista-wreadit' )
			);

			// If the post's type is not in the list of post types for audio return false.
		elseif ( ! Helpers::is_convertible_post_type( $post_id ) ) :

			$response = array(
				'audio' => false,
				'token' => wp_create_nonce( 'wreadit_detach_audio' ),
			);

		else :
			// Get audio attachments for this post.
			$attachments = get_attached_media( 'audio/mpeg', $post_id );

			// Retrieve _webinista meta key for each attachment.
			$attachments = array_map(
				function ( $attachment ) {
					$attachment->meta = get_post_meta( $attachment->ID, '_webinista', true );
					return $attachment;
				},
				$attachments
			);

			// Return only those attachments where readit === true.
			$attachments = array_filter(
				$attachments,
				function ( $attachment ) {
					if (
						property_exists( $attachment->meta, 'readit' ) &&
						( true === $attachment->meta->readit )
					) {
						return $attachment;
					}
				}
			);

			$attachments = array_values( $attachments );

			// Rewrite GUID URLs if a custom domain is set.
			$attachments = array_map(
				function ( $attachment ) {
					$attachment->guid = Helpers::filter_guid(
						$attachment->guid,
						$attachment->ID
					);
					return $attachment;
				},
				$attachments
			);

			$response['audio'] = $attachments;
			$response['token'] = wp_create_nonce( 'wreadit_detach_audio' );

		endif;

		return rest_ensure_response( $response );
	}

	/**
	 * Makes the request to AWS Polly.
	 *
	 * @since 1.0
	 *
	 * @param int $post_id ID of the post or page we'd like to convert to audio.
	 * @return Object Returns WP_Error.
	 */
	private function make_audio_request( int $post_id ): array|\WP_Error {
		$response = array();

		$the_post = get_post( $post_id, 'OBJECT', 'raw' );

		try {

			$creds = new Credentials(
				Settings::get_option( '_awskey' ),
				Settings::get_option( '_awssecret' )
			);

			$provider = CredentialProvider::fromCredentials( $creds );

			$client = new PollyClient(
				array(
					'region'      => Settings::get_option( '_awsregion' ),
					'output'      => 'json',
					'credentials' => $provider,
				)
			);

			$task_opts = array(
				'Engine'             => Settings::get_option( '_polly_engine' ),
				'LanguageCode'       => Helpers::get_locale(),
				'OutputFormat'       => Settings::get_option( '_format' ),
				'OutputS3BucketName' => Settings::get_option( '_awss3bucket' ),
				// Generate a slug for the post.
				'OutputS3KeyPrefix'  => Helpers::build_file_prefix( $the_post ),
				'Text'               => Helpers::build_text_for_audio( $the_post ),
				'VoiceId'            => Settings::get_option( '_voice' ),
			);

			$task     = $client->startSpeechSynthesisTask( $task_opts );
			$response = $task->get( 'SynthesisTask' );

		} catch ( \Aws\Polly\Exception\PollyException $error ) {
			$response = new \WP_Error(
				'aws_auth_error',
				$error->getAwsErrorMessage()
			);
		}

		return $response;
	}

	/**
	 * Processes POST requests.
	 *
	 * @since 1.0
	 *
	 * @param \WP_REST_Request $request A WP_REST_Request object.
	 * @return Object Returns WP_REST_Response or WP_Error.
	 */
	protected function process_post_request( \WP_REST_Request $request ): \WP_REST_Response|\WP_Error {
		$response = array( 'url' => '' );

		$request_params = $request->get_json_params();

		if ( ! wp_verify_nonce(
			sanitize_text_field( $request_params['_wpnonce'] ),
			'wreadit_detach_audio'
		)
		) {
			wp_die(
				esc_html__( 'Your token is invalid.', 'webinista-wreadit' ),
				null,
				403
			);
		}

		$post_id = $this->sanitize_post_id( $request->get_param( 'post_id' ) );

		$aws_response = $this->make_audio_request( $post_id );

		if ( is_wp_error( $aws_response ) ) :
			$response = $aws_response;
		else :
			$created_date = Helpers::mysql_from_datetime_result( $aws_response['CreationTime'] );

			$guid = Helpers::make_s3_url(
				Settings::get_option( '_awss3bucket' ),
				Settings::get_option( '_awsregion' ),
				wp_parse_url( $aws_response['OutputUri'], PHP_URL_PATH )
			);

			$add_media = wp_insert_attachment(
				array(
					'post_title'        => Helpers::get_filename_from_url( $aws_response['OutputUri'] ),
					'post_name'         => Helpers::get_filename_from_url( $aws_response['OutputUri'] ),
					'comment_status'    => 'closed',
					'guid'              => $aws_response['OutputUri'],
					'post_mime_type'    => Helpers::mime_type( $aws_response['OutputFormat'] ),
					'post_date_gmt'     => $created_date,
					'post_date'         => $created_date,
					'post_modified'     => $created_date,
					'post_modified_gmt' => $created_date,
					'post_content'      => Helpers::build_post_content( $post_id ),
				),
				//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				false,
				$post_id,
				true,
			);

			$read_it         = new \stdClass();
			$read_it->readit = true;

			update_post_meta(
				$add_media,
				'_webinista',
				$read_it
			);

			update_attached_file(
				$add_media,
				sanitize_url( $aws_response['OutputUri'] )
			);

			update_post_meta(
				$add_media,
				'_wp_attachment_metadata',
				array(
					'metadata' => array(
						'dataformat'  => $aws_response['OutputFormat'],
						'sample_rate' => Settings::get_option( '_sample_rate' ),
						'lang'        => $aws_response['LanguageCode'],
						'char_length' => $aws_response['RequestCharacters'],
						'mime'        => Helpers::mime_type( $aws_response['OutputFormat'] ),
						'task_id'     => $aws_response['TaskId'],
					),
					'artist'   => get_the_author_meta(
						'user_nicename',
						get_post_field( 'post_author', $post_id )
					),
				)
			);

			$response = array(
				'audio_id' => $add_media,
				'url'      => Helpers::filter_guid( $aws_response['OutputUri'], $add_media ),
				'meta'     => get_post_meta( $add_media, '_webinista', true ),
				'token'    => wp_create_nonce( 'wreadit_detach_audio' ),
			);

		endif;

		return rest_ensure_response( $response );
	}

	/**
	 * Rewrites URLs of WreadIt attachments in the media library.
	 *
	 * @since 1.1
	 *
	 * @param string $url A media library URL.
	 * @return string Returns a URL string that removes the site URL and includes the S3 bucket name and prefix.
	 */
	public function rewrite_media_urls( string $url ): string {
		$new_url = $url;

		// If the incoming URL contains the aws bucket name setting ...
		if ( stristr( $url, Settings::get_option( '_awss3bucket' ) ) !== false ) :
			$upload_dir = wp_upload_dir();

			if ( array_key_exists( 'baseurl', $upload_dir ) ) :
				$new_url = trim( str_ireplace( $upload_dir['baseurl'], '', $url ), '/' );
				$new_url = Helpers::rewrite_url_with_custom_domain( $new_url );
			endif;

		endif;

		return $new_url;
	}
}
