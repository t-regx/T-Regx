<?php
namespace Test\Fakes\CleanRegex\Internal\Prepared\Parser;

use Test\Utils\Assertion\Fails;
use TRegx\CleanRegex\Internal\Prepared\Parser\Convention;

class ThrowConvention extends Convention
{
    use Fails;

    public function __construct()
    {
        parent::__construct('');
    }

    public function lineEndings(): array
    {
        throw $this->fail();
    }
}
