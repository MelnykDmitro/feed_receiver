<?php

namespace App\Service\FeedReceiver;

use Carbon\Carbon;
use GuzzleHttp\Client;

class MovieDbFeedReceiver implements FeedReceiverInterface
{
	const URI_ENDPOINT = 'https://api.themoviedb.org/3/';

	protected $httpClient;

	public function __construct()
	{
		$this->httpClient = new Client([
			'base_uri' => self::URI_ENDPOINT,
			'query' => [
				'api_key' => env('THE_MOVIE_DB_API_KEY')
			]
		]);
	}

	public function getOutput(): string
	{
		$response = $this->httpClient->get('movie/popular');

		$responseContent = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);

		$genreList = $this->getGenreList();
		$output = $this->transformResponse($responseContent, $genreList);

		return json_encode($output);
	}

	private function getGenreList(): array
	{
		$response = $this->httpClient->get('genre/movie/list');

		$responseContent = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);

		return array_get($responseContent, 'genres');
	}

	private function transformResponse(array $response, array $genresList): array
	{
		$rows = array_get($response, 'results');

		return array_map(function (array $row) use ($genresList) {

			$genres = $this->getGenreNamesByIds(array_get($row, 'genre_ids'), $genresList);
			$releaseDate = Carbon::createFromFormat('Y-m-d', array_get($row, 'release_date'))->setTime(0, 0)->timestamp;

			return [
				'title' => array_get($row, 'title'),
				'description' => array_get($row, 'overview'),
				'images' => [
					array_get($row, 'poster_path'),
					array_get($row, 'backdrop_path'),
				],
				'genres' => $genres,
				'releaseDate' => $releaseDate
			];
		}, $rows);
	}

	private function getGenreNamesByIds(array $genreIds, array $genresList)
	{
		return array_map(function (int $genreId) use ($genresList) {

			// Find genre by id
			$genre = array_first($genresList, function ($genre) use ($genreId) {
				return array_get($genre, 'id') === $genreId;
			});

			return array_get($genre, 'name');
		}, $genreIds);
	}
}
