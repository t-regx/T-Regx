<?php
namespace TRegx\CleanRegex\Internal;

use Exception;

class NonNestedValueException extends Exception
{
    /** @var Type */
    private $type;

    public function __construct(Type $type)
    {
        parent::__construct();
        $this->type = $type;
    }

    public function getType(): Type
    {
        return $this->type;
    }
}
