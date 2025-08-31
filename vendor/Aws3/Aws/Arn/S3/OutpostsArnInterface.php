<?php

namespace Webinista\WreadIt\Aws3\Aws\Arn\S3;

use Webinista\WreadIt\Aws3\Aws\Arn\ArnInterface;
/**
 * @internal
 */
interface OutpostsArnInterface extends ArnInterface
{
    public function getOutpostId();
}
