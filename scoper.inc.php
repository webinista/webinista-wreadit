<?php
declare( strict_types=1 );

return [
	// Patch files that have been processed to fix any missed or incorrectly prefixed strings.
	'patchers' => [
		static function ( string $filePath, string $prefix, string $contents ): string {

			if ( false !== strpos( $filePath, 'gcp-build/sdk/vendor/composer/autoload_real.php' ) ) {
				return str_replace(
					'spl_autoload_unregister(array(\'ComposerAutoloader',
					'spl_autoload_unregister(array(\'Webinista\\\\WreadIt\\\\Gcp\\\\ComposerAutoloader',
					$contents
				);
			}

			return $contents;
		},
	],
];
