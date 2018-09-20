<?php
namespace CleanRegex\Match\Details;

class NotMatched implements Details
{
    /** @var array */
    private $matches;
    /** @var string */
    private $subject;

    public function __construct(array $matches, string $subject)
    {
        $this->matches = $matches;
        $this->subject = $subject;
    }

    public function subject(): string
    {
        return $this->subject;
    }

    /**
     * @return string[]
     */
    public function groupNames(): array
    {
        return array_values(array_filter(array_keys($this->matches), function ($key) {
            return is_string($key);
        }));
    }

    /**
     * @param string|int $nameOrIndex
     * @return bool
     */
    public function hasGroup($nameOrIndex): bool
    {
        return array_key_exists($nameOrIndex, $this->matches);
    }
}
