<?php

namespace Webinista\WreadIt\Aws3\GuzzleHttp;

use Webinista\WreadIt\Aws3\Psr\Http\Message\MessageInterface;
interface BodySummarizerInterface
{
    /**
     * Returns a summarized message body.
     */
    public function summarize(MessageInterface $message): ?string;
}
