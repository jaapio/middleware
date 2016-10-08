<?php declare(strict_types=1);

namespace ApiClients\Foundation\Transport;

use Evenement\EventEmitterInterface;
use Evenement\EventEmitterTrait;
use Psr\Http\Message\ResponseInterface;

final class Response implements EventEmitterInterface
{
    use EventEmitterTrait;

    /**
     * @var string
     */
    private $body;

    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * Response constructor.
     * @param string $body
     * @param ResponseInterface $response
     */
    public function __construct($body, ResponseInterface $response)
    {
        $this->body = $body;
        $this->response = $response;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}
