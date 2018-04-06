<?php

namespace App\Service;

use App\Service\FeedReceiver\BrightcoveFeedReceiver;
use App\Service\FeedReceiver\MovieDbFeedReceiver;
use App\Service\FeedReceiver\FeedReceiverInterface;

class FeedReceiverCreator
{
    const RECEIVER_BRIGHTCOVE = 'brightcove';
    const RECEIVER_MOVIE_DB = 'movie_db';

    public static function getInstance(string $input): FeedReceiverInterface
    {
        switch ($input) {
            case self::RECEIVER_BRIGHTCOVE:
                return new BrightcoveFeedReceiver();
            case self::RECEIVER_MOVIE_DB:
                return new MovieDbFeedReceiver();
        }

        throw new \InvalidArgumentException('Wrong receiver input');
    }
}
