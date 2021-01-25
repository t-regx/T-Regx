<?php
namespace TRegx\SafeRegex\Internal\Guard\Strategy;

use function is_array;

class PregReplaceSuspectedReturnStrategy implements SuspectedReturnStrategy
{
    /** @var string|array|mixed */
    private $subject;

    public function __construct($subject)
    {
        $this->subject = $subject;
    }

    public function isSuspected(string $methodName, $result): bool
    {
        if (!is_array($this->subject)) {
            return $result === null;
        }
        if (empty($this->subject)) {
            return false;
        }
        return $result === [];
    }
}
