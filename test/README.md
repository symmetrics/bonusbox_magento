# Bonusbox's magento plugin tests

You need to have phpunit installed on your system. 

First of all copy/link the folder test to the magento base folders.

* Run the modul tests with: `php path/to/magento-version/test/case/phpunit.php --configuration case/phpunit.xml`
* Run the integration tests with: `php path/to/magento-version/test/case/phpunit.php --configuration case/phpunit.xml`

In order to test all magento version run: `php path/to/magento-version/test/case/phpunit-all.php versions_basedir=path/to/mageto-subdir --configuration case/phpunit.xml`.

The configuration can be set in case/phpunit.xml or integration/phpunit.xml.

Add `--coverage-html path/to/report-folder` to enable code coverage. 