<?php
namespace TRegx\CleanRegex\Internal\Prepared\Figure;

use TRegx\CleanRegex\Internal\Prepared\Template\NullToken;
use TRegx\CleanRegex\Internal\Prepared\Template\Token;
use UnderflowException;

class ExpectedFigures implements Figures
{
    /** @var FigureExpectation */
    private $expectation;
    /** @var Figures */
    private $figures;

    public function __construct(CountedFigures $figures)
    {
        $this->figures = $figures;
        $this->expectation = new FigureExpectation($figures);
    }

    public function nextToken(): Token
    {
        $this->expectation->expectNext();
        try {
            return $this->figures->nextToken();
        } catch (UnderflowException $exception) {
            return new NullToken();
        }
    }

    public function meetExpectation(): void
    {
        $this->expectation->meetExpectation();
    }
}
