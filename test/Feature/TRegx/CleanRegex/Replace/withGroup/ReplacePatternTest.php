<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\withGroup;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\CleanRegex\NonexistentGroupException;

class ReplacePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReplaceWithGroup_indexed()
    {
        // when
        $result = pattern('https?://(\w+)\.com')
            ->replace('Links: https://google.com and http://facebook.com')
            ->all()
            ->by()
            ->group(1)
            ->orThrow();

        // then
        $this->assertEquals('Links: google and facebook', $result);
    }

    /**
     * @test
     */
    public function shouldReplaceWithGroup_named()
    {
        // when
        $result = pattern('https?://(?<domain>\w+)\.com')
            ->replace('Links: https://google.com and http://facebok.com')
            ->all()
            ->by()
            ->group('domain')
            ->orThrow();

        // then
        $this->assertEquals('Links: google and facebook', $result);
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidGroup()
    {
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Group name must be an alphanumeric string starting with a letter, given: '2group'");

        // when
        pattern('https?://(\w+)\.com')
            ->replace('Links: https://google.com and http://facebook.com')
            ->all()
            ->by()
            ->group('2group')
            ->orReturn('');
    }

    /**
     * @test
     * @dataProvider nonexistentGroups
     * @param $group
     */
    public function shouldThrowForNonExistentGroup($group)
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Group name must be an alphanumeric string starting with a letter, given: '$group'");

        // when
        pattern('https?://(\w+)\.com')
            ->replace('Links: https://google.com and http://facebook.com')
            ->all()
            ->by()
            ->group($group)
            ->orReturn('');
    }

    function nonexistentGroups(): array
    {
        return [
            ['missing'],
            [40],
        ];
    }

    /**
     * @test
     */
    public function shouldThrowForNotMatchedGroup()
    {
        // then
        $this->expectException(CustomException::class);
        $this->expectExceptionMessage('xd');

        // when
        pattern('(https?)://(\w+)\.com')
            ->replace('Links: https://google.com and ://facebook.com')
            ->all()
            ->by()
            ->group(1)
            ->orThrow(CustomException::class);
    }
}
