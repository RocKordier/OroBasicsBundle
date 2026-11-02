OroBasicsBundle
===============

[![CI](https://github.com/RocKordier/OroBasicsBundle/actions/workflows/ci.yml/badge.svg?branch=main)](https://github.com/RocKordier/OroBasicsBundle/actions/workflows/ci.yml)
[![Coverage](https://codecov.io/gh/RocKordier/OroBasicsBundle/branch/main/graph/badge.svg)](https://codecov.io/gh/RocKordier/OroBasicsBundle)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%20max-brightgreen.svg)](https://phpstan.org/)
[![semantic-release](https://img.shields.io/badge/semantic--release-angular-e10079?logo=semantic-release)](https://github.com/semantic-release/semantic-release)

[![Latest Stable Version](https://img.shields.io/packagist/v/ehdev/oro-basics-bundle)](https://packagist.org/packages/ehdev/oro-basics-bundle)
[![Total Downloads](https://img.shields.io/packagist/dt/ehdev/oro-basics-bundle)](https://packagist.org/packages/ehdev/oro-basics-bundle)
[![PHP Version](https://img.shields.io/packagist/dependency-v/ehdev/oro-basics-bundle/php)](https://packagist.org/packages/ehdev/oro-basics-bundle)
[![License](https://img.shields.io/packagist/l/ehdev/oro-basics-bundle)](LICENSE)

Basic configuration, helper and tools for an oro application

Installation
------------

With composer, add:

```bash
composer require ehdev/oro-basics-bundle:^7.0
```

| Bundle | Oro Platform | PHP  |
|--------|--------------|------|
| 7.x    | 7.0          | 8.5  |
| 6.0.x  | 6.0          | 8.3+ |
| 5.1.x  | 5.1          | 8.2+ |
| 5.0.x  | 5.0          | 8.1  |
| 4.1.x  | 3.1 / 4.1    | 7.3+ |

Components
----------
[Commands](./src/Resources/doc/Commands.md)

[Form / Extensions](./src/Resources/doc/Form/Extensions.md)

[Migration / Enum](./src/Resources/doc/Migration/Enum.md)

Development
-----------

* `develop` is the development branch. Open pull requests against `develop`.
* `main` holds the released code. Merging `develop` into `main` publishes a new release.
* Every push and pull request runs PHPUnit, PHPStan (level max) and PHP CS Fixer.

```bash
make phpunit   # tests with coverage
make stan      # static analysis
make cs        # fix coding style
```

### Releases

Releases are created automatically by [semantic-release](https://github.com/semantic-release/semantic-release)
when `develop` is merged into `main`. The version is derived from the
[Conventional Commits](https://www.conventionalcommits.org/) since the last release:

| Commit                                             | Release |
|----------------------------------------------------|---------|
| `fix: ...`                                         | patch   |
| `feat: ...`                                        | minor   |
| `feat!: ...` or a `BREAKING CHANGE:` footer        | major   |
| `docs:`, `chore:`, `ci:`, `test:`, `refactor:` ... | none    |

Merge `develop` into `main` with a merge commit (not squash), so that every commit is
analysed. The release notes are published as [GitHub Releases](https://github.com/RocKordier/OroBasicsBundle/releases),
Packagist picks up the new tag through its GitHub hook.
