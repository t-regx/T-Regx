<?php
namespace Test\Feature\TRegx\CleanRegex;

use PHPUnit\Framework\TestCase;
use TRegx\SafeRegex\Exception\CompileSafeRegexException;

class ErrorCleanTest extends TestCase
{
    /**
     * @test
     */
    public function shouldNotInfluenceFurtherChecks()
    {
        // when
        pattern('/[a-')->is()->valid();

        // when
        $valid = pattern('/[a-z]/')->is()->valid();

        // then
        $this->assertTrue($valid);
    }

    /**
     * @test
     */
    public function shouldNotInterfereWithFurtherMatches()
    {
        try {
            pattern('/[a-')->matches("");
        } catch (CompileSafeRegexException $e) {
        }

        // when
        $valid = pattern('/[a-z]/')->matches("a");

        // then
        $this->assertTrue($valid);
    }
}
