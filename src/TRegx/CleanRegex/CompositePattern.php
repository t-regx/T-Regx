<?php
namespace TRegx\CleanRegex;

use TRegx\CleanRegex\Internal\CompositePatternMapper;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\SafeRegex\preg;

class CompositePattern
{
    /** @var InternalPattern[] */
    private $patterns;

    public function __construct(array $patterns)
    {
        $this->patterns = $patterns;
    }

    public function allMatch(string $subject): bool
    {
        foreach ($this->patterns as $pattern) {
            if (!preg::match($pattern, $subject)) {
                return false;
            }
        }
        return true;
    }

    public function anyMatches(string $subject): bool
    {
        foreach ($this->patterns as $pattern) {
            if (preg::match($pattern, $subject)) {
                return true;
            }
        }
        return false;
    }

    public function chainedRemove(string $subject): string
    {
        return $this->chainedReplace($subject, '');
    }

    public function chainedReplace(string $subject, string $replacement): string
    {
        foreach ($this->patterns as $pattern) {
            $subject = preg::replace($pattern, $replacement, $subject);
        }
        return $subject;
    }

    public static function of(array $patterns): CompositePattern
    {
        return new CompositePattern((new CompositePatternMapper($patterns))->create());
    }
}
