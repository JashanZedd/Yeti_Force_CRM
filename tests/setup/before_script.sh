sudo wget https://phar.phpunit.de/phpunit-7.0.2.phar
sudo chmod +x phpunit-7.0.2.phar
sudo mv phpunit-7.0.2.phar /usr/local/bin/phpunit
/usr/local/bin/phpunit --version
composer install
yarn install --force --modules-folder "./public_html/libraries"
sudo wget https://scrutinizer-ci.com/ocular.phar
sudo chmod +x ocular.phar
sudo mv ocular.phar /usr/local/bin/ocular
mysql -e "SET GLOBAL sql_mode = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION'"
mysql -e "create database IF NOT EXISTS yetiforce;" -uroot
cp tests/setup/config.inc.txt config/config.inc.php
cp tests/setup/debug.txt config/debug.php
cp tests/setup/developer.txt config/developer.php
cp tests/setup/api.txt config/api.php
cp tests/setup/.htaccess .htaccess
# cp vendor/phpunit/phpunit-selenium/PHPUnit/Extensions/SeleniumCommon/phpunit_coverage.php phpunit_coverage.php
