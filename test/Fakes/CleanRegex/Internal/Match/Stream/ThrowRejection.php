<?php
namespace Test\Fakes\CleanRegex\Internal\Match\Stream;

use AssertionError;
use Test\Fakes\CleanRegex\Internal\Message\ThrowMessage;
use Test\Fakes\CleanRegex\Internal\ThrowSubject;
use Test\Utils\Fails;
use TRegx\CleanRegex\Internal\Match\Rejection;

class ThrowRejection extends Rejection
{
    use Fails;

    public function __construct()
    {
        parent::__construct(new ThrowSubject(), AssertionError::class, new ThrowMessage());
    }

    public function throw(?string $exceptionClassName): void
    {
        throw $this->fail();
    }
}
