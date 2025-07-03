<?php
/**
 * AWS Polly Neural Voices
 * See https://docs.aws.amazon.com/polly/latest/dg/neural-voices.html#ntts-regions
 *
 * PHP version 8
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
 * Constants for Standard and Neural voices and methods for retrieving them.
 */
final class LangVoices {
	const ALL_OPTIONS = array(
		'Arabic'                  => array(
			'code'   => 'arb',
			'voices' => array(
				array(
					'lang'    => 'arb',
					'name'    => 'Zeina',
					'label'   => 'Zeina',
					'gender'  => 'Female',
					'engines' => array(
						'standard',
					),
				),

			),

		),

		'Arabic (Gulf)'           => array(
			'code'   => 'ar-AE',
			'voices' => array(
				array(
					'lang'    => 'ar-AE',
					'name'    => 'Hala',
					'label'   => 'Hala',
					'gender'  => 'Female',
					'engines' => array(
						'neural',
					),

				),
				array(
					'lang'    => 'ar-AE',
					'name'    => 'Zayd',
					'label'   => 'Zayd',
					'gender'  => 'Male',
					'engines' => array(
						'neural',
					),

				),

			),

		),

		'Dutch (Belgian)'         => array(
			'code'   => 'nl-BE',
			'voices' => array(
				array(
					'lang'    => 'nl-BE',
					'name'    => 'Lisa',
					'label'   => 'Lisa',
					'gender'  => 'Female',
					'engines' => array(
						'neural',
					),

				),

			),

		),

		'Catalan'                 => array(
			'code'   => 'ca-ES',
			'voices' => array(
				array(
					'lang'    => 'ca-ES',
					'name'    => 'Arlet',
					'label'   => 'Arlet',
					'gender'  => 'Female',
					'engines' => array(
						'neural',
					),

				),

			),

		),

		'Czech'                   => array(
			'code'   => 'cs-CZ',
			'voices' => array(
				array(
					'lang'    => 'cs-CZ',
					'name'    => 'Jitka',
					'label'   => 'Jitka',
					'gender'  => 'Female',
					'engines' => array(
						'neural',
					),

				),

			),

		),

		'Chinese (Cantonese)'     => array(
			'code'   => 'yue-CN',
			'voices' => array(
				array(
					'lang'    => 'yue-CN',
					'name'    => 'Hiujin',
					'label'   => 'Hiujin',
					'gender'  => 'Female',
					'engines' => array(
						'neural',
					),

				),

			),

		),

		'Chinese (Mandarin)'      => array(
			'code'   => 'cmn-CN',
			'voices' => array(
				array(
					'lang'    => 'cmn-CN',
					'name'    => 'Zhiyu',
					'label'   => 'Zhiyu',
					'gender'  => 'Female',
					'engines' => array(
						'neural',
						'standard',
					),

				),

			),

		),

		'Danish'                  => array(
			'code'   => 'da-DK',
			'voices' => array(
				array(
					'lang'    => 'da-DK',
					'name'    => 'Naja',
					'label'   => 'Naja',
					'gender'  => 'Female',
					'engines' => array(
						'standard',
					),

				),

				array(
					'lang'    => 'da-DK',
					'name'    => 'Mads',
					'label'   => 'Mads',
					'gender'  => 'Male',
					'engines' => array(
						'standard',
					),

				),

				array(
					'lang'    => 'da-DK',
					'name'    => 'Sofie',
					'label'   => 'Sofie',
					'gender'  => 'Female',
					'engines' => array(
						'neural',
					),

				),

			),

		),

		'Dutch'                   => array(
			'code'   => 'nl-NL',
			'voices' => array(
				array(
					'lang'    => 'nl-NL',
					'name'    => 'Laura',
					'label'   => 'Laura',
					'gender'  => 'Female',
					'engines' => array(
						'neural',
					),

				),

				array(
					'lang'    => 'nl-NL',
					'name'    => 'Lotte',
					'label'   => 'Lotte',
					'gender'  => 'Female',
					'engines' => array(
						'standard',
					),

				),

				array(
					'lang'    => 'nl-NL',
					'name'    => 'Ruben',
					'label'   => 'Ruben',
					'gender'  => 'Male',
					'engines' => array(
						'standard',
					),

				),

			),

		),

		'English (Australian)'    => array(
			'code'   => 'en-AU',
			'voices' => array(
				array(
					'lang'    => 'en-AU',
					'name'    => 'Nicole',
					'label'   => 'Nicole',
					'gender'  => 'Female',
					'engines' => array(
						'standard',
					),

				),

				array(
					'lang'    => 'en-AU',
					'name'    => 'Olivia',
					'label'   => 'Olivia',
					'gender'  => 'Female',
					'engines' => array(
						'generative',
						'neural',
					),

				),

				array(
					'lang'    => 'en-AU',
					'name'    => 'Russell',
					'label'   => 'Russell',
					'gender'  => 'Male',
					'engines' => array(
						'standard',
					),

				),

			),

		),

		'English (British)'       => array(
			'code'   => 'en-GB',
			'voices' => array(
				array(
					'lang'    => 'en-GB',
					'name'    => 'Amy',
					'label'   => 'Amy',
					'gender'  => 'Female',
					'engines' => array(
						'generative',
						'neural',
						'standard',
					),

				),
				array(
					'lang'    => 'en-GB',
					'name'    => 'Emma',
					'label'   => 'Emma',
					'gender'  => 'Female',
					'engines' => array(
						'neural',
						'standard',
					),

				),
				array(
					'lang'    => 'en-GB',
					'name'    => 'Brian',
					'label'   => 'Brian',
					'gender'  => 'Male',
					'engines' => array(
						'neural',
						'standard',
					),

				),
				array(
					'lang'    => 'en-GB',
					'name'    => 'Arthur',
					'label'   => 'Arthur',
					'gender'  => 'Male',
					'engines' => array(
						'neural',
					),

				),
			),

		),

		'English (Indian)'        => array(
			'code'   => 'en-IN',
			'voices' => array(
				array(
					'lang'    => 'en-IN',
					'name'    => 'Aditi',
					'label'   => 'Aditi (English)',
					'gender'  => 'Female',
					'engines' => array(
						'standard',
					),

				),

				array(
					'lang'    => 'en-IN',
					'name'    => 'Raveena',
					'label'   => 'Raveena',
					'gender'  => 'Female',
					'engines' => array(
						'standard',
					),

				),

				array(
					'lang'    => 'en-IN',
					'name'    => 'Kajal',
					'label'   => 'Kajal',
					'gender'  => 'Female',
					'engines' => array(
						'generative',
						'neural',
					),

				),

			),

		),

		'English (Ireland)'       => array(
			'code'   => 'en-IE',
			'voices' => array(
				array(
					'lang'    => 'en-IE',
					'name'    => 'Niamh',
					'label'   => 'Niamh',
					'gender'  => 'Female',
					'engines' => array(
						'neural',
					),

				),

			),

		),

		'English (New Zealand)'   => array(
			'code'   => 'en-NZ',
			'voices' => array(
				array(
					'lang'    => 'en-IE',
					'name'    => 'Aria',
					'label'   => 'Aria',
					'gender'  => 'Female',
					'engines' => array(
						'neural',
					),

				),

			),

		),

		'English (Singaporean)'   => array(
			'code'   => 'en-SG',
			'voices' => array(
				array(
					'lang'    => 'en-SG',
					'name'    => 'Jasmine',
					'label'   => 'Jasmine',
					'gender'  => 'Female',
					'engines' => array(
						'neural',
					),

				),

			),

		),

		'English (South African)' => array(
			'code'   => 'en-ZA',
			'voices' => array(
				array(
					'lang'    => 'en-ZA',
					'name'    => 'Ayanda',
					'label'   => 'Ayanda',
					'gender'  => 'Female',
					'engines' => array(
						'generative',
						'neural',
					),

				),

			),

		),

		'English (US)'            => array(
			'code'   => 'en-US',
			'voices' => array(
				array(
					'lang'    => 'en-US',
					'name'    => 'Danielle',
					'label'   => 'Danielle',
					'gender'  => 'Female',
					'engines' => array(
						'generative',
						'long_form',
						'neural',
					),

				),

				array(
					'lang'    => 'en-US',
					'name'    => 'Gregory',
					'label'   => 'Gregory',
					'gender'  => 'Male',
					'engines' => array(
						'long_form',
						'neural',
					),

				),

				array(
					'lang'    => 'en-US',
					'name'    => 'Ivy',
					'label'   => 'Ivy',
					'gender'  => 'Female ( child )',
					'engines' => array(
						'neural',
						'standard',
					),

				),

				array(
					'lang'    => 'en-US',
					'name'    => 'Joanna',
					'label'   => 'Joanna',
					'gender'  => 'Female',
					'engines' => array(
						'generative',
						'neural',
						'standard',
					),

				),

				array(
					'lang'    => 'en-US',
					'name'    => 'Kendra',
					'label'   => 'Kendra',
					'gender'  => 'Female',
					'engines' => array(
						'neural',
						'standard',
					),

				),

				array(
					'lang'    => 'en-US',
					'name'    => 'Kimberly',
					'label'   => 'Kimberly',
					'gender'  => 'Female',
					'engines' => array(
						'neural',
						'standard',
					),

				),

				array(
					'lang'    => 'en-US',
					'name'    => 'Salli',
					'label'   => 'Salli',
					'gender'  => 'Female',
					'engines' => array(
						'neural',
						'standard',
					),

				),

				array(
					'lang'    => 'en-US',
					'name'    => 'Joey',
					'label'   => 'Joey',
					'gender'  => 'Male',
					'engines' => array(
						'neural',
						'standard',
					),

				),

				array(
					'lang'    => 'en-US',
					'name'    => 'Justin',
					'label'   => 'Justin',
					'gender'  => 'Male ( child )',
					'engines' => array(
						'neural',
					),

				),

				array(
					'lang'    => 'en-US',
					'name'    => 'Kevin',
					'label'   => 'Kevin',
					'gender'  => 'Male ( child )',
					'engines' => array(
						'neural',
						'standard',
					),

				),
				array(
					'lang'    => 'en-US',
					'name'    => 'Matthew',
					'label'   => 'Matthew',
					'gender'  => 'Male',
					'engines' => array(
						'generative',
						'neural',
					),

				),
				array(
					'lang'    => 'en-US',
					'name'    => 'Ruth',
					'label'   => 'Ruth',
					'gender'  => 'Female',
					'engines' => array(
						'generative',
						'long_form',
						'neural',
					),

				),
				array(
					'lang'    => 'en-US',
					'name'    => 'Stephen',
					'label'   => 'Stephen',
					'gender'  => 'Male',
					'engines' => array(
						'generative',
						'neural',
					),

				),
				array(
					'lang'    => 'en-US',
					'name'    => 'Patrick',
					'label'   => 'Patrick',
					'gender'  => 'Male',
					'engines' => array(
						'long_form',
					),

				),

			),

		),

		'English (Welsh)'         => array(
			'code'   => 'en-GB-WLS',
			'voices' => array(
				array(
					'lang'    => 'en-GB-WLS',
					'name'    => 'Geraint',
					'label'   => 'Geraint',
					'gender'  => 'Male',
					'engines' => array(
						'standard',
					),

				),

			),
		),

		'Finnish'                 => array(
			'code'   => 'fi-FI',
			'voices' => array(
				array(
					'lang'    => 'fi-FI',
					'name'    => 'Suvi',
					'label'   => 'Suvi',
					'gender'  => 'Female',
					'engines' => array(
						'neural',
					),

				),

			),

		),

		'French'                  => array(
			'code'   => 'fr-FR',
			'voices' => array(
				array(
					'lang'    => 'fr-FR',
					'name'    => 'Celine',
					'label'   => 'Céline / Celine',
					'gender'  => 'Female',
					'engines' => array(
						'standard',
					),

				),

				array(
					'lang'    => 'fr-FR',
					'name'    => 'Lea',
					'label'   => 'Léa',
					'gender'  => 'Female',
					'engines' => array(
						'generative',
						'neural',
						'standard',
					),

				),

				array(
					'lang'    => 'fr-FR',
					'name'    => 'Mathieu',
					'label'   => 'Mathieu',
					'gender'  => 'Male',
					'engines' => array(
						'standard',
					),

				),

				array(
					'lang'    => 'fr-FR',
					'name'    => 'Remi',
					'label'   => 'Rémi',
					'gender'  => 'Male',
					'engines' => array(
						'generative',
						'neural',
					),

				),

			),

		),

		'French (Belgian)'        => array(
			'code'   => 'fr-BE',
			'voices' => array(
				array(
					'lang'    => 'fr-BE',
					'name'    => 'Isabelle',
					'label'   => 'Isabelle',
					'gender'  => 'Female',
					'engines' => array(
						'neural',
					),

				),

			),

		),

		'French (Canadian)'       => array(
			'code'   => 'fr-CA',
			'voices' => array(
				array(
					'lang'    => 'fr-CA',
					'name'    => 'Chantal',
					'label'   => 'Chantal',
					'gender'  => 'Female',
					'engines' => array(
						'standard',
					),

				),

				array(
					'lang'    => 'fr-CA',
					'name'    => 'Gabrielle',
					'label'   => 'Gabrielle',
					'gender'  => 'Female',
					'engines' => array(
						'neural',
					),

				),

				array(
					'lang'    => 'fr-CA',
					'name'    => 'Liam',
					'label'   => 'Liam',
					'gender'  => 'Male',
					'engines' => array(
						'neural',
					),

				),

			),

		),

		'German'                  => array(
			'code'   => 'de-DE',
			'voices' => array(
				array(
					'lang'    => 'de-DE',
					'name'    => 'Marlene',
					'label'   => 'Marlene',
					'gender'  => 'Female',
					'engines' => array(
						'standard',
					),

				),

				array(
					'lang'    => 'de-DE',
					'name'    => 'Vicki',
					'label'   => 'Vicki',
					'gender'  => 'Female',
					'engines' => array(
						'generative',
						'neural',
						'standard',
					),

				),

				array(
					'lang'    => 'de-DE',
					'name'    => 'Hans',
					'label'   => 'Hans',
					'gender'  => 'Male',
					'engines' => array(
						'standard',
					),

				),

				array(
					'lang'    => 'de-DE',
					'name'    => 'Daniel',
					'label'   => 'Daniel',
					'gender'  => 'Male',
					'engines' => array(
						'generative',
						'neural',
					),

				),

			),

		),

		'German (Austrian)'       => array(
			'code'   => 'de-AT',
			'voices' => array(
				array(
					'lang'    => 'de-AT',
					'name'    => 'Hannah',
					'label'   => 'Hannah',
					'gender'  => 'Female',
					'engines' => array(
						'neural',
					),

				),

			),

		),

		'German (Swiss)'          => array(
			'code'   => 'de-CH',
			'voices' => array(
				array(
					'lang'    => 'de-CH',
					'name'    => 'Sabrina',
					'label'   => 'Sabrina',
					'gender'  => 'Female',
					'engines' => array(
						'neural',
					),

				),

			),

		),

		'Hindi'                   => array(
			'code'   => 'hi-IN',
			'voices' => array(
				array(
					'lang'    => 'hi-IN',
					'name'    => 'Aditi',
					'label'   => 'Aditi (Hindi)',
					'gender'  => 'Female',
					'engines' => array(
						'standard',
					),

				),

				array(
					'lang'    => 'hi-IN',
					'name'    => 'Kajal',
					'label'   => 'Kajal',
					'gender'  => 'Female',
					'engines' => array(
						'neural',
					),

				),

			),

		),

		'Icelandic'               => array(
			'code'   => 'is-IS',
			'voices' => array(
				array(
					'lang'    => 'is-IS',
					'name'    => 'Dora',
					'label'   => 'Dóra / Dora',
					'gender'  => 'Female',
					'engines' => array(
						'standard',
					),

				),

				array(
					'lang'    => 'is-IS',
					'name'    => 'Karl',
					'label'   => 'Karl',
					'gender'  => 'Male',
					'engines' => array(
						'standard',
					),

				),

			),

		),

		'Italian'                 => array(
			'code'   => 'it-IT',
			'voices' => array(
				array(
					'lang'    => 'it-IT',
					'name'    => 'Carla',
					'label'   => 'Carla',
					'gender'  => 'Female',
					'engines' => array(
						'standard',
					),

				),

				array(
					'lang'    => 'it-IT',
					'name'    => 'Bianca',
					'label'   => 'Bianca',
					'gender'  => 'Female',
					'engines' => array(
						'generative',
						'neural',
						'standard',
					),

				),

				array(
					'lang'    => 'it-IT',
					'name'    => 'Giorgio',
					'label'   => 'Giorgio',
					'gender'  => 'Male',
					'engines' => array(
						'standard',
					),

				),

				array(
					'lang'    => 'it-IT',
					'name'    => 'Adriano',
					'label'   => 'Adriano',
					'gender'  => 'Male',
					'engines' => array(
						'neural',
					),

				),

			),

		),

		'Japanese'                => array(
			'code'   => 'ja-JP',
			'voices' => array(
				array(
					'lang'    => 'ja-JP',
					'name'    => 'Mizuki',
					'label'   => 'Mizuki',
					'gender'  => 'Female',
					'engines' => array(
						'standard',
					),

				),

				array(
					'lang'    => 'ja-JP',
					'name'    => 'Takumi',
					'label'   => 'Takumi',
					'gender'  => 'Male',
					'engines' => array(
						'neural',
						'standard',
					),

				),

				array(
					'lang'    => 'ja-JP',
					'name'    => 'Kazuha',
					'label'   => 'Kazuha',
					'gender'  => 'Female',
					'engines' => array(
						'neural',
					),

				),

				array(
					'lang'    => 'ja-JP',
					'name'    => 'Tomoko',
					'label'   => 'Tomoko',
					'gender'  => 'Female',
					'engines' => array(
						'neural',
					),

				),

			),

		),

		'Korean'                  => array(
			'code'   => 'ko-KR',
			'voices' => array(
				array(
					'lang'    => 'ko-KR',
					'name'    => 'Seoyeon',
					'label'   => 'Seoyeon',
					'gender'  => 'Female',
					'engines' => array(
						'neural',
						'standard',
					),

				),

				array(
					'lang'    => 'ko-KR',
					'name'    => 'Jihye',
					'label'   => 'Jihye',
					'gender'  => 'Female',
					'engines' => array(
						'neural',
					),

				),

			),

		),

		'Norwegian'               => array(
			'code'   => 'nb-NO',
			'voices' => array(
				array(
					'lang'    => 'nb-NO',
					'name'    => 'Liv',
					'label'   => 'Liv',
					'gender'  => 'Female',
					'engines' => array(
						'standard',
					),

				),

				array(
					'lang'    => 'nb-NO',
					'name'    => 'Ida',
					'label'   => 'Ida',
					'gender'  => 'Female',
					'engines' => array(
						'neural',
					),

				),

			),

		),

		'Polish'                  => array(
			'code'   => 'pl-PL',
			'voices' => array(
				array(
					'lang'    => 'pl-PL',
					'name'    => 'Ewa',
					'label'   => 'Ewa',
					'gender'  => 'Female',
					'engines' => array(
						'standard',
					),

				),

				array(
					'lang'    => 'pl-PL',
					'name'    => 'Maja',
					'label'   => 'Maja',
					'gender'  => 'Female',
					'engines' => array(
						'standard',
					),

				),

				array(
					'lang'    => 'pl-PL',
					'name'    => 'Jacek',
					'label'   => 'Jacek',
					'gender'  => 'Male',
					'engines' => array(
						'standard',
					),

				),

				array(
					'lang'    => 'pl-PL',
					'name'    => 'Jan',
					'label'   => 'Jan',
					'gender'  => 'Male',
					'engines' => array(
						'standard',
					),

				),

				array(
					'lang'    => 'pl-PL',
					'name'    => 'Ola',
					'label'   => 'Ola',
					'gender'  => 'Female',
					'engines' => array(
						'neural',
					),

				),

			),

		),

		'Portuguese (Brazilian)'  => array(
			'code'   => 'pt-BR',
			'voices' => array(
				array(
					'lang'    => 'pt-BR',
					'name'    => 'Camila',
					'label'   => 'Camila',
					'gender'  => 'Female',
					'engines' => array(
						'neural',
						'standard',
					),

				),

				array(
					'lang'    => 'pt-BR',
					'name'    => 'Vitoria',
					'label'   => 'Vitória / Vitoria',
					'gender'  => 'Female',
					'engines' => array(
						'neural',
						'standard',
					),

				),

				array(
					'lang'    => 'pt-BR',
					'name'    => 'Ricardo',
					'label'   => 'Ricardo',
					'gender'  => 'Male',
					'engines' => array(
						'standard',
					),

				),

				array(
					'lang'    => 'pt-BR',
					'name'    => 'Thiago',
					'label'   => 'Thiago',
					'gender'  => 'Male',
					'engines' => array(
						'neural',
					),

				),

			),

		),

		'Portuguese (European)'   => array(
			'code'   => 'pt-PT',
			'voices' => array(
				array(
					'lang'    => 'pt-PT',
					'name'    => 'Ines',
					'label'   => 'Inês / Ines',
					'gender'  => 'Female',
					'engines' => array(
						'neural',
						'standard',
					),

				),

				array(
					'lang'    => 'pt-PT',
					'name'    => 'Cristiano',
					'label'   => 'Cristiano',
					'gender'  => 'Male',
					'engines' => array(
						'standard',
					),

				),

			),

		),

		'Romanian'                => array(
			'code'   => 'ro-RO',
			'voices' => array(
				array(
					'lang'    => 'ro-RO',
					'name'    => 'Carmen',
					'label'   => 'Carmen',
					'gender'  => 'Female',
					'engines' => array(
						'standard',
					),

				),

			),

		),

		'Russian'                 => array(
			'code'   => 'ru-RU',
			'voices' => array(
				array(
					'lang'    => 'ru-RU',
					'name'    => 'Tatyana',
					'label'   => 'Tatyana',
					'gender'  => 'Female',
					'engines' => array(
						'standard',
					),

				),

				array(
					'lang'    => 'ru-RU',
					'name'    => 'Maxim',
					'label'   => 'Maxim',
					'gender'  => 'Male',
					'engines' => array(
						'standard',
					),

				),

			),

		),

		'Spanish (Spain)'         => array(
			'code'   => 'es-ES',
			'voices' => array(
				array(
					'lang'    => 'es-ES',
					'name'    => 'Conchita',
					'label'   => 'Conchita',
					'gender'  => 'Female',
					'engines' => array(
						'standard',
					),

				),

				array(
					'lang'    => 'es-ES',
					'name'    => 'Lucia',
					'label'   => 'Lucia',
					'gender'  => 'Female',
					'engines' => array(
						'generative',
						'neural',
						'standard',
					),

				),

				array(
					'lang'    => 'es-ES',
					'name'    => 'Alba',
					'label'   => 'Alba',
					'gender'  => 'Female',
					'engines' => array(
						'long_form',
					),

				),

				array(
					'lang'    => 'es-ES',
					'name'    => 'Enrique',
					'label'   => 'Enrique',
					'gender'  => 'Male',
					'engines' => array(
						'standard',
					),

				),

				array(
					'lang'    => 'es-ES',
					'name'    => 'Sergio',
					'label'   => 'Sergio',
					'gender'  => 'Male',
					'engines' => array(
						'generative',
						'neural',
					),

				),

				array(
					'lang'    => 'es-ES',
					'name'    => 'Raul',
					'label'   => 'Raúl',
					'gender'  => 'Male',
					'engines' => array(
						'long_form',
					),

				),

			),

		),

		'Spanish (Mexican)'       => array(
			'code'   => 'es-MX',
			'voices' => array(
				array(
					'lang'    => 'es-MX',
					'name'    => 'Mia',
					'label'   => 'Mia',
					'gender'  => 'Female',
					'engines' => array(
						'generative',
						'neural',
						'standard',
					),

				),

				array(
					'lang'    => 'es-MX',
					'name'    => 'Andres',
					'label'   => 'Andrés',
					'gender'  => 'Male',
					'engines' => array(
						'generative',
						'neural',
					),

				),

			),

		),

		'Spanish (US)'            => array(
			'code'   => 'es-US',
			'voices' => array(
				array(
					'lang'    => 'es-US',
					'name'    => 'Lupe',
					'label'   => 'Lupe',
					'gender'  => 'Female',
					'engines' => array(
						'generative',
						'neural',
						'standard',
					),

				),

				array(
					'lang'    => 'es-US',
					'name'    => 'Penelope',
					'label'   => 'Penélope / Penelope',
					'gender'  => 'Female',
					'engines' => array(
						'standard',
					),

				),

				array(
					'lang'    => 'es-US',
					'name'    => 'Miguel',
					'label'   => 'Miguel',
					'gender'  => 'Male',
					'engines' => array(
						'standard',
					),

				),

				array(
					'lang'    => 'es-US',
					'name'    => 'Pedro',
					'label'   => 'Pedro',
					'gender'  => 'Male',
					'engines' => array(
						'generative',
						'neural',
					),

				),

			),

		),

		'Swedish'                 => array(
			'code'   => 'sv-SE',
			'voices' => array(
				array(
					'lang'    => 'sv-SE',
					'name'    => 'Astrid',
					'label'   => 'Astrid',
					'gender'  => 'Female',
					'engines' => array(
						'standard',
					),

				),

				array(
					'lang'    => 'sv-SE',
					'name'    => 'Elin',
					'label'   => 'Elin',
					'gender'  => 'Female',
					'engines' => array(
						'neural',
					),

				),

			),

		),

		'Turkish'                 => array(
			'code'   => 'tr-TR',
			'voices' => array(
				array(
					'lang'    => 'tr-TR',
					'name'    => 'Filiz',
					'label'   => 'Filiz',
					'gender'  => 'Female',
					'engines' => array(
						'standard',
					),

				),

				array(
					'lang'    => 'tr-TR',
					'name'    => 'Burcu',
					'label'   => 'Burcu',
					'gender'  => 'Female',
					'engines' => array(
						'neural',
					),

				),

			),

		),

		'Welsh'                   => array(
			'code'   => 'cy-GB',
			'voices' => array(
				array(
					'lang'    => 'cy-GB',
					'name'    => 'Gwyneth',
					'label'   => 'Gwyneth',
					'gender'  => 'Female',
					'engines' => array(
						'standard',
					),

				),

			),

		),

	);

