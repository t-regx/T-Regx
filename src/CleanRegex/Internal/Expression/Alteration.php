<?php
namespace TRegx\CleanRegex\Internal\Expression;

use TRegx\CleanRegex\Internal\Delimiter\Delimiter;
use TRegx\CleanRegex\Internal\Expression\Predefinition\DelimiterPredefinition;
use TRegx\CleanRegex\Internal\Expression\Predefinition\Predefinition;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;
use TRegx\CleanRegex\Internal\Prepared\Phrase\UnconjugatedPhrase;
use TRegx\CleanRegex\Internal\Prepared\Word\AlterationWord;

class Alteration implements Expression
{
    /** @var Phrase */
    private $phrase;
    /** @var Flags */
    private $flags;

    public function __construct(array $texts, Flags $flags)
    {
        $this->phrase = new UnconjugatedPhrase(new AlterationWord($texts));
        $this->flags = $flags;
    }

    public function predefinition(): Predefinition
    {
        return new DelimiterPredefinition($this->phrase, new Delimiter('/'), $this->flags);
    }
}
