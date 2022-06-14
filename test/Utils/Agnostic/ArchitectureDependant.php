<?php
namespace Test\Utils\Agnostic;

trait ArchitectureDependant
{
    /**
     * @return bool
     */
    public function isArchitecture64(): bool
    {
        // 4-bytes integers (32-bit architecture)
        // 8-bytes integers (64-bit architecture)
        return \PHP_INT_SIZE === 8;
    }

    public function onArchitecture32(array $dataProvider): array
    {
        if ($this->isArchitecture64()) {
            return [];
        }
        return $dataProvider;
    }

    public function onArchitecture64(array $dataProvider): array
    {
        if ($this->isArchitecture64()) {
            return $dataProvider;
        }
        return [];
    }
}
