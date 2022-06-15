<?php
namespace Test\Unit\CleanRegex\Internal\Match\Details;

use PHPUnit\Framework\TestCase;
use Test\Fakes\CleanRegex\Internal\Model\ThrowEntry;
use Test\Utils\TestCase\TestCasePasses;
use TRegx\CleanRegex\Internal\Match\Details\NumericDetail;
use TRegx\CleanRegex\Internal\Model\Entry;

class NumericDetailTest extends TestCase
{
    use TestCasePasses;

    /**
     * @test
     */
    public function shouldConstructorNotCallEntry()
    {
        /**
         * Instance of {@see Entry} can be delegating calls
         * to lazy-loaded matches, and we never want instance
         * of {@see NumericDetail} to run it, hence this test.
         */

        // when
        new NumericDetail(new ThrowEntry());

        // then
        $this->pass();
    }
}
