<?php
namespace Test\Integration\TRegx\CleanRegex;

use PHPUnit\Framework\TestCase;
use Test\Warnings;
use TRegx\DataProvider\DataProviders;
use TRegx\SafeRegex\Exception\MalformedPatternException;
use TRegx\SafeRegex\preg;

class PatternExceptionsTest extends TestCase
{
    use Warnings;

    /**
     * @test
     * @param callable $function
     * @param string $argument
     * @dataProvider malformedPatternExceptionFunctions
     */
    public function shouldThrowMalformedPatternException(callable $function, string $argument)
    {
        // then
        $this->expectException(MalformedPatternException::class);

        // when
        $function($argument);
    }

    function malformedPatternExceptionFunctions(): array
    {
        return DataProviders::cross([
            'match'                  => [
                function (string $pattern) {
                    preg::match($pattern, '');
                }
            ],
            'match_all'              => [
                function (string $pattern) {
                    preg::match_all($pattern, '');
                }
            ],
            'replace'                => [
                function (string $pattern) {
                    preg::replace($pattern, '', '');
                }
            ],
            'filter'                 => [
                function (string $pattern) {
                    preg::filter($pattern, '', '');
                }
            ],
            'replace_callback_array' => [
                function (string $pattern) {
                    preg::replace_callback_array([$pattern => 'strtoupper'], '');
                }
            ],
            'replace_callback'       => [
                function (string $pattern) {
                    preg::replace_callback($pattern, 'strtoupper', '');
                }
            ],
            'split'                  => [
                function (string $pattern) {
                    preg::split($pattern, '');
                }
            ],
            'grep'                   => [
                function (string $pattern) {
                    preg::grep($pattern, []);
                }
            ],
        ], \Test\DataProviders::invalidPregPatterns());
    }
}
