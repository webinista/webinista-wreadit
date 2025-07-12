=== Plugin Name ===
Contributors: webinista
Donate link: https://ko-fi.com/webinista
Tags: text-to-speech
Requires at least: 6.7
Tested up to: 6.8
Stable tag: 1.0.0-beta
Requires PHP: 8.0
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html
A text-to-speech plugin for WordPress that uses Amazon Polly.

# [Webinista WreadIt](https://wreadit.webinista.com/)

**Current version:** v1.0.0-beta [See all releases](https://github.com/webinista/webinista-wreadit/releases/).
 
A WordPress plugin for creating audio versions of your posts using AWS Polly.

## Prerequisites

This plugin assumes that you are familiar with [Amazon Web Services](https://aws.amazon.com/) and that you have an account.

## License

Webinista Wreadit is licensed under the terms of the GNU General Public License, version 3. Some of its dependencies use the MIT 2.0 and Apache 2.0 licenses.

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
    GNU General Public License for more details.

## Contributing

In order to contribute, you'll need a local WordPress development environment. Try  [**wp-env**](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-env/) or [**Varying Vagrant Vagrants**](https://varyingvagrantvagrants.org/) (or VVV).

### To contribute:

1. Clone this repository into a local `wp-content/plugins` directory.
2. Enter the `webinista-wreadit` directory.
3. Run `composer install`.
4. Run `npm install`.
5. Run `npm run start` to start a development server.

Don't forget to activate the plugin from your WordPress dashboard.

As of now, this project uses the [@wordpress/scripts](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-scripts/) package. Consult its documentation for more instructions, or read the `package.json` file.

## Code of Conduct

Webinista is working on the Code of Conduct and contribution instructions. For now, the rule is: be kind and / or helpful, ideally both.
