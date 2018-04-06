<?php

namespace App\Service\FeedReceiver;

interface FeedReceiverInterface
{
    public function getOutput(): string;
}
