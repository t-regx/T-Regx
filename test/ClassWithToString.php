<?php
namespace Test;

class ClassWithToString
{
    /** @var string */
    private $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public function __toString()
    {
        return $this->text;
    }
}
