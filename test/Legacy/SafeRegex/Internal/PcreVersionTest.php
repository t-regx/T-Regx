<?php
namespace Test\Legacy\SafeRegex\Internal;

use PHPUnit\Framework\TestCase;
use TRegx\SafeRegex\Internal\PcreVersion;

/**
 * @deprecated
 * @covers \TRegx\SafeRegex\Internal\PcreVersion
 */
class PcreVersionTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetSemanticVersionIdentity()
    {
        // given
        $version = new PcreVersion('13.14');
        // when
        $semantic = $version->semanticVersion();
        // then
        $this->assertSame('13.14', $semantic);
    }

    /**
     * @test
     */
    public function shouldGetSemanticVersion()
    {
        // given
        $version = new PcreVersion('52.23 2021-15');
        // when
        $semantic = $version->semanticVersion();
        // then
        $this->assertSame('52.23', $semantic);
    }

    /**
     * @test
     */
    public function shouldParseVersion()
    {
        // given
        $version = new PcreVersion('42.31');
        // when
        $majorVersion = $version->majorVersion();
        $minorVersion = $version->minorVersion();
        // then
        $this->assertSame(42, $majorVersion);
        $this->assertSame(31, $minorVersion);
    }

    /**
     * @test
     */
    public function shouldParseVersionOneDigit()
    {
        // given
        $version = new PcreVersion('4.1');
        // when
        $majorVersion = $version->majorVersion();
        $minorVersion = $version->minorVersion();
        // then
        $this->assertSame(4, $majorVersion);
        $this->assertSame(1, $minorVersion);
    }

    /**
     * @test
     * @dataProvider pcre2
     */
    public function shouldBePcre2(string $version, bool $expected)
    {
        // given
        $version = new PcreVersion($version);
        // when
        $pcre2 = $version->pcre2();
        // then
        $this->assertSame($expected, $pcre2);
    }

    public function pcre2(): array
    {
        return [
            ['11.12', true],
            ['10.12', true],
            ['9.2', false],
            ['8.2', false],
        ];
    }
}
