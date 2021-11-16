<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\Details;

use PHPUnit\Framework\TestCase;
use Test\Fakes\CleanRegex\Internal\Model\Match\ThrowEntry;
use Test\Utils\TestCasePasses;
use TRegx\CleanRegex\Internal\Match\Details\NumericDetail;


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
