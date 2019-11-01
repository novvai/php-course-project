<?php

namespace Novvai\Interfaces;

interface SessionInterface
{
    public function destroy();
    public function has(string $key): bool;
    public function get(string $dottedPath);
    public function add(string $key, $value): SessionInterface;
    public function flash(string $key, $value): SessionInterface;
}
