<?php
/**
 * @package webinista_wreadit
 * @version 1.0.0
 */

namespace Webinista;
require_once dirname(plugin_dir_path(__FILE__)) . '/classes/load.php';

?>
	<header class="intro">
		<h1>
			<svg viewBox="0 0 67 57" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" id="webinista_logo"><title>Webinista</title><path stroke="none" fill-rule="evenodd" d="M25.0805611,0.924485012 C27.5213497,-0.308125931 31.4785085,-0.30819741 33.9194387,0.924485012 L54.5805609,11.358459 C57.0213496,12.59107 59,15.5886129 59,18.0539777 L59,38.9219258 C59,41.3871477 57.021491,44.384762 54.5805609,45.6174444 L33.9194387,56.0514184 C31.47865,57.2840294 27.5214913,57.2841007 25.0805611,56.0514184 L4.4194388,45.6174444 C1.97865015,44.3848335 0,41.3872906 0,38.9219258 L0,18.0539777 C0,15.5887558 1.97850861,12.5911415 4.4194388,11.358459 L25.0805611,0.924485012 Z M45.1483297,34.2726778 L48.1612419,16.896295 L45.3320439,16.896295 C42.1721605,16.896295 39.1592484,19.4691929 38.6081059,22.6759931 L36.5872503,34.2726778 L34.1622234,34.2726778 C33.1334242,34.2726778 32.4353104,33.4523335 32.6190246,32.408259 L35.3012512,16.896295 L32.4720533,16.896295 C29.3121698,16.896295 26.2992577,19.4691929 25.7481153,22.6759931 L23.7272596,34.2726778 L21.3022328,34.2726778 C20.2734336,34.2726778 19.5753197,33.4523335 19.7590339,32.408259 L21.4492041,22.6759931 C22.0003466,19.4691929 19.9060052,16.896295 16.7461218,16.896295 L13.880181,16.896295 L10.8672689,34.2726778 C10.3161265,37.4794781 12.4472107,40.0896644 15.6070941,40.0896644 L18.436292,40.0896644 L19.3916056,34.5336964 C18.9506916,37.6286316 21.0817758,40.0896644 24.1681735,40.0896644 L25.6011439,40.0896644 C28.6875417,40.0896644 31.5534825,37.6286316 32.2515963,34.5336964 C31.8106823,37.6286316 33.9417665,40.0896644 37.0281642,40.0896644 L38.4611346,40.0896644 C41.621018,40.0896644 44.5971873,37.4794781 45.1483297,34.2726778 Z" id="wantelope" class="webinista_logo_symbol"></path></svg>
			Webinista WreadIt
		</h1>

		<p>To use this plugin, you'll need:</p>
		<ul>
			<li>an <a href="https://aws.amazon.com/">Amazon Web Services</a> account; and</li>
			<li>an IAM user that has access to Polly and write access to an S3 bucket for audio storage.</li>
		 </ul>

		<p>Need help? Learn about <a href="https://wreadit.webinista.com/">support packages</a>.</p>
	</header>

	<form action="options.php" method="post" id="webinista_wreadit--options">
	<?php settings_fields( 'webinista_wreadit_options' ); ?>

	<details open>
		<summary><h2>Credentials and Bucket Settings</h2></summary>

		<p class="intro"><?php esc_html_e(
			'Enter your <abbr>AWS</abbr> Key ID, Secret Access Key, and <abbr>S3</abbr> bucket name below.',
			Settings::ASSET_ID
		); ?></p>
		<p>
			<?php OptionsPage::readit_awskey(); ?>
		</p>

		<p>
			<?php OptionsPage::readit_awssecret(); ?>
		</p>

		<p>
			<?php
				OptionsPage::readit_bucket_name();
				OptionsPage::readit_help_trigger(
					'webinista_wreadit--bucket_help',
					'Get help with the S3 Bucket Name field'
				);
			?>
		</p>
		<section popover id="webinista_wreadit--bucket_help">
			<?php
				OptionsPage::readit_help_trigger(
					'webinista_wreadit--bucket_help',
					'',
					'close'
				);
			?>
			<h3>S3 Bucket Name</h3>
			<p>
				Dots or period characters are strongly discouraged in Simple Storage
				Service bucket names. As a result, they are disallowed here.
			</p>

			<p>
				Instead, Webinista WreadIt uses <abbr>S3</abbr>'s
			<a href="https://docs.aws.amazon.com/AmazonS3/latest/userguide/VirtualHosting.html">virtual hosting</a>. You can also add a custom domain as an alias for the S3 virtual host or a content delivery network.
			</p>
		</section>

		<p>
			<?php OptionsPage::readit_path_prefix(); ?>
		</p>

		<p>
			<?php OptionsPage::readit_domain(); ?>
		</p>

	</details>

	<details open>
		<summary><h2>Audio and Region Settings</h2></summary>

		<p class="intro">
		Only regions and voices that are compatible
		with <abbr>AWS</abbr> Polly's <em>Standard</em> and <em>Neural</em> engines are available below.
		</p>

		<p>
			<?php OptionsPage::readit_pollyengine(); ?>
		</p>

		<p>
			<?php OptionsPage::readit_regions(); ?>
		</p>


		<p>
			<?php OptionsPage::readit_pollyvoices(); ?>
		</p>

		<p>
			<?php OptionsPage::readit_audio_format(); ?>
		</p>


	</details>

	<details open>
		<summary><h2>Allow for Post Types</h2></summary>
		<div>
		<?php OptionsPage::readit_post_types(); ?>
		</div>
	</details>

	<?php submit_button('Save Settings'); ?>

</form>
</div>
