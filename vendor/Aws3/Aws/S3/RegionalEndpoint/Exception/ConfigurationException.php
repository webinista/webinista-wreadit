<?php

namespace Webinista\WreadIt\Aws3\Aws\S3\RegionalEndpoint\Exception;

use Webinista\WreadIt\Aws3\Aws\HasMonitoringEventsTrait;
use Webinista\WreadIt\Aws3\Aws\MonitoringEventsInterface;
/**
 * Represents an error interacting with configuration for sts regional endpoints
 */
class ConfigurationException extends \RuntimeException implements MonitoringEventsInterface
{
    use HasMonitoringEventsTrait;
}