	/**
	 * Returns LangVoices::ALL_OPTIONS
	 *
	 * @return array
	 */
	public static function get_all(): array {
		return self::ALL_OPTIONS;
	}

	/**
	 * Filters LangVoices::ALL_OPTIONS by language, region, and regional variant
	 *
	 * @param string $lang_region_code A language / region/ variant code such as fr,
	 * en-US, or en-GB-WLS.
	 * @return array
	 */
	public static function get_voices_by_lang( string $lang_region_code ): array {
		$all = self::ALL_OPTIONS;

		// If, for some weird reason, the Locale class isn't available...
		if ( ! class_exists( 'Locale' ) ) :
			$matching = array_filter(
				$all,
				function ( $lang ) use ( $lang_region_code ) {
					return $lang['code'] === $lang_region_code;
				}
			);

		else :
			$parsed   = Locale::parseLocale( $lang_region_code );
			$matching = array();

			// Filter language-voice objects by language code.
			if ( array_key_exists( 'language', $parsed ) ) :

				$matching = array_filter(
					$all,
					function ( $lang ) use ( $parsed ) {
						return strpos( $lang['code'], $parsed['language'] ) === 0;
					}
				);

			endif;

			// Further filter by region code.
			if ( array_key_exists( 'region', $parsed ) ) :
				$matching = array_filter(
					$matching,
					function ( $lang ) use ( $parsed ) {

						// Gets the locale code for the current item.
						$item_locale = Locale::parseLocale( $lang['code'] );

						return $item_locale['region'] === $parsed['region'];
					}
				);
			endif;

			// Further filter by variant, if any.
			$variants = Locale::getAllVariants( $lang_region_code );

			if ( count( $variants ) ) :

				$matching = array_filter(
					$matching,
					function ( $lang ) use ( $variants ) {
						return strpos( $lang['code'], $variants[0] ) !== false;
					}
				);

			endif;
		endif;

		return $matching;
	}

