<?php

namespace Novvai\DBDrivers\Interfaces;

interface DBConnectionInterface
{
    public function getBy(string $query): array;
    public function execute(string $query);
}
