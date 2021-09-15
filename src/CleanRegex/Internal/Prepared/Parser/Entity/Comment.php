<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Entity;

use TRegx\CleanRegex\Internal\Chars;
use TRegx\CleanRegex\Internal\Prepared\Phrase\ConjugatedPhrase;
use TRegx\CleanRegex\Internal\Prepared\Phrase\PatternPhrase;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;

class Comment implements Entity
{
    use TransitiveFlags;

    /** @var Chars */
    private $comment;
    /** @var bool */
    private $closed;

    public function __construct(string $comment, bool $closed)
    {
        $this->comment = new Chars($comment);
        $this->closed = $closed;
    }

    public function phrase(): Phrase
    {
        if ($this->closed) {
            return new PatternPhrase("#$this->comment\n");
        }
        if ($this->comment->endsWith('\\')) {
            return new ConjugatedPhrase("#\\\n", "#\\");
        }
        return new PatternPhrase("#$this->comment");
    }
}
