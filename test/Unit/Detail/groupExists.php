<?php
namespace Test\Unit\Detail;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Regex\Detail;
use Regex\Pattern;
use function Test\Fixture\Functions\catching;

class groupExists extends TestCase
{
    /**
     * @test
     */
    public function wholeMatch()
    {
        $detail = $this->detail('Winter is coming!', 'Winter is coming!');
        $this->assertTrue($detail->groupExists(0));
    }

    /**
     * @test
     */
    public function exists()
    {
        $detail = $this->detail('(?<group>)', '');
        $this->assertTrue($detail->groupExists('group'));
    }

    /**
     * @test
     */
    public function missing()
    {
        $detail = $this->detail('(?<group>)', '');
        $this->assertFalse($detail->groupExists('missing'));
    }

    /**
     * @test
     */
    public function invalidName()
    {
        $detail = $this->anyDetail();
        catching(fn() => $detail->groupExists('2group'))
            ->assertException(InvalidArgumentException::class)
            ->assertMessage("Group name must be an alphanumeric string, not starting with a digit, given: '2group'.");
    }

    /**
     * @test
     */
    public function lastOptional()
    {
        $detail = $this->detail('(Valar) (?<optional>dohaeris)?', 'Valar Morghulis');
        $this->assertTrue($detail->groupExists('optional'));
        $this->assertTrue($detail->groupExists(2));
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
