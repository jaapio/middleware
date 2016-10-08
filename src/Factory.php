<?php
declare(strict_types=1);

namespace ApiClients\Foundation\Transport;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\HandlerStack;
use React\Dns\Resolver\Resolver;
use React\EventLoop\Factory as LoopFactory;
use React\EventLoop\LoopInterface;
use React\HttpClient\Client as HttpClient;
use React\HttpClient\Factory as HttpClientFactory;
use React\Dns\Resolver\Factory as ResolverFactory;
use WyriHaximus\React\GuzzlePsr7\HttpClientAdapter;

class Factory
{
    /**
     * @param LoopInterface|null $loop
     * @param array $options
     * @return Client
     */
    public static function create(LoopInterface $loop = null, array $options = []): Client
    {
        if (!($loop instanceof LoopInterface)) {
            $loop = LoopFactory::create();
        }

        if (!isset($options[Options::DNS])) {
            $options[Options::DNS] = '8.8.8.8';
        }

        $resolver = (new ResolverFactory())->createCached($options[Options::DNS], $loop);
        $httpClient = (new HttpClientFactory())->create($loop, $resolver);

        return self::createFromReactHttpClient(
            $httpClient,
            $resolver,
            $loop,
            $options
        );
    }

    /**
     * @param HttpClient $httpClient
     * @param Resolver $resolver
     * @param LoopInterface|null $loop
     * @param array $options
     * @return Client
     */
    public static function createFromReactHttpClient(
        HttpClient $httpClient,
        Resolver $resolver,
        LoopInterface $loop = null,
        array $options = []
    ): Client {
        return self::createFromGuzzleClient(
            $loop,
            new GuzzleClient(
                [
                    'handler' => HandlerStack::create(
                        new HttpClientAdapter(
                            $loop,
                            $httpClient,
                            $resolver
                        )
                    ),
                ]
            ),
            $options
        );
    }

    /**
     * @param LoopInterface $loop
     * @param GuzzleClient $guzzle
     * @param array $options
     * @return Client
     */
    public static function createFromGuzzleClient(
        LoopInterface $loop,
        GuzzleClient $guzzle,
        array $options = []
    ): Client {
        return new Client(
            $loop,
            $guzzle,
            $options
        );
    }
}
