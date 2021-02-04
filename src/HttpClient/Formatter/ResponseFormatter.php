<?php

declare(strict_types=1);

namespace DarthSoup\WhmcsApi\HttpClient\Formatter;

use Psr\Http\Message\ResponseInterface;
use RuntimeException;

final class ResponseFormatter
{
    /**
     * @param ResponseInterface $response
     * @return mixed|string
     * @throws \JsonException
     */
    public static function format(ResponseInterface $response)
    {
        $body = (string) $response->getBody();

        if (strpos($response->getHeaderLine('Content-Type'), 'application/json') === 0) {
            return json_decode($body, true, 512, JSON_THROW_ON_ERROR);
        }

        return $body;
    }

    /**
     * @param ResponseInterface $response
     * @return string|null
     * @throws \JsonException
     */
    public static function errorMessage(ResponseInterface $response): ?string
    {
        try {
            $content = self::format($response);
        } catch (RuntimeException $e) {
            return null;
        }

        if (!\is_array($content)) {
            return null;
        }

        return $content['message'] ?? null;
    }
}
