<?php
namespace TRegx\SafeRegex\Guard\Strategy;

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
        if (is_array($this->subject)) {
            if (empty($this->subject)) {
                return false;
            }
            return $result === [];
        }
        return $result === null;
    }
}
