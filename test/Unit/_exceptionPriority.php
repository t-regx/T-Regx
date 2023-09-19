<?php
namespace Test\Unit;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Regex\GroupException;
use Regex\Pattern;
use function Test\Fixture\Functions\catching;

class _exceptionPriority extends TestCase
{
    /**
     * @test
     */
    public function invalidOverRuntime()
    {
        $pattern = new Pattern('(*NO_JIT)(*LIMIT_RECURSION=3)((((motive))))');
        catching(fn() => $pattern->searchGroup('Fear cuts deeper than swords', '2malformed'))
            ->assertException(InvalidArgumentException::class)
            ->assertMessage("Group name must be an alphanumeric string, not starting with a digit, given: '2malformed'.");
    }

    /**
     * @test
     */
    public function missingGroupOverMatchError()
    {
        $pattern = new Pattern('(\d+\d+)+3');
        $subject = '11111111111111111111 3';
        catching(fn() => $pattern->searchGroup($subject, 'missing'))
            ->assertException(GroupException::class)
            ->assertMessage("Capturing group does not exist: 'missing'.");
    }
}
