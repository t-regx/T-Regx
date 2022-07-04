<?php
namespace TRegx\CleanRegex\Exception;

class PlaceholderFigureException extends \Exception
{
    public static function forSuperfluousPlaceholders(int $expected, int $actual): PlaceholderFigureException
    {
        return new self("Not enough corresponding figures supplied. Used $expected placeholders, but $actual figures supplied.");
    }

    public static function forSuperfluousFigures(int $expected, int $actual): self
    {
        return new self("Supplied a superfluous figure. Used $expected placeholders, but $actual figures supplied.");
    }
}
