<?php
namespace Test\Fakes\CleanRegex\Internal\Prepared\Parser;

use TRegx\CleanRegex\Internal\Prepared\Parser\Convention;

class ConstantConvention extends Convention
{
    /** @var string */
    private $ending;

    public function __construct(string $ending)
    {
        parent::__construct('');
        $this->ending = $ending;
    }

    public function lineEndings(): array
    {
        return [$this->ending];
    }
}
