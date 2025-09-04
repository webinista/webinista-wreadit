# [Webinista WreadIt](https://wreadit.webinista.com/)

**Current version:** v1.2.0 [See all releases](https://github.com/webinista/webinista-wreadit/releases/).

A WordPress plugin for creating audio versions of your posts using Amazon Polly.

## Prerequisites

This plugin assumes that you are familiar with [Amazon Web Services](https://aws.amazon.com/) and that you have an account. You will need:

- An Amazon Simple Storage Service (S3) bucket.
- An IAM user with full access to Polly and read/write access to the S3 bucket.
- A key ID and Secret Access Key for the IAM user.

This plugin connects to Amazon Polly, and Amazon S3. Clicking the _Generate Audio Version_ button sends the following data to Amazon Polly:

- The title and text of the blog post.
- The slug of the blog post.
- The _display name_ of the post's author. (Manage using the _Display name publicly as_ setting on the _Edit User_ screen.)
- Your AWS Key ID.
- Your Secret Access Key.
- The name of your S3 bucket.

Read Amazon's [Polly FAQs](https://aws.amazon.com/polly/faqs/#topic-2),
general [Data Privacy FAQs](https://aws.amazon.com/compliance/data-privacy-faq/), and [AWS Privacy Notice](https://aws.amazon.com/privacy/) to understand how Amazon uses your data. Read the [AWS Customer Agreement](https://aws.amazon.com/agreement/) and [AWS Service Terms](https://aws.amazon.com/service-terms/) to understand your rights and obligations with regard to Amazon's services.

Amazon Polly requires your Key ID and Secret Access Key in order to authenticate the request. Amazon Polly saves the generated audio file to your S3 bucket.

Webinista WreadIt uses [S3's virtual hosting](https://docs.aws.amazon.com/AmazonS3/latest/userguide/VirtualHosting.html
). URLs for audio files use the pattern shown below, unless you've set a custom domain.

`https://<bucket name>.s3.<region code>.amazonaws.com/<your optional prefix/><file name>`

For example, if your bucket name is `myblogsaudio`, your bucket region is `us-east-2`, and you've set a `media/` prefix, your audio URLs will begin with  `https://myblogsaudio.s3.us-east-2.amazonaws.com/media/`.

File names begin with the slug of each blog post. Amazon Polly also appends a unique identifier to the name.

_Audio files must be publicly readable_ to be available to your listeners.

## Installation

1. Upload the `webinista-wreadit` folder to your `/wp-content/plugins/` directory. You can also upload the entire `.zip` file via the plugin page of WordPress by clicking 'Add New' and selecting the zip from your computer.
2. Activate the plugin
3. Enter your credentials in the appropriate fields.


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

### 1.2.0 / 2025-09-03

#### Enhancement

- Removed AWS SDK in favor of a subset of the SDK that's been scoped to the project.
  - See https://github.com/webinista/webinista-wreadit/issues/18

#### Misc

- Updated Amazon SDK to version 3.356.10
- Updated @wordpress/jest-preset-default to 12.30.0
- Updated @wordpress/scripts to 30.23.0
- Updated @wordpress/components to 30.3.0
- Updated @wordpress/dom-ready to 4.30.0

### 1.1.1 / 2025-08-09

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
