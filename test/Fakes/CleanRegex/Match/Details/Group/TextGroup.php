<?php
namespace Test\Fakes\CleanRegex\Match\Details\Group;

use Test\Utils\Fails;
use TRegx\CleanRegex\Match\Details\Group\Group;
use TRegx\CleanRegex\Match\Optional;

class TextGroup implements Group
{
    use Fails;

    /** @var string */
    private $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public function text(): string
    {
        return $this->text;
    }

    public function length(): int
    {
        throw $this->fail();
    }

    public function byteLength(): int
    {
        throw $this->fail();
    }

    public function toInt(int $base = null): int
    {
        throw $this->fail();
    }

    public function isInt(int $base = null): bool
    {
        throw $this->fail();
    }

    public function matched(): bool
    {
        return true;
    }

    public function equals(string $expected): bool
    {
        throw $this->fail();
    }

    public function name(): ?string
    {
        throw $this->fail();
    }

    public function usedIdentifier()
    {
        throw $this->fail();
    }

    public function offset(): int
    {
        throw $this->fail();
    }

    public function tail(): int
    {
        throw $this->fail();
    }

    public function byteOffset(): int
    {
        throw $this->fail();
    }

    public function byteTail(): int
    {
        throw $this->fail();
    }

    public function substitute(string $replacement): string
    {
        throw $this->fail();
    }

    public function subject(): string
    {
        throw $this->fail();
    }

    public function all(): array
    {
        throw $this->fail();
    }

    public function index(): int
    {
        throw $this->fail();
    }

    public function or(string $substitute): string
    {
        throw $this->fail();
    }

    public function map(callable $mapper): Optional
    {
        throw $this->fail();
    }
}
