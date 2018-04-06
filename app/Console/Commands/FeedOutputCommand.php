<?php

namespace App\Console\Commands;

use App\Service\FeedReceiverCreator;
use Illuminate\Console\Command;

class FeedOutputCommand extends Command
{
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $signature = "feed:output";

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = "Output feed with specified input";

	public function handle()
	{
		$input = $this->ask('Specify input (default is The Movie DB)', FeedReceiverCreator::RECEIVER_MOVIE_DB);

		$receiver = FeedReceiverCreator::getInstance($input);

		$this->line($receiver->getOutput());
	}
}
