<?php
namespace Test\Supposition\lineEndings;

use TRegx\CleanRegex\Internal\VisibleCharacters;

class Ending
{
    /** @var string */
    private $name;
    /** @var EndingsMap */
    private $endings;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->endings = new EndingsMap();
    }

    public function name(): string
    {
        return $this->name;
    }

    public function ending(): string
    {
        return $this->endings->ending($this->name);
    }

    public function __toString(): string
    {
        return new VisibleCharacters($this->ending());
    }
}
