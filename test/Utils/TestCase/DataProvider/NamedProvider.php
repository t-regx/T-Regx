<?php
namespace Test\Utils\TestCase\DataProvider;

use Exception;
use TRegx\CleanRegex\Internal\VisibleCharacters;

class NamedProvider
{
    private $provider = [];

    public function addEntry(string $name, array $value): void
    {
        $key = new VisibleCharacters($name);
        if (\array_key_exists("$key", $this->provider)) {
            throw new Exception("Duplicate key '$key'");
        }
        $this->provider["$key"] = $value;
    }

    public function addGroupEntry(string $group, array $value): void
    {
        $key = new VisibleCharacters($value[0]);
        $this->provider["$group, $key"] = $value;
    }

    public function toDataProvider(): array
    {
        return $this->provider;
    }
}
