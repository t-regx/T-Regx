<?php
namespace TRegx\CleanRegex\Internal\Prepared\Quoteable;

class RawQuoteable implements Quoteable
{
    /** @var string */
    private $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function quote(string $delimiter): string
    {
        return $this->value;
    }
}
