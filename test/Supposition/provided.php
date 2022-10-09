<?php

use Test\Utils\TestCase\DataProvider\NamedProvider;

function provided(array $values): array
{
    $provider = [];
    foreach ($values as $value) {
        $provider[$value] = [$value];
    }
    return $provider;
}

function named(array $values): array
{
    $provider = new NamedProvider();
    foreach ($values as $key => $value) {
        if (\is_array($value[0])) {
            foreach ($value as $groupValue) {
                $provider->addGroupEntry($key, $groupValue);
            }
        } else {
            $provider->addEntry($value[0], $value);
        }
    }
    return $provider->toDataProvider();
}
