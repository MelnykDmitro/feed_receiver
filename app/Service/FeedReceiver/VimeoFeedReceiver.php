<?php

namespace App\Service\FeedReceiver;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Symfony\Component\Serializer\Encoder\XmlEncoder;

class VimeoFeedReceiver implements FeedReceiverInterface
{
    const URI_ENDPOINT = 'https://vimeo.com';

    protected $httpClient;

    public function __construct()
    {
        $this->httpClient = new Client([
            'base_uri' => self::URI_ENDPOINT
        ]);
    }

	public function getData(): array
	{
        $response = $this->httpClient->get('example/videos/rss');

        $responseDecoded = (new XmlEncoder())->decode((string) $response->getBody()->getContents(), 'array');

        $items = array_get($responseDecoded, 'channel.item');

        $output = $this->transformResponse($items);

        return $output;
	}

    private function transformResponse(array $items): array
    {
        return array_map(function ($item) {

            $releaseDate = Carbon::createFromFormat(Carbon::RFC1123, array_get($item, 'pubDate'))->setTime(0, 0)->timestamp;

            return [
                'title' => array_get($item, 'title'),
                'description' => array_get($item, 'description'),
                'images' => [
                    array_get($item, 'media:content.media:thumbnail.@url')
                ],
                'genres' => [], // No any information about genres in response item
                'releaseDate' => $releaseDate
            ];

        }, $items);
	}
}
