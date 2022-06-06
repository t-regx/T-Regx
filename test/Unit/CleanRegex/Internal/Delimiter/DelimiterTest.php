<?php
namespace Test\Unit\CleanRegex\Internal\Delimiter;

use PHPUnit\Framework\TestCase;
use Test\Fakes\CleanRegex\Internal\Prepared\Word\ConjugatedOnlyPhrase;
use TRegx\CleanRegex\Internal\Delimiter\Delimiter;
use TRegx\CleanRegex\Internal\Flags;

/**
 * @covers \TRegx\CleanRegex\Internal\Delimiter\Delimiter
 */
class DelimiterTest extends TestCase
{
    /**
     * @test
     */
    public function shouldDelimit()
    {
        // given
        $delimiter = new Delimiter('#');

        // when
        $delimited = $delimiter->delimited(new ConjugatedOnlyPhrase('foo/bar'), new Flags('i'));

        // then
        $this->assertSame('#foo/bar#i', $delimited);
    }
}
