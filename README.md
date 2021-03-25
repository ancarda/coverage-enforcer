# Coverage Enforcer

Coverage Enforcer is a tiny command line tool that enforces code coverage
metrics. Intended to be used in Continuous Integration (CI) systems to prevent
code coverage from falling to unacceptable levels.

Pull down with composer:

    composer require --dev ancarda/enforce-coverage

Then just invoke like so:

    vendor/bin/enforce-coverage --minStmtCov=100 <path to clover XML file>
