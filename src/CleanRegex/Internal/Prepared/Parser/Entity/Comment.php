<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Entity;

use TRegx\CleanRegex\Internal\Prepared\Phrase\ConjugatedPhrase;
use TRegx\CleanRegex\Internal\Prepared\Phrase\PatternPhrase;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;

class Comment implements Entity
{
    use TransitiveFlags;

    /** @var string */
    private $comment;

    public function __construct(string $comment)
    {
        $this->comment = $comment;
    }

    public function phrase(): Phrase
    {
        if ($this->endsWithBlackSlash()) {
            return new ConjugatedPhrase("#\\\n", "#\\");
        }
        return new PatternPhrase("#$this->comment");
    }

    private function endsWithBlackSlash(): bool
    {
        if ($this->comment === '') {
            return false;
        }
        return $this->comment[-1] === '\\';
    }
}
