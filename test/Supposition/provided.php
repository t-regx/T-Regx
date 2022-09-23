<?php

function named(array $values): array
{
    $provider = [];
    foreach ($values as $value) {
        $key = new \TRegx\CleanRegex\Internal\VisibleCharacters($value[0]);
        $provider["$key"] = $value;
    }
    return $provider;
}
