<?php
namespace TRegx\CleanRegex\Match\Details;

/**
 * In PHP 8, "match" will become a keyword and thus will be unfit
 * for class names and namespaces.
 *
 * @deprecated Use {@see \TRegx\CleanRegex\Match\Details\ReplaceDetail} instead
 */
interface ReplaceMatch extends ReplaceDetail, Match
{
}
