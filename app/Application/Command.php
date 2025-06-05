<?php

namespace App\Application;

interface Command
{
    public function toArray(): array;
}
