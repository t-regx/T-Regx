<?php
namespace Test\Utils\Impl;

use TRegx\CleanRegex\Internal\Factory\Optional\OptionalWorker;
use TRegx\CleanRegex\Internal\Factory\Worker\StreamWorker;

class ThrowWorker implements StreamWorker
{
    /** @var \Throwable */
    private $fluent;
    /** @var \Throwable */
    private $subject;

    private function __construct(?\Throwable $fluent, ?\Throwable $subject)
    {
        $this->fluent = $fluent ?? self::defaultException();
        $this->subject = $subject ?? self::defaultException();
    }

    public static function fluent(\Throwable $throwable): self
    {
        return new self($throwable, null);
    }

    public static function subject(\Throwable $throwable): self
    {
        return new self(null, $throwable);
    }

    public static function none(): self
    {
        return new self(null, null);
    }

    private static function defaultException(): \Exception
    {
        return new \Exception("This exception wasn't supposed to be thrown");
    }

    public function undecorateWorker(): StreamWorker
    {
        return $this;
    }

    public function noFirst(): OptionalWorker
    {
        return new ConstantThrowOptionalWorker($this->fluent);
    }

    public function noNth(int $nth, int $total): OptionalWorker
    {
        return new ConstantThrowOptionalWorker($this->fluent);
    }

    public function unmatchedFirst(): OptionalWorker
    {
        return new ConstantThrowOptionalWorker($this->subject);
    }

    public function unmatchedNth(int $nth): OptionalWorker
    {
        return new ConstantThrowOptionalWorker($this->subject);
    }
}
