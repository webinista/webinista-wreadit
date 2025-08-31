<?php

namespace Webinista\WreadIt\Aws3\Aws;

use Webinista\WreadIt\Aws3\Psr\Http\Message\ResponseInterface;
interface ResponseContainerInterface
{
    /**
     * Get the received HTTP response if any.
     *
     * @return ResponseInterface|null
     */
    public function getResponse();
}
