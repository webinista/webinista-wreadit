#!/usr/bin/env bash

set -e

# if [ ! -d ./webinista-wreadit ]; then
#    echo 'This script must be run from the repository root.'
#    exit 1
# fi

for PROG in composer find sed unzip
do
	which ${PROG}
	if [ 0 -ne $? ]
	then
		echo "${PROG} not found in path."
		exit 1
	fi
done

REPO_ROOT=${PWD}
TMP_ROOT="${REPO_ROOT}/aws3-build"
TARGET_DIR="${REPO_ROOT}/vendor/Aws3"
SOURCE_ZIP="https://docs.aws.amazon.com/aws-sdk-php/v3/download/aws.zip"

if [ -d "${TMP_ROOT}" ]; then
    rm -rf "${TMP_ROOT}"
fi;

mkdir "${TMP_ROOT}"
cd "${TMP_ROOT}"

function log_step() {
    echo
    echo
    echo ${1}
    echo
    echo
}

log_step "Install the latest v3 of the AWS SDK"
mkdir sdk
(
    # Performed in aws3-build
    cd sdk
    curl -sL ${SOURCE_ZIP} -o aws.zip
    unzip aws.zip
    rm aws.zip

    # Delete everything from the SDK except for S3, CloudFront and Common files.
    find Aws/ -mindepth 1 -maxdepth 1 -type d \
      ! -name Auth \
      ! -name S3 \
      ! -name Connect \
      ! -name Polly \
      ! -name Api \
      ! -name Credentials \
      ! -name Configuration \
      ! -name Identity \
      ! -name Iam \
      ! -name Crypto \
      ! -name data \
      ! -name Endpoint \
      ! -name EndpointV2 \
      ! -name EndpointDiscovery \
      ! -name Arn \
      ! -name Exception \
      ! -name Handler \
      ! -name Multipart \
      ! -name Signature \
      ! -name ClientSideMonitoring \
      ! -name Sts \
      ! -name Retry \
      ! -name DefaultsMode \
      ! -name Token \
      -exec rm -rf {} +

    # Delete SDK /data subfolders that are not known to be used by S3 or CloudFront.
    find Aws/data/ -mindepth 1 -maxdepth 1 -type d \
      ! -name s3 \
      ! -name cloudfront \
      ! -name sts \
      ! -name polly \
      -exec rm -rf {} +

    # Remove polyfill, tests & docs
    find . -type d -iname tests -exec rm -rf {} +
    find . -type d -iname docs -exec rm -rf {} +

    # Remove unused classes from the autoloader's classmap.
    cat aws-autoloader.php | grep '__DIR__' | sed "s/^.*__DIR__ . '\///" | cut -d"'" -f1 | while read PHP_FILE_PATH
    do
      if [ ! -f $PHP_FILE_PATH ]
      then
        echo $PHP_FILE_PATH >> remove.list
      fi
    done
    if [ -f remove.list ]
    then
      cat aws-autoloader.php | grep -v -f remove.list > aws-autoloader.new && mv aws-autoloader.new aws-autoloader.php
      # && rm remove.list
    fi
)

log_step "Run the prefixer, adding our namespace prefix" # Prefixed files are written into the ./sdk_prefixed directory.
${REPO_ROOT}/vendor/bin/php-scoper add-prefix --config=${REPO_ROOT}/scoper.inc.php --prefix="Webinista\\WreadIt\\Aws3" --output-dir=sdk_prefixed sdk/

(
   cd sdk_prefixed
    echo ${PWD}
    rm -rf composer

    # Set the locale to prevent sed errors from characters with different encoding.
    export LC_ALL=C
    # Perform regex search replace to clean up any missed replacements in string literals (1 literal backslash = 4 in the command)

    OS_NAME=`uname -s`
    if [ "Darwin" = "${OS_NAME}" ]
    then
		  find . -type f -name "*.php" -print0 | xargs -0 sed -i '' -E "s:'(Aws|GuzzleHttp|Psr|JmesPath|Symfony)\\\\\\\\:'Webinista\\\\\\\\WreadIt\\\\\\\\Aws3\\\\\\\\\1\\\\\\\\:g"
		  find . -type f -name "*.php" -print0 | xargs -0 sed -i '' -E "s:'\\\\\\\\(Aws|GuzzleHttp|Psr|JmesPath|Symfony)\\\\\\\\:'Webinista\\\\\\\\WreadIt\\\\\\\\Aws3\\\\\\\\\1\\\\\\\\:g"
		  find . -type f -name "*.php" -print0 | xargs -0 sed -i '' -E "s:\"(Aws|GuzzleHttp|Psr|JmesPath|Symfony)\\\\\\\\:\"Webinista\\\\\\\\WreadIt\\\\\\\\Aws3\\\\\\\\\1\\\\\\\\:g"
    else
		  find . -type f -name "*.php" -print0 | xargs -0 sed -i'' -E "s:'(Aws|GuzzleHttp|Psr|JmesPath|Symfony)\\\\\\\\:'Webinista\\\\\\\\WreadIt\\\\\\\\Aws3\\\\\\\\\1\\\\\\\\:g"
		  find . -type f -name "*.php" -print0 | xargs -0 sed -i'' -E "s:'\\\\\\\\(Aws|GuzzleHttp|Psr|JmesPath|Symfony)\\\\\\\\:'Webinista\\\\\\\\WreadIt\\\\\\\\Aws3\\\\\\\\\1\\\\\\\\:g"
		  find . -type f -name "*.php" -print0 | xargs -0 sed -i'' -E "s:\"(Aws|GuzzleHttp|Psr|JmesPath|Symfony)\\\\\\\\:\"Webinista\\\\\\\\WreadIt\\\\\\\\Aws3\\\\\\\\\1\\\\\\\\:g"
    fi
)

# Delete the target directory if it exists.
if [ -d "${TARGET_DIR}" ]; then
    rm -rf "${TARGET_DIR}"
fi

# Move the prefixed SDK files to the plugin's vendor directory where they are referenced.
 mv sdk_prefixed "${TARGET_DIR}"

# Clean up the temporary working directory.
 rm -rf "${TMP_ROOT}"

log_step "Done!"
