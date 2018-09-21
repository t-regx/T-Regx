<?php
namespace Test\Unit\TRegx\CleanRegex\Match\Groups\Strategy;

use TRegx\CleanRegex\Internal\Delimiter\DelimiterParser;
use TRegx\CleanRegex\Match\Groups\Strategy\PatternAnalyzeGroupVerifier;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class PatternAnalyzeGroupVerifierTest extends TestCase
{
    /**
     * @test
     */
    public function shouldThrowOnIntegerGroup()
    {
        // given
        $verifier = new PatternAnalyzeGroupVerifier(new DelimiterParser());

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Analyzing pattern is supported only for string group names');

        // when
        $verifier->groupExists('//', 2);
    }
}
