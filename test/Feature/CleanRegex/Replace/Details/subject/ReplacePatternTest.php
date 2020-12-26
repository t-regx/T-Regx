<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\Details\subject;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\ReplaceDetail;

class ReplacePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetSubject()
    {
        // given
        pattern('(?<matched>Foo)(?<unmatched>Bar)?')
            ->replace('Hello:Foo')
            ->first()
            ->callback(function (ReplaceDetail $detail) {
                // given
                $matched = $detail->group('matched');
                $unmatched = $detail->group('unmatched');

                // when
                $matchedSubject = $matched->subject();
                $unmatchedSubject = $unmatched->subject();

                // then
                $this->assertSame('Hello:Foo', $matchedSubject);
                $this->assertSame('Hello:Foo', $unmatchedSubject);

                // cleanup
                return '';
            });
    }
}
