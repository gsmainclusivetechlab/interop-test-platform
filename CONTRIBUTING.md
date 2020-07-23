# Contributing to the GSMA Interoperability Test Platform

We would love your input! We want to make contributing to this project as easy
as possible, whether it's:

-   Reporting a bug
-   Discussing the current state of the code
-   Submitting a fix
-   Proposing new features

## Report bugs using Github's [issues](https://github.com/gsmainclusivetechlab/interop-test-platform/issues)

We use GitHub issues to track public bugs. Report a bug by
[opening a new issue](https://github.com/gsmainclusivetechlab/interop-test-platform/issues/new/choose);
it's that easy!

### Write bug reports with detail

Ensure that your report clearly indicates both the behaviour that you _expect_
to see, and the behaviour that you are _actually_ seeing. Ideally, please also
include a list of steps to reproduce the error you are seeing.

If you would like to suggest a new feature or discuss a problem with the code,
follow the same process as to report a bug.

## Submit changes using [Git Flow](https://nvie.com/posts/a-successful-git-branching-model/)

All code changes must happen through GitHub pull requests:

1. Fork the repo and create your branch from `develop`.

2. If you've added code that should be tested, add tests.

3. Update the documentation by creating a parallel pull request on the
   [docs repository](https://github.com/gsmainclusivetechlab/interop-docs).

4. [Lint your code](#use-a-consistent-coding-style).

5. Rebase your branch onto the latest `develop`.

6. Ensure the test suite [passes](./README.md#running-tests).

7. [Create your pull request](https://github.com/gsmainclusivetechlab/interop-test-platform/compare)!

Try to ensure that your pull request is focussed, and aim for one succinct
commit. For example, squash commits like 'oops, fix typo/bug' into the parent
commit. You can use `git rebase -i` to clean up the commit history before
submitting the pull request. If you'd like to preview the pull request before
it's complete, feel free to create it early and mark it as a "draft" until
you've finished.

Include the message `Closes gsmainclusivetechlab/interop-test-platform#123`
(replacing `123` with the issue number for the feature you are working on)
inside your commit message so that Github links the issue and tracks progress
correctly. You can do that same inside the pull request description, to ensure
that your work doesn't go un-noticed!

Changes which are merged into the `develop` branch are continuously deployed to
our [staging server](https://staging.interop.gsmainclusivetechlab.io).
Periodically, the project maintainers will create a release tag and merge
`develop` into `master`. When this happens, the project will be continuously
deployed to the [production server](https://interop.gsmainclusivetechlab.io).

### Use a Consistent Coding Style

To ensure that our pull requests aren't full of nit-picking comments and still
ensure consistent code style, we use `prettier` to enforce consistency. To fix
any style errors, simply run `npm run lint` from the top-level of the project.

### Any contributions you make will be under the MIT Software License

In short, when you submit code changes, your submissions are understood to be
under the same [MIT License](./LICENSE) that covers the project. Feel free to
contact the maintainers if that's a concern.
