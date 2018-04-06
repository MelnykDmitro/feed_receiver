<?php

namespace App\Http\Controllers;

use App\Service\FeedReceiverCreator;

class FeedController extends Controller
{
	public function __invoke($input = FeedReceiverCreator::RECEIVER_MOVIE_DB)
	{
		$receiver = FeedReceiverCreator::getInstance($input);
		return $receiver->getOutput();
    }
}
