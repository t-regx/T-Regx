<?php
namespace Test\Fakes\CleanRegex\Match\Details;

use Test\Fakes\CleanRegex\Internal\Model\ThrowGroupAware;
use Test\Fakes\CleanRegex\Internal\ThrowSubject;
use Test\Utils\Fails;
use TRegx\CleanRegex\Match\Details\NotMatched;

class ThrowNotMatched extends NotMatched
{
    use Fails;

    public function __construct()
    {
        parent::__construct(new ThrowGroupAware(), new ThrowSubject());
    }

    public function subject(): string
    {
        throw $this->fail();
    }

    public function groupNames(): array
    {
        throw $this->fail();
    }

    public function groupsCount(): int
    {
        throw $this->fail();
    }

    public function hasGroup($nameOrIndex): bool
    {
        throw $this->fail();
    }
}
