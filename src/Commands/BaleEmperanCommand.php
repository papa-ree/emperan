<?php

namespace Paparee\BaleEmperan\Commands;

use Illuminate\Console\Command;

class BaleEmperanCommand extends Command
{
    public $signature = 'bale-emperan';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
