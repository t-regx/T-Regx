<?php
namespace TRegx\CleanRegex\Internal\Prepared\Quotable;

class RawQuotable implements Quotable
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
