<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\Base;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchNullable;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;

class ApiBaseTest extends TestCase
{
    public function testMatchGroupableInstance()
    {
        // given
        $base = new ApiBase(new InternalPattern('\w+'), 'word', new UserData());

        // when
        $groupable = $base->matchGroupable();

        // then
        $this->assertInstanceOf($this->getClassName(), $groupable);
    }

    private function getClassName()
    {
        if (defined('PREG_UNMATCHED_AS_NULL')) {
            return RawMatchNullable::class;
        }
        return RawMatchOffset::class;
    }
}
