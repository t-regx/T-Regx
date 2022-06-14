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

    public function causeCompileWarning(): void
    {
        \preg_replace_callback('/a/', function () {
            @\trigger_error('preg_match() error', E_USER_NOTICE);
        }, 'a');
    }
}
