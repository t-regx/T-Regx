<?php
namespace Test\Unit\CleanRegex\Match\Groups\Strategy;

use CleanRegex\Internal\Delimiter\DelimiterParser;
use CleanRegex\Match\Groups\Strategy\PatternAnalyzeGroupVerifier;
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
