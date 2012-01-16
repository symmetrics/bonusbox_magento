# Bonusbox's magento plugin tests

You need to have phpunit installed on your system. 

First of all copy/link the folder test to the magento base folders.

Run the module tests with: 
`/usr/bin/php path/to/magento-version/test/phpunit.php --configuration case/phpunit.xml`

Run the integration tests with: 
`/usr/bin/php path/to/magento-version/test/phpunit.php --configuration integration/phpunit.xml`

In order to test all magento version run: 
`/usr/bin/php path/to/magento-version/test/case/phpunit-all.php versions_basedir=path/to/mageto-subdir --configuration case/phpunit.xml`.

Add `--coverage-html path/to/report-folder` to enable code coverage.

e.g. `/usr/bin/php ./magento-head/test/phpunit.php --configuration ./magento-plugin/test/case/phpunit.xml` from `/home/magento/www`