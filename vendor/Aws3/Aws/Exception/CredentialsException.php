<?php

namespace Webinista\WreadIt\Aws3\Aws\Exception;

use Webinista\WreadIt\Aws3\Aws\HasMonitoringEventsTrait;
use Webinista\WreadIt\Aws3\Aws\MonitoringEventsInterface;
class CredentialsException extends \RuntimeException implements MonitoringEventsInterface
{
    use HasMonitoringEventsTrait;
}
