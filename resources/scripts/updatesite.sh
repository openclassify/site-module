#!/bin/bash

# Backup composer.json
mv composer.json composerbackup.json 2>/dev/null

# Backup config & app folder
yes | cp -rT config configBackup 2>/dev/null
yes | cp -rT app appBackup 2>/dev/null
echo "--> Backup: composer.json & app/ & config/ ..."

# Update GIT Source
git pull origin master
echo "--> Updated GIT Source..."

# Restore composer.json & config & app
if [ -e composerbackup.json ] ; then

    cp composerbackup.json composer.json 2>/dev/null
    mv -f configBackup/* config/
    mv -f appBackup/* app/
    echo "--> Restore: composer.json & app/ & config/ ..."
fi

# Composer Update
/usr/bin/php7.4 -d memory_limit=-1 /usr/local/bin/composer update -n
echo "--> Composer updated..."

# Flushed bootstrap caches
rm -rf bootstrap/cache/*.php
/usr/bin/php7.4 artisan cache:clear
echo "--> Laravel bootstrap cache flushed..."

# Run CLI commands
/usr/bin/php7.4 artisan migrate --all-addons --force
/usr/bin/php7.4 artisan assets:clear
/usr/bin/php7.4 artisan cache:clear
/usr/bin/php7.4 artisan view:clear
/usr/bin/php7.4 artisan refresh
echo "--> Openclassify artisan refreshed..."


# Fix Permissions
sudo chmod -R 775 bootstrap/*
sudo chmod -R -f 777 storage
sudo chmod -R 775 public/*
echo "--> Fixed permissions..."


FILE=storage/streams/default/oauth-private.key
if test -f "$FILE"; then
    sudo chmod 600 storage/streams/default/oauth-private.key
    sudo chmod 600 storage/streams/default/oauth-public.key
    echo "--> Fixed oauth keys permission..."
	echo "$FILE exists."
fi

echo ">>> Completed Updated! <<<"