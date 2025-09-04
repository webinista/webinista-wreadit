<?php

namespace Webinista\WreadIt\Aws3\Aws\Api\ErrorParser;

use Webinista\WreadIt\Aws3\Aws\Api\Parser\JsonParser;
use Webinista\WreadIt\Aws3\Aws\Api\Service;
use Webinista\WreadIt\Aws3\Aws\Api\StructureShape;
use Webinista\WreadIt\Aws3\Aws\CommandInterface;
use Webinista\WreadIt\Aws3\Psr\Http\Message\ResponseInterface;
/**
 * Parses JSON-REST errors.
 */
class RestJsonErrorParser extends AbstractErrorParser
{
    use JsonParserTrait;
    private $parser;
    public function __construct(?Service $api = null, ?JsonParser $parser = null)
    {
        parent::__construct($api);
        $this->parser = $parser ?: new JsonParser();
    }
    public function __invoke(ResponseInterface $response, ?CommandInterface $command = null)
    {
        $data = $this->genericHandler($response);
        // Merge in error data from the JSON body
        if ($json = $data['parsed']) {
            $data = array_replace($json, $data);
        }
        // Correct error type from services like Amazon Glacier
        if (!empty($data['type'])) {
            $data['type'] = strtolower($data['type']);
        }
        // Retrieve error message directly
        $data['message'] = $data['parsed']['message'] ?? $data['parsed']['Message'] ?? null;
        $this->populateShape($data, $response, $command);
        return $data;
    }
}
