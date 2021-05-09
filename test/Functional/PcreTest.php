<?php
namespace Test\Functional\TRegx;

use PHPUnit\Framework\TestCase;
use TRegx\Pcre;
use const PHP_VERSION_ID;

class PcreTest extends TestCase
{
    /**
     * @test
     * @dataProvider pcreVersionDependant
     */
    public function shouldGetPcreVersion(string $expectedVersion, int $expectedMajor, int $expectedMinor, bool $expectedPcre2)
    {
        // when
        $version = Pcre::semanticVersion();
        $major = Pcre::majorVersion();
        $minor = Pcre::minorVersion();
        $pcre2 = Pcre::pcre2();

        // then
        $this->assertSame($expectedVersion, $version);
        $this->assertSame($expectedMajor, $major);
        $this->assertSame($expectedMinor, $minor);
        $this->assertSame($expectedPcre2, $pcre2);
    }

    public function pcreVersionDependant(): array
    {
        if (PHP_VERSION_ID >= 70412) {
            return $this->version(10, 35, true);
        }
        if (PHP_VERSION_ID >= 70406) {
            return $this->version(10, 34, true);
        }
        if (PHP_VERSION_ID >= 70400) {
            return $this->version(10, 33, true);
        }
        if (PHP_VERSION_ID >= 70300) {
            return $this->version(10, 32, true);
        }
        if (PHP_VERSION_ID >= 70200) {
            return $this->version(8, 41, false);
        }
        if (PHP_VERSION_ID >= 70003) {
            return $this->version(8, 38, false);
        }
        return $this->version(8, 37, false);
    }

    private function version(int $major, int $minor, bool $pcre2): array
    {
        return [PHP_VERSION => ["$major.$minor", $major, $minor, $pcre2]];
    }

    /**
     * @test
     */
    public function shouldNotBeProneToConstantOverride()
    {
        if (PHP_VERSION_ID >= 70300) {
            $this->markTestSkipped("PHP with PCRE2 is not prone to constant override");
        }

        // given
        define('PCRE_VERSION_MAJOR', 1200);
        define('PCRE_VERSION_MINOR', 1300);

        // when
        $semantic = Pcre::semanticVersion();
        $minor = Pcre::minorVersion();
        $major = Pcre::majorVersion();
        $pcre2 = Pcre::pcre2();

        // then
        $this->assertLessThan(1100, $major);
        $this->assertLessThan(1100, $minor);
        $this->assertFalse($pcre2);
        $this->assertNotSame('1200.1300', $semantic);
    }
}
