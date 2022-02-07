<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Numeral;

use PHPUnit\Framework\TestCase;
use Test\Fakes\CleanRegex\Internal\Numeral\ThrowBase;
use Test\Utils\ArchitectureDependant;
use TRegx\CleanRegex\Internal\Numeral\Base;
use TRegx\CleanRegex\Internal\Numeral\NumeralFormatException;
use TRegx\CleanRegex\Internal\Numeral\NumeralOverflowException;
use TRegx\CleanRegex\Internal\Numeral\StringNumeral;
use TRegx\DataProvider\CrossDataProviders;

/**
 * @covers \TRegx\CleanRegex\Internal\Numeral\StringNumeral
 */
class StringNumeralTest extends TestCase
{
    use ArchitectureDependant;

    /**
     * @test
     * @dataProvider maximumAndMinimumValues
     */
    public function shouldParse(string $input, $expected, int $base)
    {
        // given
        $numeral = new StringNumeral($input);

        // when
        $integer = $numeral->asInt(new Base($base));

        // then
        $this->assertSame($expected, $integer);
    }

    public function maximumAndMinimumValues(): array
    {
        return \array_merge(
            $this->maximumOnArchitecture32(2147483647),
            $this->minimumOnArchitecture32(-2147483648),
            $this->onArchitecture64($this->maximumOnArchitecture64()),
            $this->onArchitecture64($this->minimumOnArchitecture64())
        );
    }

    private function maximumOnArchitecture32(int $maximum32): array
    {
        return [
            ['1111111111111111111111111111111', $maximum32, 2],
            ['12112122212110202101', $maximum32, 3],
            ['1333333333333333', $maximum32, 4],
            ['13344223434042', $maximum32, 5],
            ['553032005531', $maximum32, 6],
            ['104134211161', $maximum32, 7],
            ['17777777777', $maximum32, 8],
            ['5478773671', $maximum32, 9],
            ['2147483647', $maximum32, 10],
            ['a02220281', $maximum32, 11],
            ['4bb2308a7', $maximum32, 12],
            ['282ba4aaa', $maximum32, 13],
            ['1652ca931', $maximum32, 14],
            ['c87e66b7', $maximum32, 15],
            ['7fffffff', $maximum32, 16],
            ['53g7f548', $maximum32, 17],
            ['3928g3h1', $maximum32, 18],
            ['27c57h32', $maximum32, 19],
            ['1db1f927', $maximum32, 20],
            ['140h2d91', $maximum32, 21],
            ['ikf5bf1', $maximum32, 22],
            ['ebelf95', $maximum32, 23],
            ['b5gge57', $maximum32, 24],
            ['8jmdnkm', $maximum32, 25],
            ['6oj8ion', $maximum32, 26],
            ['5ehncka', $maximum32, 27],
            ['4clm98f', $maximum32, 28],
            ['3hk7987', $maximum32, 29],
            ['2sb6cs7', $maximum32, 30],
            ['2d09uc1', $maximum32, 31],
            ['1vvvvvv', $maximum32, 32],
            ['1lsqtl1', $maximum32, 33],
            ['1d8xqrp', $maximum32, 34],
            ['15v22um', $maximum32, 35],
            ['zik0zj', $maximum32, 36],
        ];
    }

    private function minimumOnArchitecture32(int $minimum32): array
    {
        return [
            ['-10000000000000000000000000000000', $minimum32, 2],
            ['-12112122212110202102', $minimum32, 3],
            ['-2000000000000000', $minimum32, 4],
            ['-13344223434043', $minimum32, 5],
            ['-553032005532', $minimum32, 6],
            ['-104134211162', $minimum32, 7],
            ['-20000000000', $minimum32, 8],
            ['-5478773672', $minimum32, 9],
            ['-2147483648', $minimum32, 10],
            ['-a02220282', $minimum32, 11],
            ['-4bb2308a8', $minimum32, 12],
            ['-282ba4aab', $minimum32, 13],
            ['-1652ca932', $minimum32, 14],
            ['-c87e66b8', $minimum32, 15],
            ['-80000000', $minimum32, 16],
            ['-53g7f549', $minimum32, 17],
            ['-3928g3h2', $minimum32, 18],
            ['-27c57h33', $minimum32, 19],
            ['-1db1f928', $minimum32, 20],
            ['-140h2d92', $minimum32, 21],
            ['-ikf5bf2', $minimum32, 22],
            ['-ebelf96', $minimum32, 23],
            ['-b5gge58', $minimum32, 24],
            ['-8jmdnkn', $minimum32, 25],
            ['-6oj8ioo', $minimum32, 26],
            ['-5ehnckb', $minimum32, 27],
            ['-4clm98g', $minimum32, 28],
            ['-3hk7988', $minimum32, 29],
            ['-2sb6cs8', $minimum32, 30],
            ['-2d09uc2', $minimum32, 31],
            ['-2000000', $minimum32, 32],
            ['-1lsqtl2', $minimum32, 33],
            ['-1d8xqrq', $minimum32, 34],
            ['-15v22un', $minimum32, 35],
            ['-zik0zk', $minimum32, 36],
        ];
    }

