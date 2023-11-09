#!/bin/sh

echo $@

docker exec -t mcatalog_php vendor/friendsofphp/php-cs-fixer/php-cs-fixer fix --config=.php-cs-fixer.dist.php $@