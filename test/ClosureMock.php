<?php
namespace Test;

class ClosureMock
{
    /** @var callable */
    private $callback;

    /** @var bool */
    private $isCalled = false;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function __invoke()
    {
        $this->isCalled = true;
        $param_arr = func_get_args();
        return call_user_func_array($this->callback, $param_arr);
    }

    public function isCalled()
    {
        return $this->isCalled;
    }
}