    private function maximumOnArchitecture64(): array
    {
        $maximum64 = 9223372036854775807;
        return [
            ['111111111111111111111111111111111111111111111111111111111111111', $maximum64, 2],
            ['2021110011022210012102010021220101220221', $maximum64, 3],
            ['13333333333333333333333333333333', $maximum64, 4],
            ['1104332401304422434310311212', $maximum64, 5],
            ['1540241003031030222122211', $maximum64, 6],
            ['22341010611245052052300', $maximum64, 7],
            ['777777777777777777777', $maximum64, 8],
            ['67404283172107811827', $maximum64, 9],
            ['9223372036854775807', $maximum64, 10],
            ['1728002635214590697', $maximum64, 11],
            ['41a792678515120367', $maximum64, 12],
            ['10b269549075433c37', $maximum64, 13],
            ['4340724c6c71dc7a7', $maximum64, 14],
            ['160e2ad3246366807', $maximum64, 15],
            ['7fffffffffffffff', $maximum64, 16],
            ['33d3d8307b214008', $maximum64, 17],
            ['16agh595df825fa7', $maximum64, 18],
            ['ba643dci0ffeehh', $maximum64, 19],
            ['5cbfjia3fh26ja7', $maximum64, 20],
            ['2heiciiie82dh97', $maximum64, 21],
            ['1adaibb21dckfa7', $maximum64, 22],
            ['i6k448cf4192c2', $maximum64, 23],
            ['acd772jnc9l0l7', $maximum64, 24],
            ['64ie1focnn5g77', $maximum64, 25],
            ['3igoecjbmca687', $maximum64, 26],
            ['27c48l5b37oaop', $maximum64, 27],
            ['1bk39f3ah3dmq7', $maximum64, 28],
            ['q1se8f0m04isb', $maximum64, 29],
            ['hajppbc1fc207', $maximum64, 30],
            ['bm03i95hia437', $maximum64, 31],
            ['7vvvvvvvvvvvv', $maximum64, 32],
            ['5hg4ck9jd4u37', $maximum64, 33],
            ['3tdtk1v8j6tpp', $maximum64, 34],
            ['2pijmikexrxp7', $maximum64, 35],
            ['1y2p0ij32e8e7', $maximum64, 36],
        ];
    }

    private function minimumOnArchitecture64(): array
    {
        /**
         * I'm unable to explain it, but somehow -9223372036854775808 is parsed
         * as a float, even though -9223372036854775807-1 is calculated as an integer.
         *
         * It's probably because INT_MAX is positive 9223372036854775807, so php
         * dumbly checks negative 9223372036854775807, even though it's not the
         * smallest possible integer.
         *
         * Using {@see PHP_INT_MIN} also works as integer,
         */
        $minimum64 = -9223372036854775807 - 1;
        return [
            ['-1000000000000000000000000000000000000000000000000000000000000000', $minimum64, 2],
            ['-2021110011022210012102010021220101220222', $minimum64, 3],
            ['-20000000000000000000000000000000', $minimum64, 4],
            ['-1104332401304422434310311213', $minimum64, 5],
            ['-1540241003031030222122212', $minimum64, 6],
            ['-22341010611245052052301', $minimum64, 7],
            ['-1000000000000000000000', $minimum64, 8],
            ['-67404283172107811828', $minimum64, 9],
            ['-9223372036854775808', $minimum64, 10],
            ['-1728002635214590698', $minimum64, 11],
            ['-41a792678515120368', $minimum64, 12],
            ['-10b269549075433c38', $minimum64, 13],
            ['-4340724c6c71dc7a8', $minimum64, 14],
            ['-160e2ad3246366808', $minimum64, 15],
            ['-8000000000000000', $minimum64, 16],
            ['-33d3d8307b214009', $minimum64, 17],
            ['-16agh595df825fa8', $minimum64, 18],
            ['-ba643dci0ffeehi', $minimum64, 19],
            ['-5cbfjia3fh26ja8', $minimum64, 20],
            ['-2heiciiie82dh98', $minimum64, 21],
            ['-1adaibb21dckfa8', $minimum64, 22],
            ['-i6k448cf4192c3', $minimum64, 23],
            ['-acd772jnc9l0l8', $minimum64, 24],
            ['-64ie1focnn5g78', $minimum64, 25],
            ['-3igoecjbmca688', $minimum64, 26],
            ['-27c48l5b37oaoq', $minimum64, 27],
            ['-1bk39f3ah3dmq8', $minimum64, 28],
            ['-q1se8f0m04isc', $minimum64, 29],
            ['-hajppbc1fc208', $minimum64, 30],
            ['-bm03i95hia438', $minimum64, 31],
            ['-8000000000000', $minimum64, 32],
            ['-5hg4ck9jd4u38', $minimum64, 33],
            ['-3tdtk1v8j6tpq', $minimum64, 34],
            ['-2pijmikexrxp8', $minimum64, 35],
            ['-1y2p0ij32e8e8', $minimum64, 36],
        ];
    }

