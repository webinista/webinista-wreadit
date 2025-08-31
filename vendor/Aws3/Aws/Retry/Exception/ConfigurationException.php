<?php

namespace Webinista\WreadIt\Aws3\Aws\Retry\Exception;

use Webinista\WreadIt\Aws3\Aws\HasMonitoringEventsTrait;
use Webinista\WreadIt\Aws3\Aws\MonitoringEventsInterface;
/**
 * Represents an error interacting with retry configuration
 */
class ConfigurationException extends \RuntimeException implements MonitoringEventsInterface
{
    use HasMonitoringEventsTrait;
}
