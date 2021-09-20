<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template;

use TRegx\CleanRegex\Internal\Prepared\Word\AlterationWord;
use TRegx\CleanRegex\Internal\Prepared\Word\Word;
use TRegx\CleanRegex\Internal\Type\Type;
use TRegx\CleanRegex\Internal\Type\ValueType;

class AlterationToken implements Token
{
    use DelimiterAgnostic;

    /** @var array */
    private $figures;

    public function __construct(array $figures)
    {
        $this->figures = $figures;
    }

    public function word(): Word
    {
        return new AlterationWord($this->figures);
    }

    public function type(): Type
    {
        return new ValueType($this->figures);
    }
}
