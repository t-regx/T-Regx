<?php
namespace TRegx\SafeRegex\Constants;

use function array_key_exists;

abstract class Constants
{
    public function getConstant(int $error): string
    {
        $constants = $this->getConstants();
        if (array_key_exists($error, $constants)) {
            return $constants[$error];
        }
        return $this->getDefault();
    }

    abstract protected function getConstants(): array;

    abstract protected function getDefault(): string;
}
