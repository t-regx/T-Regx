<?php
namespace Test\Unit\Detail;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Regex\Detail;
use Regex\GroupException;
use Regex\Pattern;
use function Test\Fixture\Functions\catching;

class groupMatched extends TestCase
{
    /**
     * @test
     */
    public function matched()
    {
        $detail = $this->detail('(?<winter>Winter) is coming', 'Winter is coming!');
        $this->assertTrue($detail->groupMatched('winter'));
    }

    /**
     * @test
     */
    public function unmatched()
    {
        $detail = $this->detail('(?<summer>Summer)? is coming', 'Winter is coming!');
        $this->assertFalse($detail->groupMatched('summer'));
    }

    /**
     * @test
     */
    public function invalidName()
    {
        $detail = $this->anyDetail();
        catching(fn() => $detail->groupMatched('2group'))
            ->assertException(InvalidArgumentException::class)
            ->assertMessage("Group name must be an alphanumeric string, not starting with a digit, given: '2group'.");
    }

    /**
     * @test
     */
    public function lastOptional()
    {
        $detail = $this->detail('(Valar) (?<optional>dohaeris)?', 'Valar Morghulis');
        $this->assertFalse($detail->groupMatched('optional'));
        $this->assertFalse($detail->groupMatched(2));
    }

    /**
     * @test
     */
    public function empty()
    {
        $detail = $this->detail('(?<empty>)', 'We do not sow');
        $this->assertTrue($detail->groupMatched('empty'));
    }

    /**
     * @test
     */
    public function missingGroups()
    {
        $detail = $this->anyDetail();
        catching(fn() => $detail->groupMatched('missing'))
            ->assertException(GroupException::class)
            ->assertMessage("Capturing group does not exist: 'missing'.");
    }

    private function anyDetail(): Detail
    {
        return $this->detail('any', 'any');
    }

    private function detail(string $pattern, string $subject): Detail
    {
        return (new Pattern($pattern))->first($subject);
    }
}
