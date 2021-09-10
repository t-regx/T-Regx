# T-Regex Development and Contributing rules

1. The the importantest - **Lack of fake TDD**
2. The importantest - **Prefer exceptions to magic***
3. Very important - **Type-safety**
4. Much important - **Minimisation of inconsistencies**
5. Important - **No "general-purpose" classes**

! * *Magic would be: defaults, `null`s, warnings, implicity arguments or false-positives)*

## Fake TDD

T-Regx contributors must take for granted that:

- There is no rule, so that each `Foo` class should have a corresponding `FooTest` class. Surprising, I know.
- **There is no automatic tool that can tell you whether a file is tested.** You need to read the tests, understand the
  specification.
- Coverage is **not** a determinant of well tested code. One can achieve 100% coverage with little effort and **still**
  encounter an enormous number of bugs.

  Coverage means:
  - Not covered parts are **definitely** not tested
  - Covered parts are **not necessarily** tested

  A class containing coverage hits **is not necessarily** tested. To verify that, one should get acquainted with: unit
  tests, interaction testcases that test behaviour of the feature directly or indirectly. Or using mutation testing.

## Simple and clean design

- Prefer immutable classes (not everytime that's possible)
- Don't add getters and setters, just for the sake of them
- Follow Zen of Python

## "General-purpose" classes

In a high enough abstraction layer, there's little room for "general purpose" classes. Don't create classes that are
unnecessarily loosely coupled, "because it may be used later". If there is a reason to truly reuse them in the future,
we'll refactor the class accordingly, when we have the reason before us.

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

  Keep in mind, that we only excluded reports from the coverage. The tests themselves still exist and still are run.
  Tests are very useful! Only a fool would remove good tests.
- **So why don't you include all the reports in the coverage?**

  Because then we have 100% coverage, and we can't use coverage to find which parts of the code aren't unit-tested.

- **Can't you find untested parts by just running `Unit` category coverage?**

  Yes, that's another way to do it. However, excluding `Feature`/`Interaction` achieves the same goal, so why wouldn't
  we.

- **You already have 100% coverage, why lie about it?**

  We don't strive for 100% coverage metric, nor we think anyone should. It's just an output of some tool, it doesn't
  tell you anything about software itself. Having 100% coverage doesn't *mean* anything. If you can't use it as a tool,
  then it's useless. By excluding `Feature`/`Interaction` reports we gave the coverage its purpose again.

- **If you exclude `Feature`/`Interaction` from coverage, how do you find missing tests in those categories?**

  Good question, we probably can't. But neither could we when they were included, because the coverage was always 100%.
