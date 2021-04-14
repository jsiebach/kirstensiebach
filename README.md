## Setting up the server

1. If necessary, create a new server running Ubuntu 20.04 (tested). This server should be accessible via SSH (disable your root password bro) as Github Actions will be set up to do deployments
2. Set up nginx/phpfpm/mysql/git
```
sudo apt install software-properties-common
sudo add-apt-repository ppa:ondrej/php
sudo apt update
sudo apt install php8.0-fpm

# test with
systemctl status php8.0-fpm
```

```
sudo apt install nginx

# test with
systemctl status nginx
```

```
sudo apt install mysql-server

# to install fresh
sudo mysql_secure_installation

# create a user for mysql for the site. mysql_native_password is the way to go for laravel/php apps as they take issue with sha2
sudo mysql
CREATE USER username@localhost IDENTIFIED WITH mysql_native_password BY xxpasswordxx;
```

```
sudo apt install git
git --version
```

```
# install composer per https://getcomposer.org/download/
```

```
# install node
curl -sL https://deb.nodesource.com/setup_14.x | sudo -E bash -
sudo apt-get install nodejs

#check with
node -v
npm -v
```

