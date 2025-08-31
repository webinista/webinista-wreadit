<?php

namespace Webinista\WreadIt\Aws3\Aws\Handler\GuzzleV6;

trigger_error(sprintf('Using the "%s" class is deprecated, use "%s" instead.', __NAMESPACE__ . '\GuzzleHandler', \Webinista\WreadIt\Aws3\Aws\Handler\Guzzle\GuzzleHandler::class), \E_USER_DEPRECATED);
class_alias(\Webinista\WreadIt\Aws3\Aws\Handler\Guzzle\GuzzleHandler::class, __NAMESPACE__ . '\GuzzleHandler');
