#!/bin/bash

# Docuemnt root of the setup
ROOT='./'


#--------------------------------------------------------
echo "Setup Started at: " + $(date)

cd $ROOT

echo "Running: setup:upgrade" + $(date)
bin/magento setup:upgrade

echo "Clear cache and generated content" + $(date)
rm -rf $ROOT/pub/static/* $ROOT/var/cache/* $ROOT/var/page_cache/* $ROOT/var/view_preprocessed/* $ROOT/generated/code/* $ROOT/generated/metadata/*

echo "Running: di:compile" + $(date)
bin/magento setup:di:compile

echo "Running: static-content:deploy" + $(date)
bin/magento setup:static-content:deploy -f

echo "Running: cache:flush" + $(date)
# bin/magento cache:enable
bin/magento cache:flush

echo "updating ownership and permissions" + $(date)
chown -R adminstrator:www-data .
chmod -R 777 .

echo "Completed at: " + $(date)
