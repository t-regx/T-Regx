<?php
namespace TRegx\CleanRegex\Replace\By;

use TRegx\CleanRegex\Internal\Pcre\Legacy\Base;
use TRegx\CleanRegex\Internal\Replace\By\DelegatedDetail;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Details\DuplicateName;
use TRegx\CleanRegex\Match\Details\Group\Group;

class LazyDetail implements Detail
{
    /** @var DelegatedDetail */
    private $delegatedDetail;
    /** @var Subject */
    private $subject;
    /** @var int */
    private $index;
    /** @var int */
    private $limit;

    public function __construct(Base $base, Subject $subject, int $index, int $limit)
    {
        $this->delegatedDetail = new DelegatedDetail($base, $subject, $index);
        $this->subject = $subject;
        $this->index = $index;
        $this->limit = $limit;
    }

    private function detail(): Detail
    {
        return $this->delegatedDetail->detail();
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
        return $this->detail()->groupNames();
    }

    public function groupsCount(): int
    {
        return $this->detail()->groupsCount();
    }

    /**
     * @param string|int $nameOrIndex
     * @return bool
     */
    public function groupExists($nameOrIndex): bool
    {
        return $this->detail()->groupExists($nameOrIndex);
    }

    public function text(): string
    {
        return $this->detail()->text();
    }

    public function length(): int
    {
        return $this->detail()->length();
    }

    public function byteLength(): int
    {
        return $this->detail()->byteLength();
    }

    public function toInt(int $base = 10): int
    {
        return $this->detail()->toInt($base);
    }

    public function isInt(int $base = 10): bool
    {
        return $this->detail()->isInt($base);
    }

    public function index(): int
    {
        return $this->index;
    }

    public function limit(): int
    {
        return $this->limit;
    }

    /**
     * @param string|int $nameOrIndex
     * @return string
     */
    public function get($nameOrIndex): string
    {
        return $this->detail()->get($nameOrIndex);
    }

    /**
     * @param string|int $nameOrIndex
     * @return Group
     */
    public function group($nameOrIndex): Group
    {
        return $this->detail()->group($nameOrIndex);
    }

    public function usingDuplicateName(): DuplicateName
    {
        return $this->detail()->usingDuplicateName();
    }

    /**
     * @return Group[]
     */
    public function groups(): array
    {
        return $this->detail()->groups();
    }

    /**
     * @return Group[]
     */
    public function namedGroups(): array
    {
        return $this->detail()->namedGroups();
    }

    /**
     * @param string|int $nameOrIndex
     * @return bool
     */
    public function matched($nameOrIndex): bool
    {
        return $this->detail()->matched($nameOrIndex);
    }

    /**
     * @return string[]
     */
    public function all(): array
    {
        return $this->detail()->all();
    }

    public function offset(): int
    {
        return $this->detail()->offset();
    }

    public function tail(): int
    {
        return $this->detail()->tail();
    }

    public function byteOffset(): int
    {
        return $this->detail()->byteOffset();
    }

    public function byteTail(): int
    {
        return $this->detail()->byteTail();
    }

    public function __toString(): string
    {
        return $this->detail();
    }
}
