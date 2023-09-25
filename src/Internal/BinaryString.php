<?php
namespace Regex\Internal;

class BinaryString
{
    public bool $containsControl;
    private string $nonControl;

    public function __construct(string $string)
    {
        $this->containsControl = $this->containsControl($string);
        $this->nonControl = \str_replace(["\0", "\t", "\r", "\x8"], ' ', $string);
    }

    public function __toString(): string
    {
        return $this->nonControl;
    }

    private function containsControl(string $string): bool
    {
        if (\strPbrk($string, "\0\x08\t\v\f\r\e\x7f")) {
            return true;
        }
        if (\mb_check_encoding($string, 'utf8')) {
            return \preg_match('/[\0-\x07\x0e-\x1f]|[^\P{Z} ]/u', $string);
        }
        return true;
    }
}
