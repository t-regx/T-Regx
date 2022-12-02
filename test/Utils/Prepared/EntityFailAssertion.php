<?php
namespace Test\Utils\Prepared;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Feed;
use TRegx\CleanRegex\Internal\Prepared\Parser\PcreParser;
use TRegx\CleanRegex\Internal\Prepared\Parser\SubpatternFlags;

class EntityFailAssertion
{
    /** @var TestCase */
    private $testCase;
    /** @var array */
    private $consumers;

    public function __construct(TestCase $testCase, array $consumers)
    {
        $this->testCase = $testCase;
        $this->consumers = $consumers;
    }

    public function assertPatternFails(string $pattern, string $flags = ''): void
    {
        $parser = new PcreParser(new Feed($pattern), SubpatternFlags::from(new Flags($flags)), $this->consumers);
        $this->testCase->expectException(InternalCleanRegexException::class);
        $parser->entities();
    }
}