    /**
     * @test
     * @dataProvider overflown
     */
    public function shouldThrowForOverflow(string $value, int $base)
    {
        // given
        $number = new StringNumeral($value);

        // then
        $this->expectException(NumeralOverflowException::class);

        // when
        $number->asInt(new Base($base));
    }

    public function overflown(): array
    {
        return \array_merge(
            $this->onArchitecture32($this->overflownPositiveArchitecture32()),
            $this->onArchitecture32($this->overflownNegativeArchitecture32()),
            $this->overflownPositiveArchitecture64(),
            $this->overflownNegativeArchitecture64()
        );
    }

    private function overflownPositiveArchitecture32(): array
    {
        return [
            ['10000000000000000000000000000000', 2],
            ['12112122212110202102', 3],
            ['2000000000000000', 4],
            ['13344223434043', 5],
            ['553032005532', 6],
            ['104134211162', 7],
            ['20000000000', 8],
            ['5478773672', 9],
            ['2147483648', 10],
            ['a02220282', 11],
            ['4bb2308a8', 12],
            ['282ba4aab', 13],
            ['1652ca932', 14],
            ['c87e66b8', 15],
            ['80000000', 16],
            ['53g7f549', 17],
            ['3928g3h2', 18],
            ['27c57h33', 19],
            ['1db1f928', 20],
            ['140h2d92', 21],
            ['ikf5bf2', 22],
            ['ebelf96', 23],
            ['b5gge58', 24],
            ['8jmdnkn', 25],
            ['6oj8ioo', 26],
            ['5ehnckb', 27],
            ['4clm98g', 28],
            ['3hk7988', 29],
            ['2sb6cs8', 30],
            ['2d09uc2', 31],
            ['2000000', 32],
            ['1lsqtl2', 33],
            ['1d8xqrq', 34],
            ['15v22un', 35],
            ['zik0zk', 36],
        ];
    }

    private function overflownNegativeArchitecture32(): array
    {
        return [
            ['-10000000000000000000000000000001', 2],
            ['-12112122212110202110', 3],
            ['-2000000000000001', 4],
            ['-13344223434044', 5],
            ['-553032005533', 6],
            ['-104134211163', 7],
            ['-20000000001', 8],
            ['-5478773673', 9],
            ['-2147483649', 10],
            ['-a02220283', 11],
            ['-4bb2308a9', 12],
            ['-282ba4aac', 13],
            ['-1652ca933', 14],
            ['-c87e66b9', 15],
            ['-80000001', 16],
            ['-53g7f54a', 17],
            ['-3928g3h3', 18],
            ['-27c57h34', 19],
            ['-1db1f929', 20],
            ['-140h2d93', 21],
            ['-ikf5bf3', 22],
            ['-ebelf97', 23],
            ['-b5gge59', 24],
            ['-8jmdnko', 25],
            ['-6oj8iop', 26],
            ['-5ehnckc', 27],
            ['-4clm98h', 28],
            ['-3hk7989', 29],
            ['-2sb6cs9', 30],
            ['-2d09uc3', 31],
            ['-2000001', 32],
            ['-1lsqtl3', 33],
            ['-1d8xqrr', 34],
            ['-15v22uo', 35],
            ['-zik0zl', 36],
        ];
    }

    private function overflownPositiveArchitecture64(): array
    {
        return [
            ['1000000000000000000000000000000000000000000000000000000000000000', 2],
            ['2021110011022210012102010021220101220222', 3],
            ['20000000000000000000000000000000', 4],
            ['1104332401304422434310311213', 5],
            ['1540241003031030222122212', 6],
            ['22341010611245052052301', 7],
            ['1000000000000000000000', 8],
            ['67404283172107811828', 9],
            ['9223372036854775808', 10],
            ['1728002635214590698', 11],
            ['41a792678515120368', 12],
            ['10b269549075433c38', 13],
            ['4340724c6c71dc7a8', 14],
            ['160e2ad3246366808', 15],
            ['8000000000000000', 16],
            ['33d3d8307b214009', 17],
            ['16agh595df825fa8', 18],
            ['ba643dci0ffeehi', 19],
            ['5cbfjia3fh26ja8', 20],
            ['2heiciiie82dh98', 21],
            ['1adaibb21dckfa8', 22],
            ['i6k448cf4192c3', 23],
            ['acd772jnc9l0l8', 24],
            ['64ie1focnn5g78', 25],
            ['3igoecjbmca688', 26],
            ['27c48l5b37oaoq', 27],
            ['1bk39f3ah3dmq8', 28],
            ['q1se8f0m04isc', 29],
            ['hajppbc1fc208', 30],
            ['bm03i95hia438', 31],
            ['8000000000000', 32],
            ['5hg4ck9jd4u38', 33],
            ['3tdtk1v8j6tpq', 34],
            ['2pijmikexrxp8', 35],
            ['1y2p0ij32e8e8', 36],
        ];
    }

