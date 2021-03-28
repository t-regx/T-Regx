<?php
namespace Test\Feature\TRegx\CleanRegex\_quoted_prepared;

use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsPattern;
use TRegx\CleanRegex\Pattern;

class PatternTest extends TestCase
{
    use AssertsPattern;

    /**
     * @test
     */
    public function shouldTest_beFalse_forNotMatching()
    {
        // given
        $maliciousCode = './\]123!@#]\Q[$%^&*()\E\\{}\\\\+[]';
        $pattern = Pattern::inject('^[@]+$', [$maliciousCode]);

        // when
        $match = $pattern->match($maliciousCode)->first();

        // then
        $this->assertSame($match, $maliciousCode);
        $this->assertSamePattern('/^[\.\/\\\\\]123\!@\#\]\\\Q\[\$%\^&\*\(\)\\\E\\\\\{\}\\\\\\\\\+\[\]]+$/', $pattern);
    }
}
