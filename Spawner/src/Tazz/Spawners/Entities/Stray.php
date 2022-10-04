<?php

namespace Tazz\Spawners\Entities;

class Stray extends Skeleton {

    public const NETWORK_ID = self::STRAY;

    public function getName(): string{
        return "Stray";
    }
}