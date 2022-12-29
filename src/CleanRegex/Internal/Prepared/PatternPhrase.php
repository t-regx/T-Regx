<?php
namespace TRegx\CleanRegex\Internal\Prepared;

use TRegx\CleanRegex\Internal\AutoCapture\Group\GroupAutoCapture;
use TRegx\CleanRegex\Internal\Prepared\Pattern\StringPattern;
use TRegx\CleanRegex\Internal\Prepared\Phrase\CompositePhrase;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;
use TRegx\CleanRegex\Internal\Prepared\Placeholders\Placeholders;

class PatternPhrase
{
    /** @var PatternEntities */
    private $entities;
    /** @var Placeholders */
    private $placeholders;

    public function __construct(GroupAutoCapture $autoCapture, StringPattern $pattern, Placeholders $placeholders)
    {
        $this->entities = new PatternEntities($pattern, $autoCapture, $placeholders);
        $this->placeholders = $placeholders;
    }

    public function phrase(): Phrase
    {
        $phrases = $this->entities->phrases();
        $this->placeholders->meetExpectation();
        return new CompositePhrase($phrases);
    }
}
