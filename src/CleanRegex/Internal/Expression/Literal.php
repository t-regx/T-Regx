<?php
namespace TRegx\CleanRegex\Internal\Expression;

use TRegx\CleanRegex\Internal\AutoCapture\Pattern\PatternAutoCapture;
use TRegx\CleanRegex\Internal\Delimiter\Delimiter;
use TRegx\CleanRegex\Internal\Expression\Predefinition\DelimiterPredefinition;
use TRegx\CleanRegex\Internal\Expression\Predefinition\Predefinition;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;
use TRegx\CleanRegex\Internal\Prepared\Phrase\UnconjugatedPhrase;
use TRegx\CleanRegex\Internal\Prepared\Word\TextWord;

class Literal implements Expression
{
    /** @var PatternAutoCapture */
    private $autoCapture;
    /** @var Phrase */
    private $phrase;
    /** @var Flags */
    private $flags;

    public function __construct(PatternAutoCapture $autoCapture, string $text, Flags $flags)
    {
        $this->autoCapture = $autoCapture;
        $this->phrase = new UnconjugatedPhrase(new TextWord($text));
        $this->flags = $flags;
    }

    public function predefinition(): Predefinition
    {
        return new DelimiterPredefinition($this->autoCapture, $this->phrase, new Delimiter('/'), $this->flags);
    }
}
