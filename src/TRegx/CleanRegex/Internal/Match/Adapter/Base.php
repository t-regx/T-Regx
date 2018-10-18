<?php
namespace TRegx\CleanRegex\Internal\Match\Adapter;

use TRegx\CleanRegex\Internal\InternalPattern;

interface Base
{
    public function getPattern(): InternalPattern;

    public function getSubject(): string;

    public function match(): array;

    public function matchCountOffset(): array;

    public function matchCountVerified(): array;

    public function matchAll(): array;

    public function matchAllOffsets(): array;
}
