<?php
namespace Test\Utils\Assertion;

use Exception;
use PHPUnit\Framework\Assert;
use Test\Utils\Classes\ExampleException;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\EmptyOptionalException;
use TRegx\CleanRegex\Match\Optional;

trait AssertsOptional
{
    public function assertOptionalEmpty(Optional $optional)
    {
        $this->assertIsEmpty($optional);
        $this->assertIsEmpty($optional->map(Functions::fail()));
    }

    private function assertIsEmpty(Optional $optional): void
    {
        Assert::assertSame('Foo', $optional->orReturn('Foo'));
        Assert::assertSame('Bar', $optional->orReturn('Bar'));
        Assert::assertSame('Foo', $optional->orElse(Functions::constant('Foo')));
        Assert::assertSame('Bar', $optional->orElse(Functions::constant('Bar')));
        $optional->orElse(Functions::assertArgumentless());
        $this->assertThrows(ExampleException::class, function () use ($optional) {
            $optional->orThrow(new ExampleException());
        });
        $this->assertThrows(EmptyOptionalException::class, function () use ($optional) {
            $optional->orThrow(new EmptyOptionalException());
        });
        $this->assertThrows(EmptyOptionalException::class, function () use ($optional) {
            $optional->get();
        });
    }

    private function assertThrows(string $className, callable $function): void
    {
        try {
            $function();
            Assert::fail();
        } catch (Exception $exception) {
            Assert::assertSame($className, \get_class($exception));
        }
    }
}
