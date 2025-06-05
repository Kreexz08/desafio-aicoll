<?php

namespace App\Application;

interface QueryHandler
{
    public function handle(Query $query): mixed;
}
