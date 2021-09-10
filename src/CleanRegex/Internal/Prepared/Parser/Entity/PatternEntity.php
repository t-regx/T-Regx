<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Entity;

use TRegx\CleanRegex\Internal\Prepared\Word\PatternWord;
use TRegx\CleanRegex\Internal\Prepared\Word\Word;

trait PatternEntity
{
    public abstract function pattern();

    public function word(): Word
    {
        return new PatternWord($this->pattern());
    }
}
