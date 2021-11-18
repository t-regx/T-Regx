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

    public function __construct(string $comment)
    {
        $this->comment = new Chars($comment);
    }

    public function phrase(): Phrase
    {
        if ($this->comment->endsWith('\\')) {
            return new ConjugatedPhrase("#\\\n", "#\\");
        }
        return new PatternPhrase("#$this->comment");
    }
}