    private function overflownNegativeArchitecture64(): array
    {
        return [
            ['-1000000000000000000000000000000000000000000000000000000000000001', 2],
            ['-2021110011022210012102010021220101221000', 3],
            ['-20000000000000000000000000000001', 4],
            ['-1104332401304422434310311214', 5],
            ['-1540241003031030222122213', 6],
            ['-22341010611245052052302', 7],
            ['-1000000000000000000001', 8],
            ['-67404283172107811830', 9],
            ['-9223372036854775809', 10],
            ['-1728002635214590699', 11],
            ['-41a792678515120369', 12],
            ['-10b269549075433c39', 13],
            ['-4340724c6c71dc7a9', 14],
            ['-160e2ad3246366809', 15],
            ['-8000000000000001', 16],
            ['-33d3d8307b21400a', 17],
            ['-16agh595df825fa9', 18],
            ['-ba643dci0ffeei0', 19],
            ['-5cbfjia3fh26ja9', 20],
            ['-2heiciiie82dh99', 21],
            ['-1adaibb21dckfa9', 22],
            ['-i6k448cf4192c4', 23],
            ['-acd772jnc9l0l9', 24],
            ['-64ie1focnn5g79', 25],
            ['-3igoecjbmca689', 26],
            ['-27c48l5b37oap0', 27],
            ['-1bk39f3ah3dmq9', 28],
            ['-q1se8f0m04isd', 29],
            ['-hajppbc1fc209', 30],
            ['-bm03i95hia439', 31],
            ['-8000000000001', 32],
            ['-5hg4ck9jd4u39', 33],
            ['-3tdtk1v8j6tpr', 34],
            ['-2pijmikexrxp9', 35],
            ['-1y2p0ij32e8e9', 36],
        ];
    }

    /**
     * @test
     * @dataProvider malformedValues
     */
    public function shouldThrowForMalformedValues(int $base, string $value)
    {
        // given
        $number = new StringNumeral($value);

        // then
        $this->expectException(NumeralFormatException::class);

        // when
        $number->asInt(new Base($base));
    }

    public function malformedValues(): array
    {
        return CrossDataProviders::cross([[2], [10], [16], [36]], [['--1'], ['1-1'], ['+2'], ['-'], ['\n1']]);
    }

    /**
     * @test
     * @dataProvider cornerDigits
     */
    public function shouldThrowForCornerDigit(int $base, string $value)
    {
        // given
        $number = new StringNumeral($value);

        // then
        $this->expectException(NumeralFormatException::class);

        // when
        $number->asInt(new Base($base));
    }

    public function cornerDigits(): array
    {
        return [[9, '9'], [2, '2'], [35, 'z'], [9, '-9'], [2, '-2'], [35, '-z']];
    }

    /**
     * @test
     */
    public function testZero()
    {
        // given
        $number = new StringNumeral('000');

        // when
        $format = $number->asInt(new Base(12));

        // then
        $this->assertSame(0, $format);
    }

    /**
     * @test
     */
    public function shouldThrowMalformedForEmpty()
    {
        // given
        $number = new StringNumeral('');

        // then
        $this->expectException(NumeralFormatException::class);

        // when
        $number->asInt(new ThrowBase());
    }

    /**
     * @test
     */
    public function shouldParseCaseInsensitively()
    {
        // given
        $number = new StringNumeral('-ABC');

        // when
        $integer = $number->asInt(new Base(13));

        // then
        $this->assertSame(-1845, $integer);
    }

    /**
     * @test
     * @dataProvider caseInsensitiveBounds
     */
    public function shouldBeCaseInsensitiveForBounds(string $number, int $expected)
    {
        // given
        $number = new StringNumeral($number);

        // when
        $integer = $number->asInt(new Base(36));

        // then
        $this->assertSame($expected, $integer);
    }

    public function caseInsensitiveBounds(): array
    {
        return \array_merge(
            $this->onArchitecture32([['-ZIK0ZK', -2147483647 - 1]]),
            $this->onArchitecture64([['-1Y2P0IJ32E8E8', -9223372036854775807 - 1]])
        );
    }
}
