# T-Regex Development and Contributing rules

There are a few rules that we believe must be obeyed, while developing and maintaining T-Regx:

1. Lack of fake TDD
2. Preference of exceptions over: defaults, `null`s, warnings, magic values or false-positives.
3. Type-safety
4. Minimisation of inconsistencies
5. Lack of unnecessary performance overhead

## Fake TDD

In presence of overwhelming fashion for scrum teams, agile approaches, tdd, oop and what not - one must understand what
T-Regx understands as TDD, which is not always the same as what others might consider TDD.

### Ideal TDD

T-Regx contributors must take for granted that:

- Coverage is **not** a determinant of a well tested code. One can achieve 100% coverage with little effort and **
  still** encounter an enormous number of bugs.

  Coverage means:
    - Not covered parts are **definitely** not tested
    - Covered parts are **not necessarily** tested

  That's it. No more information can be obtained from coverage.
- A class containing coverage hits **is not necessarily** tested. To verify that, one should get acquainted with: unit
  tests of the class, integration testcases that test behaviour of the class/feature implemented by the class
  (also indirectly) and use mutation testing.

  **Only** once done that, one is able to define which parts of the class are covered by tests and which are not.
- **There is no automatic tool that can tell you whether a file is tested.** You need to read the tests, understand what
  parts are covered and what not.
- There is no rule, so that each `Foo` class should have a corresponding `FooTest` class. You should choose your tests
  accordingly. Surprising, I know.

### Where to place new tests

Currently, we have 4 roots for automatic tests in T-Regx:

- `Feature` (a.k.a. "end to end") - This category has two goals:
    - Ensure that a certain functionality works "out of the box" (as if used by an end user).
    - Ensure that each dependency is integrated properly with other dependencies.
    - Is immute to refactoring of internals

  Rules:
  - Each test-case must be created from `pattern()` function or one of `Pattern` factory methods.

- `Interaction` - You'd like to test behaviour - then test only the part of your interface that's relevant to the
  feature domain, and not each element separately - write the smallest tests possible. Try to keep coupling of tests
  with logic as low as possible.

  Rules:
  - There should be as little tests in this category as possible (preferably 1 per functionality). More throughout
    testcases should be in `Unit` or `Feature`.
  - If possible, any dependencies should be real instances, instead of fakes. If instantiating becomes too complicated,
    occasional fakes are allowed, to make tests easier to read and edit.

- `Unit` - This is the category that the coverage is measured against (if there are no tests for a class in this
  category, it's as thought it's not covered). Rules:
  - 100% of dependencies must be faked. Only a change in the tested class can cause the test to fail.

- `Functional` - tests in `Functional` cover T-Regx's contract with PHP methods, errors, warnings, etc.

  Rules:
  - Tests for breaking changes between PHP versions should be in `Functional`
  - Tests for `preg_` and `preg::` should be in `Functional`
  - Tests for reaction of T-Regx on warnings, notices, errors, fatals and

## Avoid inconsistencies

We value consistency. Knowing a part of a library, should aid in learning the rest of it.

Keep in mind:

- When editing a feature, see if there's also a similar feature. Consider, whether they both should be edited, or only
  the one?
- For example, at first, there were only methods `flatMap()` in `match()`, `match()->group()` and others. When we added
  `flatMapAssoc()`, we didn't only add it to the most obvious group, but everywhere where `flatMap()` was.

## Simple and clean design

We'd like you to try to avoid unnecessary setters and getters, just for the sake of them. All classes, whichever are fit
for it, should be **immutable**. Surely, not everytime it's possible.

## Formality

### Fully qualified names

We use Fully Qualified Names for global PHP functions, for the sake of performance. To keep it consistent, we also added
a composer command which helps us find any unqualified functions. Use it like so

```bash
./composer.phar fqn
```

or

```cmd
php composer.phar fqn
```

### PhpUnit `@covers` annotation

Coverage is not a guarantee of a tested or well-tested code. It's only ever a measure of which parts of the code wasn't
touched by tests, period. We reached 100% coverage in T-Regx years ago, and it ceased to be a useful tool. We couldn't
use coverage to find which places aren't tested, because we always had 100%. It was useless.

So we decided to exclude `Functional`, `Feature` and `Interaction` tests from coverage. Now, only tests in `Unit`
category are taken into account when generating coverage. Now we have coverage around 40-60% and are working our way up
with unit tests.

FAQ:

- **Why did you intentioanlly decrease coverage?**

  Keep in mind, that we only excluded tests from the coverage. The tests themselves still exist and still are run. Tests
  are very useful! Only a fool would remove tests.
- **So why don't you include all the tests in the coverage?**

  Because then we have 100% coverage, and we can't use coverage to find which parts of the code aren't unit-tested.

- **Can't you find untested parts by just running `Unit` category coverage?**

  Yes, that's another way to do it. However, excluding `Feature`/`Interaction` achieves the same goal, so why wouldn't
  we.

- **You already have 100% coverage, why lie about it?**

  We don't strive for 100% coverage metric, nor we think anyone should. 100% coverage is just an output of some tool.
  Having 100% coverage doesn't *mean* anything. If you can't use it as a tool, then it's useless. By excluding `Feature`
  /`Interaction` tests from coverage we gave the coverage purpose again.

- **If you exclude `Feature`/`Interaction` from coverage, how do you find missing tests in those categories?**

  Good question, we probably can't. But neither could we when they were included, because there was always 100%
  coverage.
