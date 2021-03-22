<?php
namespace TRegx\CleanRegex\Internal\Prepared\Quotable;

class Extended
{
    public static function quote(string $string): string
    {
        return \strtr($string, [
            ' '    => '\ ',
            "\t"   => '\t',   #9
            "\n"   => '\n',   #10
            "\x0B" => '\x0B', #11
            "\f"   => '\f',   #12
            "\r"   => '\r',   #13
        ]);
    }
}
