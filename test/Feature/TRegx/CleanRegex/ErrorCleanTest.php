<?php
namespace Test\Feature\TRegx\CleanRegex;

use PHPUnit\Framework\TestCase;
use TRegx\SafeRegex\Exception\MalformedPatternException;

class ErrorCleanTest extends TestCase
{
    /**
     * @test
     */
    public function shouldNotInfluenceFurtherChecks()
    {
        // when
        pattern('/[a-')->valid();

        // when
        $valid = pattern('/[a-z]/')->valid();

        // then
        $this->assertTrue($valid);
    }

    /**
     * @test
     */
    public function shouldNotInterfereWithFurtherMatches()
    {
        try {
            pattern('/[a-')->test("");
        } catch (MalformedPatternException $e) {
        }

        // when
        $valid = pattern('[a-z]')->test("a");

        // then
        $this->assertTrue($valid);
    }
}
