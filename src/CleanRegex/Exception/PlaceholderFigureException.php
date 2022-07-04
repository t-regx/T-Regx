<?php
namespace TRegx\CleanRegex\Exception;

use TRegx\CleanRegex\Internal\Prepared\Template\Cluster\Cluster;

class PlaceholderFigureException extends \Exception
{
    public static function forSuperfluousPlaceholders(int $expected, int $actual): PlaceholderFigureException
    {
        return new self("Not enough corresponding figures supplied. Used $expected placeholders, but $actual figures supplied.");
    }

    public static function forSuperfluousFigures(int $expected, int $actual, Cluster $cluster): self
    {
        return new self("Found a superfluous figure: {$cluster->type()}. Used $expected placeholders, but $actual figures supplied.");
    }
}
