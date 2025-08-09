# [Webinista WreadIt](https://wreadit.webinista.com/)

**Current version:** v1.1.1 [See all releases](https://github.com/webinista/webinista-wreadit/releases/).

A WordPress plugin for creating audio versions of your posts using Amazon Polly.

## Prerequisites

This plugin assumes that you are familiar with [Amazon Web Services](https://aws.amazon.com/) and that you have an account.

## License

Webinista WreadIt is licensed under the terms of the GNU General Public License, version 3. Some of its dependencies use the MIT 2.0 and Apache 2.0 licenses.

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.
    
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
    GNU General Public License for more details.

## Contributing

Contributions are welcome. Please read the [Contributing.md](https://github.com/webinista/webinista-wreadit/blob/main/CONTRIBUTING.md) first.

## Changelog

### 1.1.1 / 2025-08-07

#### Bug fixes

- Ensures that a default post types value gets included with form submission. (Regression introduced in version 1.1.0).

#### Enhancement

- Disables voice options that are incompatible with the selected engine.

#### Misc

- Updated Amazon SDK to version 3.352.5
- Updated @wordpress/components to version 30.1.0
- Updated @wordpress/dom-ready to version 4.28.0
- Updated @wordpress/jest-preset-default to 12.28.0
- Updated @wordpress/scripts to 30.21.0

### 1.1.0 / 2025-08-02

#### Misc

- Bump form-data from 4.0.3 to 4.0.4
- Rewrite WreadIt audio URLs in the Media Library.
- Update Wordpress JavaScript packages.
