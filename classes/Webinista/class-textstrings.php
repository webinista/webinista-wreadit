<?php
/**
 * Constants for displaying text.
 *
 * @category  Plugin
 * @package   WebinistaWreadIt
 * @author    Webinista, Inc <readit@webinista.com>
 * @copyright 2024 Webinista, Inc
 * @license   MIT License
 * @link      https://wreadit.webinista.com/
 * @since     File available since Release 1.0.0-beta
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

namespace Webinista;

/**
 * Strings for options page.
 */
final class TextStrings {

	const AWS_BUCKET_LABEL         = '<abbr>S3</abbr>  Bucket Name';
	const AWS_AUDIO_FORMAT         = 'Audio Format';
	const AWS_KEY_LABEL            = '<abbr>AWS</abbr> Key ID';
	const AWS_REGION               = '<abbr>AWS</abbr> Region';
	const AWS_SECRET_LABEL         = 'Secret Access Key';
	const POST_TYPES_INTRO_TEXT    = 'Select which post types can be converted to audio.';
	const DOMAIN_LABEL             = 'Custom Host URL';
	const DOMAIN_DESCRIBED_BY      = 'Include the https:// prefix. (Optional)';
	const PATH_PREFIX              = 'Path prefix';
    const PATH_PREFIX_DESCRIBED_BY = 'Prefix audio file names with a directory such as <code>audio</code> or <code>media</code> (Optional). Changing this setting later could break existing URLs.';   // phpcs:ignore
	const VOICE_ENGINES_LABEL      = 'Voice Engine';
	const VOICE_LABEL              = 'Voice';
	const BLANK_AWS_KEY            = 'I need an AWS Key ID.';
	const BLANK_SECRET_KEY         = 'I need a Secret Access Key.';
	const BAD_BUCKET_NAME          = 'Your S3 bucket name does not follow AWS rules.';
	const UNKOWN_REGION            = 'Please enter a known region name.';
	const INVALID_HOST             = 'Please enter a valid host name.';
	const INVALID_ENGINE_ARGUMENT  = 'That engine is not supported.';
}
