## Where to place new tests

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
