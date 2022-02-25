<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template;

use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;
use TRegx\CleanRegex\Internal\Prepared\Phrase\UnconjugatedPhrase;
use TRegx\CleanRegex\Internal\Prepared\Word\AlterationWord;
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

    public function phrase(): Phrase
    {
        return new UnconjugatedPhrase(new AlterationWord($this->figures));
    }

    public function type(): Type
    {
        return new ValueType($this->figures);
    }
}
