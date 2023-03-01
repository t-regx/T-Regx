<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template\Cluster;

use TRegx\CleanRegex\Internal\Prepared\Parser\SubpatternFlags;
use TRegx\CleanRegex\Internal\Prepared\Phrase\AtomicGroupPhrase;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;
use TRegx\CleanRegex\Internal\Prepared\Phrase\WordPhrase;
use TRegx\CleanRegex\Internal\Prepared\Word\TextWord;

class AtomicGroup implements Cluster
{
    /** @var string */
    private $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public function phrase(SubpatternFlags $flags): Phrase
    {
        return new AtomicGroupPhrase(new WordPhrase(new TextWord($this->text)));
    }

    public function suitable(string $candidate): bool
    {
        return true;
    }
}
