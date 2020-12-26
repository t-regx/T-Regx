<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\_or;

use PHPUnit\Framework\TestCase;
use Test\Utils\CustomSubjectException;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Exception\NotReplacedException;

class ReplacePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturn_with()
    {
        // when
        $result = pattern('Foo')->replace('Bar')->first()->otherwiseReturning('otherwise')->with('');

        // then
        $this->assertSame('otherwise', $result);
    }

    /**
     * @test
     */
    public function shouldReturn_withReferences()
    {
        // when
        $result = pattern('Foo')->replace('Bar')->first()->otherwiseReturning('otherwise')->withReferences('');

        // then
        $this->assertSame('otherwise', $result);
    }

    /**
     * @test
     */
    public function shouldReturn_otherwise_with()
    {
        // when
        $result = pattern('Foo')
            ->replace('Bar')
            ->first()
            ->otherwise(function (string $subject) {
                $this->assertSame('Bar', $subject);
                return 'otherwise';
            })
            ->with('');

        // then
        $this->assertSame('otherwise', $result);
    }

    /**
     * @test
     */
    public function shouldThrow_otherwise_with_returnNull()
    {
        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage('Invalid otherwise() callback return type. Expected string, but null given');

        // when
        pattern('Foo')
            ->replace('Bar')
            ->first()
            ->otherwise(function (string $subject) {
                $this->assertSame('Bar', $subject);
                return null;
            })
            ->with('');
    }

    /**
     * @test
     */
    public function shouldThrow_otherwiseThrowing_with_custom()
    {
        // given
        $replacePattern = pattern('Foo')->replace('Bar')->first()->otherwiseThrowing(CustomSubjectException::class);

        // when
        try {
            $replacePattern->with('');
        } catch (CustomSubjectException $e) {
            // then
            $this->assertSame("Replacements were supposed to be performed, but subject doesn't match the pattern", $e->getMessage());
            $this->assertSame('Bar', $e->subject);
        }
    }

    /**
     * @test
     */
    public function shouldThrow_otherwiseThrowing_with()
    {
        // given
        $replacePattern = pattern('Foo')->replace('Bar')->first()->otherwiseThrowing();

        // when
        try {
            $replacePattern->with('');
        } catch (NotReplacedException $e) {
            // then
            $this->assertSame("Replacements were supposed to be performed, but subject doesn't match the pattern", $e->getMessage());
            $this->assertSame('Bar', $e->getSubject());
        }
    }

    /**
     * @test
     */
    public function shouldReturn_callback()
    {
        // given
        $replacePattern = pattern('Foo')->replace('Bar')->first();

        // when
        $result = $replacePattern->otherwiseReturning('otherwise')->callback(Functions::fail());

        // then
        $this->assertSame('otherwise', $result);
    }

    /**
     * @test
     */
    public function shouldReturn_by_map()
    {
        // given
        $replacePattern = pattern('Foo')->replace('Bar')->first();

        // when
        $result = $replacePattern->otherwiseReturning('otherwise')->by()->map([]);

        // then
        $this->assertSame('otherwise', $result);
    }

    /**
     * @test
     */
    public function shouldReturn_by_group_map()
    {
        // given
        $replacePattern = pattern('(Foo)')->replace('Bar')->first();

        // when
        $result = $replacePattern->otherwiseReturning('otherwise')->by()->group(1)->map([])->orElseThrow();

        // then
        $this->assertSame('otherwise', $result);
    }
}
