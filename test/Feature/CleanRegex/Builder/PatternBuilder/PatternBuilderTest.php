<?php
namespace Test\Feature\TRegx\CleanRegex\Builder\PatternBuilder;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;
use TRegx\Exception\MalformedPatternException;

class PatternBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function shouldThrowMalformedPatternException_forUndelimitedPcrePattern()
    {
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage("No ending delimiter '%' found");

        // when
        Pattern::builder()->pcre()->inject("%Foo", [])->test('bar');
    }
}
