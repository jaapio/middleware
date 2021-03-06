<?php declare(strict_types=1);

namespace ApiClients\Foundation\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use React\Promise\CancellablePromiseInterface;

/**
 * Middleware, when a new request is made an instance specifically for that request is made for each request.
 */
interface MiddlewareInterface
{
    /**
     * Priority ranging from 0 to 1000. Where 1000 will be executed first on `pre` and 0 last on `pre`.
     * For `post` the order is reversed.
     *
     * @return int
     */
    public function priority(): int;

    /**
     * Return the processed $request via a fulfilled promise.
     * When implementing cache or other feature that returns a response, do it with a rejected promise.
     * If neither is possible, e.g. on some kind of failure, resolve the unaltered request.
     *
     * @param RequestInterface $request
     * @param array $options
     * @return CancellablePromiseInterface
     */
    public function pre(RequestInterface $request, array $options = []): CancellablePromiseInterface;

    /**
     * Return the processed $response via a promise.
     *
     * @param ResponseInterface $response
     * @param array $options
     * @return CancellablePromiseInterface
     */
    public function post(ResponseInterface $response, array $options = []): CancellablePromiseInterface;
}
