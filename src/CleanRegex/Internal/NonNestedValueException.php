<?php
namespace TRegx\CleanRegex\Internal;

use Exception;
use TRegx\CleanRegex\Internal\Type\Type;

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
