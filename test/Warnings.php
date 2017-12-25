<?php
namespace Test;

trait Warnings
{
    public function causeRuntimeWarning(): void
    {
        @preg_match('/pattern/u', "\xc3\x28");
    }

    public function causeCompileWarning(): void
    {
        @preg_match('/unclosed pattern', '');
    }
}
