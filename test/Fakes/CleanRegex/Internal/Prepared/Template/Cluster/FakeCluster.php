<?php
namespace Test\Fakes\CleanRegex\Internal\Prepared\Template\Cluster;

use Test\Utils\Assertion\Fails;
use TRegx\CleanRegex\Internal\Prepared\Phrase\PatternPhrase;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;
use TRegx\CleanRegex\Internal\Prepared\Template\Cluster\Cluster;
use TRegx\CleanRegex\Internal\Type\Type;

class FakeCluster implements Cluster
{
    use Fails;

    /** @var string */
    private $pattern;

    public function __construct(string $text)
    {
        $this->pattern = $text;
    }

    public function phrase(): Phrase
    {
        return new PatternPhrase($this->pattern);
    }

    public function suitable(string $candidate): bool
    {
        throw $this->fail();
    }

    public function type(): Type
    {
        throw $this->fail();
    }
}
