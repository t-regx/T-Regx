## About tests in T-Regx

Currently, we have 4 roots for automatic tests in T-Regx: `Feature`, `Legacy`,
`Structure` and `Supposition`.

In order to minimise misconceptions we don't name the folder `Unit\ `, since the
many users and contributors might bring in their understanding of what "unit test"
is or isn't, we clarify exact criteria T-Regx tests must meet.

## Overall rules for tests

- a test **must not** use or reference a class from `Internal\ ` for any reason.

- a test **must** be small:
  - an ideal test spans 1-3 lines
  - a test up to 4-10 lines is allowed, if the test cannot be split into more
    smaller tests
  - a test suite class with 300 small tests is preferential to a suite with 50
    larger tests covering the same features.
  - increasing quantity of tests **is not** considered a flaw.
  - decreasing specifity of tests **is** considered a flaw.

- a test **must** execute fast.
  - frequent and rapid execution of tests is desirable
  - while developing T-Regx, execution of tests should be possible at any time, in
    rapid successions, even after a single character change in a source code.
- a test **must not** alter another test.
  - execution of a test **must not** influence in any way the execution of another
    test, in particular: by triggering PHP errors (which are preserved between
    executions), by setting errors handlers which influence exceution of
    error-sensitive tests, set any state between tests.
  - a great deal of dedication and care must be put to tests which cover error
    handling functionallity in T-Regx. T-Regx library operates on errors and
    error handlers, and proper coverage of such functionallity requires an even
    greater deal of logic around errors and error handlers, in a way that is
    sensitive enough to detect regression, and at the same time is resilient
    enough not to influence other tests.

### Category `Feature\ `

Standard T-Regx tests are in `Feature\ ` folder. Tests in `Feature\ ` folder meet
certain criteria that we believe increase the quality of the library.

Criteria for tests in `Feature\ ` must follow:

- a test in `Feature\ ` should cover a very narrow part of functionallity. An ideal
  test covers as little piece of feature as possibile, provided the piece of feature
  is non-trivial. Any test that can be split into two more specific tests, should be
  split.

- tests in `Feature\ ` should be groupped by their relation to the `Pattern` class.
- a test covering a particular function or method should be placed in a directory
  with named as the covered function or method (e.g. `flatMap\ `, `match\ `, `stream\ `).
- a test covering other part of functionallity but not pertaining for a particular
  method must be placed in a directory prefixed with `_` (e.g. `_trailing_backslash`,
  `_noAutoCapture`, `_jit`).

### Category `Supposition\ `

T-Regx handles varying and changing behaviours of PHP, and so certain features of the
library rely on a particular behaviour of a given PHP function. Tests in category
`Supposition\ ` assert that very behaviour, so when the behaviour is prooved to change,
the tests can recognize the improper assumption, as opposed to a simple bug in the
library.

Criteria for tests in `Supposition\ ` must follow:

- a test in `Supposition\ ` must not use or reference any T-Regx logic.
- a test in `Supposition\ ` must only assert the current behaviour of PHP.

### Category `Structure\ `

Tests in category `Structure\ ` verify the hierarchy of T-Regx exceptions and whether
the correct relation of the exceptions is followed for the sake of more general
or specific `try`/`catch`.

### Category `Legacy\ `

A previously used category, but now deprecated and planned to be removed in the future.
Tests in category `Legacy\ ` break the encapsulation of T-Regx library and slows down
refactoring of many features.

No new tests should be added into that category.
