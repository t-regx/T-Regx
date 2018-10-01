<?php
namespace TRegx\CleanRegex\Analyze\Simplify\Model;

class AltEnd extends Model
{
    /** @var string */
    private $content;

    public function __construct()
    {
        $this->content = ')';
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
