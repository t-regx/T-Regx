<?php
namespace SafeRegex\Guard;

use SafeRegex\ExceptionFactory;

class GuardedInvoker
{
    /** @var callable */
    private $callback;
    /** @var string */
    private $methodName;

    public function __construct(string $methodName, callable $callback)
    {
        $this->callback = $callback;
        $this->methodName = $methodName;
    }

    public function catch(): GuardedInvocation
    {
        $result = call_user_func($this->callback);

        return new GuardedInvocation($result, $this->exception($result));
    }

    public function exception($result)
    {
        return (new ExceptionFactory())->retrieveGlobalsAndReturn($this->methodName, $result);
    }
}
