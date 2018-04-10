<?php

namespace App\Service;

use App\Service\FeedReceiver\VimeoFeedReceiver;
use App\Service\FeedReceiver\MovieDbFeedReceiver;
use App\Service\FeedReceiver\FeedReceiverInterface;

class FeedReceiverCreator
{
    const RECEIVER_VIMEO = 'vimeo';
    const RECEIVER_MOVIE_DB = 'movie_db';

    public static function getInstance(string $input): FeedReceiverInterface
    {
        switch ($input) {
            case self::RECEIVER_VIMEO:
                return new VimeoFeedReceiver();
            case self::RECEIVER_MOVIE_DB:
                return new MovieDbFeedReceiver();
        }

        throw new \InvalidArgumentException('Wrong receiver input');
    }
}
