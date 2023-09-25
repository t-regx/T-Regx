<?php
namespace Test\Unit\_nonPrintable;

use TRegx\PhpUnit\DataProviders\DataProvider;

trait NonPrintables
{
    public function nonPrintables(): DataProvider
    {
        return DataProvider::of([
            'low ascii codes'             => ["\1\2\3\4", ''],
            'bell'                        => [\chr(7), ''],
            'backspace'                   => [\chr(8), ' '],
            'tab'                         => [\chr(9), ' '],
            'vertical tab'                => [\chr(11), ''],
            'form feed'                   => [\chr(12), ''],
            'carriage return'             => [\chr(13), ' '],
            'shift out'                   => [\chr(14), ''],
            'escape'                      => [\chr(27), ''],
            'unit'                        => [\chr(31), ''],
            'delete'                      => [\chr(127), ''],
            'unicode nbsp'                => ["\xc2\xa0", ' '],
            'unicode line separator'      => ["\u{2028}", ' '],
            'unicode paragraph separator' => ["\u{2029}", ' '],
            'unicode (malformed)'         => ["\xc3\x28", "\xc3\x28"]
        ]);
    }
}
