<?php
namespace Test\Utils\Runtime;

trait CausesWarnings
{
    public function causeRuntimeWarning(): void
    {
        @\preg_match('/pattern/u', "\xc3\x28");
    }

    public function causeMalformedPatternWarning(): void
    {
        @\preg_match('/unclosed pattern', '');
    }
}
