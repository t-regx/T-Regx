<?php
namespace Test\Fakes\CleanRegex\Internal\Factory\Optional;

use AssertionError;
use Test\Fakes\CleanRegex\Internal\Messages\ThrowMessage;
use Test\Fakes\CleanRegex\Internal\Model\ThrowGroupAware;
use Test\Fakes\CleanRegex\Internal\ThrowSubject;
use Throwable;
use TRegx\CleanRegex\Internal\Factory\Optional\NotMatchedOptionalWorker;
use TRegx\CleanRegex\Match\Details\NotMatched;

class ThrowOptionalWorker extends NotMatchedOptionalWorker
{
    public function __construct()
    {
        parent::__construct(new ThrowMessage(), new ThrowSubject(), new NotMatched(new ThrowGroupAware(), new ThrowSubject()), '');
    }

    public function arguments(): array
    {
        throw $this->fail();
    }

    public function throwable(?string $exceptionClassname): Throwable
    {
        throw $this->fail();
    }

    private function fail(): Throwable
    {
        return new AssertionError("Failed to assert that OptionalWorker wasn't used");
    }
}