	/**
	 * Filters LangVoices::ALL_OPTIONS by the [$engines] provided
	 *
	 * @param array $engines The engine names to return.
	 * @return array
	 * @throws \InvalidArgumentException If the engine string isn't supported.
	 */
	public static function get_voices_for_engines( array $engines ): array {

		foreach ( $engines as $e ) :
			if ( ! SettingsSelectMenus::is_supported_engine( $e ) ) {
				throw new \InvalidArgumentException(
					esc_html_e(
						//phpcs:disable WordPress.WP.I18n.NonSingularStringLiteralText
						TextStrings::INVALID_ENGINE_ARGUMENT,
						//phpcs:disable WordPress.WP.I18n.NonSingularStringLiteralDomain
						Settings::ASSET_ID
					)
				);
			}
		endforeach;

		$group = self::ALL_OPTIONS;

		foreach ( $group as $lang => $value ) :
			// Return only those voices that contain the values in [ $engines ].
			$voices                   = array_filter(
				$value['voices'],
				function ( $v ) use ( $engines ) {
					if ( array_intersect( $v['engines'], $engines ) ) {
						return $v;
					}
				}
			);
			$group[ $lang ]['voices'] = array_values( $voices ); // Resets array keys.
		endforeach;

		// Filter out groups with an empty voices array.
		$group = array_filter(
			$group,
			function ( $g ) {
				return boolval( $g['voices'] );
			}
		);

		return $group;
	}
}
