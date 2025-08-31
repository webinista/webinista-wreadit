<?php

namespace Webinista\WreadIt\Aws3\GuzzleHttp;

use Webinista\WreadIt\Aws3\Psr\Http\Message\MessageInterface;
final class BodySummarizer implements BodySummarizerInterface
{
    /**
     * @var int|null
     */
    private $truncateAt;
    public function __construct(?int $truncateAt = null)
    {
        $this->truncateAt = $truncateAt;
    }
    /**
     * Returns a summarized message body.
     */
    public function summarize(MessageInterface $message): ?string
    {
        return $this->truncateAt === null ? Psr7\Message::bodySummary($message) : Psr7\Message::bodySummary($message, $this->truncateAt);
    }
}
