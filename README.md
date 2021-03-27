# Coverage Enforcer

[![builds.sr.ht status](https://builds.sr.ht/~ancarda/coverage-enforcer.svg)](https://builds.sr.ht/~ancarda/coverage-enforcer?)

Coverage Enforcer is a tiny command line tool that enforces code coverage
metrics. Intended to be used in Continuous Integration (CI) systems to prevent
code coverage from falling to unacceptable levels.

Pull down with composer:

    composer require --dev ancarda/coverage-enforcer

Then just invoke like so:

    vendor/bin/enforce-coverage --minStmtCov=100 <path to clover XML file>

## Useful Links

* Source Code:   <https://git.sr.ht/~ancarda/coverage-enforcer>
* Issue Tracker: <https://todo.sr.ht/~ancarda/coverage-enforcer>
* Mailing List:  <https://lists.sr.ht/~ancarda/coverage-enforcer>
