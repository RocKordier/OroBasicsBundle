# OroBasicsBundle

## Git
- Commit message language: English
- Conventional Commits, releases are created by semantic-release on merge of `develop` into `main`
- Pre-commit gates: `vendor/bin/phpunit`, `vendor/bin/phpstan analyse -c phpstan.neon --memory-limit=1G`, `vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.php --dry-run`
