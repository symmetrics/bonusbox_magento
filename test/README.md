# Bonusbox's magento plugin unit tests

You need to have phpunit installed on your system. 

Run the modul tests with: php path/to/magento/test/case/phpunit.php --configuration case/phpunit.xml
Run the integration tests with: php path/to/magento/test/case/phpunit.php --configuration case/phpunit.xml

In order to test all magento version run: php path/to/magento/test/case/phpunit.php versions_basedir=path/to/mageto-subdir --configuration case/phpunit.xml.

Add --coverage-html path/to/report-folder to enable code coverage.