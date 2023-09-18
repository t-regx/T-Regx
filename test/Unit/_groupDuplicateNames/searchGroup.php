<?php
namespace Test\Unit\_groupDuplicateNames;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;

/*
 * PCRE and PHP allow groups to have duplicate names, 
 * provided that modifier /J is set, or internal option 
 * (?J) is added.
 * 
 * It does, in fact, allow duplicate group names, and 
 * in limited scenarios, that does actually work as
 * expected. However, certain patterns can be composed, 
 * e.g. groups with duplicate names outside of alteration
 * branch, which yield output of the last group, even 
 * if isn't matched. Thus, one might skip the name of 
 * the first group, since it is not matched anyway. 
 * That is only fixed in PHP 8.0. Should we only support 
 * PHP 8.0, we could actually fully support duplicate names.
 * 
 * This feature is made additionally complex, because 
 * during the first match, an unmatched group is appended 
 * to the output, giving an impression that groups
 * with duplicate names can coalesce to a matched occurrence.
 * However, in truth, that's just the artifact of single matches
 * and trailing optional group - if the duplicate, unmatched
 * group is followed by another matched group, then the
 * defect appears again, and thus can't be properly used
 * in the library.
 */

class searchGroup extends TestCase
{
    private Pattern $pattern;

    protected function setUp(): void
    {
        $this->pattern = new Pattern('(?<group>Valar)? (?<group>morghulis)?', 'J');
    }

    public function test()
    {
        $this->assertEquals(['Valar'], $this->pattern->searchGroup('Valar morghulis', 'group'));
    }

    /**
     * @test
     */
    public function unmatched()
    {
        $this->assertSame([null], $this->pattern->searchGroup(' morghulis', 'group'));
    }

    /**
     * @test
     */
    public function unmatchedSecond()
    {
        $this->assertSame(['Valar'], $this->pattern->searchGroup('Valar ', 'group'));
    }
}
