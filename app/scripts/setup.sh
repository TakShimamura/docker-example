#!/bin/bash

#####################################################
#
#     MAKE SURE TO UPDATE YOUR appspec.yml file
#
#####################################################

##################################
#         variables
##################################

laravelFramework="6"

url="staging.api.users.diesellaptops.com"
appName="user-repo"
s3envFile="users.env"
database="api_user_repo"

certifier="sylvester@diesellaptops.com"
password=""
user="ubuntu"
pgsqlUser="postgres"
s3bucket="s3://dl-staging-server"

cloneFolder=$(date +%s)
home="/home/$user/apis/$appName"
app="$home/clones/$cloneFolder"
current="$home/current"
serverHome="/etc/nginx"
serverConf="$serverHome/sites-available/$url.conf"
serverConfSymLn="$serverHome/sites-enabled/$url.conf"


##################################
#           Tasks
##################################

# create database if not exists.
dbStored=".database"
if ! test -f $home/$dbStored; then
  sudo -u $pgsqlUser createdb $database
  echo $database > $home/$dbStored
fi

# rename the clone to current epoch
sudo mv $home/clones/new-clone $app
# create base storage structure if it does not exist.
if ! test -d "$home/storage"; then
  sudo cp -r $app/storage $home/storage
  sudo chmod -R 777 $home/storage
fi
# remove cloned storage folder 
sudo rm -r $app/storage
# if .env file is not present check s3 bucket
if ! test -f "$home/.env"; then
  sudo aws s3 cp $s3bucket/envs/$s3envFile $home/.env
fi
# create sym link for .env & storage
sudo ln -s $home/.env $app/.env
sudo ln -s $home/storage $app/storage

# install dependencies & composer
composer install -d $app
if ["$laravelFramwork" -lt "7"]; then
  php $app/artisan key:generate
fi
# set up project
php $app/artisan migrate fresh

# remove old project sym-link and create sym-link to fresh installation
sudo rm -r $current
sudo ln -s $app $current

# refresh server configuration & ssl
sudo rm $serverConf 
sudo rm $serverConfSymLn
toReplace="/"
substitution="\/"
rootLocation="${current//$toReplace/$substitution}\/public"
sed -i "s/^.*server_name .*$/    server_name $url;/" $app/nginx.conf
sed -i "s/^.*root .*$/    root $rootLocation;/" $app/nginx.conf
sudo cp $app/nginx.conf  $serverConf
sudo ln -s $serverConf $serverConfSymLn
# set up ssl and restart server
sudo certbot run -n --nginx --agree-tos -d $url  -m  $certifier  --redirect
sudo service nginx restart

# ssl & redirect https
# add 2 months to date:   now=$(date -d "+2 month" +%s)
# lastCert=$home/.lastCert
# renewCert=$home/.renewCert
# now=$(date +%s)
# if test -f "$lastCert"; then
#   last=$(head -n 1 $lastCert | tail -1)

#   if [ "$now" -gt "$last" ]; then 
#     echo $serverConf > $renewCert
#   fi

# else 
  # sudo certbot run -n --nginx --agree-tos -d $url  -m  $certifier  --redirect
#   echo now > $lastCert
# fi

# sudo service nginx restart

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
sudo echo $cloneFolder > $toArchive

# delete previous installation zips older than the newest 2
toDelete=$home/.cleanup
sudo ls $home/archives -t | tail -n +3 > $toDelete
while IFS= read -r line
do
  sudo rm $home/archives/$line
done < "$toDelete"
sudo rm $toDelete


