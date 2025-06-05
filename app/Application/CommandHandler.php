<?php

namespace App\Application;

interface CommandHandler
{
    public function handle(Command $command);
}
