#!/bin/bash

# variables
certifier="sylvester@diesellaptops.com"
url="testapi.server.diesellaptops.com"
folder=$(date +%s)
home=/home/ubuntu/testing
app=$home/clones/$folder
current=$home/current
serverHome=/etc/nginx
serverConf=$serverHome/sites-available/$url.conf
serverConfSymLn=$serverHome/sites-enabled/$url.conf

# rename the clone to current epoch
sudo mv $home/clones/new-clone $app
# remove cloned storage folder 
sudo rm -r $app/storage
# create sym link for .env & storage
sudo ln -s $home/.env $app/.env
sudo ln -s $home/storage $app/storage
# install dependencies
composer install -d $app
# set up project
php $app/artisan migrate

# remove old project sym-link and create sym-link to fresh installation
sudo rm -r $current
sudo ln -s $app $current

# refresh server configuration
sudo rm $serverConf 
sudo rm $serverConfSymLn
sudo cp $app/nginx.conf  $serverConf
sudo ln -s $serverConf $serverConfSymLn
# restart the server
sudo service nginx restart


# ssl & redirect https
# add 2 months to date:   now=$(date -d "+2 month" +%s)
lastCert=$home/.lastCert
now=$(date +%s)
if test -f "$lastCert"; then
  last=$(head -n 1 $lastCert | tail -1)

  if [ "$now" -gt "$last" ]; then 
    echo "we need to renew!"
  fi
else 
  sudo certbot run -n --nginx --agree-tos -d $url  -m  $certifier  --redirect
  echo now > lastCert
fi

sudo service nginx restart

# previous installation clean up
# first ensure archives folder exists
sudo mkdir -p $home/archives
toArchive=$home/.toArchive
while IFS= read -r line
do
  sudo zip -r $home/clones/$line.zip $home/clones/$line
  sudo mv $home/clones/$line.zip $home/archives/
  sudo rm -r $home/clones/$line
done < "$toArchive"
sudo rm $toArchive

# add this installation to be archived next
sudo echo $folder > $toArchive

# delete previous installation zips older than the newest 2
toDelete=$home/.cleanup
sudo ls $home/archives -t | tail -n +3 > $toDelete
while IFS= read -r line
do
  sudo rm $home/archives/$line
done < "$toDelete"
sudo rm $toDelete


