<?php
namespace Test\Unit\TRegx\CleanRegex\Analyze\Simplify\Set;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Analyze\Simplify\Posix\PosixClassSimplifier;

class PosixClassSimplifierTest extends TestCase
{
    /**
     * @test
     * @dataProvider inputAndOutput
     * @param array $input
     * @param array $output
     */
    public function shouldGetAll(array $input, array $output)
    {
        // given
        $set = new PosixClassSimplifier($input);

        // when
        $all = $set->all();

        // then
        $this->assertEquals($output, $all);
    }

    public function inputAndOutput()
    {
        return [
            // duplicates
            [['aba'], ['aba']],
            [['.', '.'], ['.']],
            [['a', 'b', 'a'], ['ab']],
            [['1-98'], ['1-9']],
            [['81-'], ['81-']],
            [['a-za', 'a'], ['a-z']],
            [['\d', '0-9'], ['\d']],
            [['0-9', '\d'], ['\d']],
            [['5-76'], ['5-7']],
            [['a-a'], ['a']],
            [['9-9'], ['9']],

            // control characters
            [['^^'], ['^^']],
            [['^^^'], ['^^']],
            [['-0-9-'], ['-0-9']],
            [['-0-9'], ['-0-9']],
            [['0-9-'], ['0-9-']],
            [['^-0-9-'], ['-0-9']],
            [['^-0-9'], ['-0-9']],
            [['^0-9-'], ['0-9-']],
            [['0-9-a-z'], ['0-9-a-z']],
            [['0-9a-z-'], ['0-9a-z-']],
            [['-0-9-a-z'], ['-0-9a-z']],
            [['0-9-a-z-'], ['0-9-a-z']],
        ];
    }

    /**
     * @test
     * @dataProvider inputAndOutput
     * @param array $input
     * @param array $output
     */
    public function shouldGetAll_negated(array $input, array $output)
    {
        // given
        $negated = array_merge(['^'], $input);
        $set = new PosixClassSimplifier($negated);

        // when
        $all = $set->all();

        // then
        $this->assertEquals($output, $all);
    }
}
