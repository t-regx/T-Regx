# T-Regex Development and Contributing rules

1. The **the** importantest - **Lack of fake TDD**
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
