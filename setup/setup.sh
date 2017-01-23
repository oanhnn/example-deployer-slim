#!/bin/bash
export cf_timezone="Asia/Tokyo"
export cf_username="dev"
export cf_password="dev@2017"
# Default, SELinux is disabled in Amazon Linux
export cf_firewall_enabled=false
export cf_selinux_enabled=false
export cf_deploy_path="/var/www/apps/slim"

echo ">> Prevent remote access with plaintext password and enable agent forwarding"
sudo sed -i -e "s/^PasswordAuthentication *yes/PasswordAuthentication no/g" /etc/ssh/sshd_config
sudo sed -i -e "s/^#AllowAgentForwarding *yes/AllowAgentForwarding yes/" /etc/ssh/sshd_config
sudo systemctl restart sshd

if [[ $cf_firewall_enabled == true ]]
then
  echo ">> Enable firewalld service"
  sudo systemctl start firewalld
  sudo systemctl enable firewalld
  sudo firewall-cmd --permanent --add-port=22/tcp
  sudo firewall-cmd --permanent --add-port=80/tcp
  sudo firewall-cmd --reload
else
  echo ">> Disable firewalld service"
  sudo systemctl stop firewalld
  sudo systemctl disable firewalld
fi

# Default, SELinux is disabled in Amazon Linux
if [[ $cf_selinux_enabled == true ]]
then
  echo ">> Fix SELinux"
  sudo setsebool -P httpd_can_network_connect 1
  sudo setsebool -P httpd_can_network_connect_db 1
  sudo setsebool -P httpd_can_network_memcache 1
  sudo setsebool -P httpd_anon_write 1
  sudo setsebool -P httpd_sys_script_anon_write 1
  sudo setsebool -P httpd_unified 1
  sudo setsebool -P httpd_can_sendmail 1
else
  echo ">> Disable SELinux"
  sudo setenforce 0
  sudo sed -i 's/SELINUX=\(enforcing\|permissive\)/SELINUX=disabled/g' /etc/sysconfig/selinux
  sudo sed -i 's/SELINUX=\(enforcing\|permissive\)/SELINUX=disabled/g' /etc/selinux/config
fi

echo ">> Install common packages"
sudo yum install -y epel-release
sudo yum update -y
sudo yum install -y wget curl gcc gcc-c++ make git unzip tree openssl \
    gd libxml2 zlib pcre telnet nmap

echo ">> Install 'chmodr' command"
sudo cp -f ./chmodr.sh /usr/bin/chmodr && \
sudo chmod a+x /usr/bin/chmodr

echo ">> Install Apache"
sudo yum -y install httpd24 httpd24-tools

# ensure it is running
sudo service httpd start
sudo chkconfig httpd on --level 2345

echo ">> Install PHP 5.6"
sudo yum install -y php56 php56-cli php56-common \
    php56-gd php56-intl php56-mbstring php56-mcrypt php56-pdo php56-mysqlnd php56-xml

echo ">> Configure and secure PHP"
sudo sed -i -e "s#^;date\.timezone=.*#date.timezone=$cf_timezone#" /etc/php.ini && \
#sudo sed -i -e "s/^;cgi\.fix_pathinfo=.*/cgi.fix_pathinfo=0/" /etc/php.ini && \
sudo sed -i -e "s#^short_open_tag=.*#short_open_tag=On#" /etc/php.ini

echo ">> Install Composer"
curl -sSL https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod a+x /usr/local/bin/composer

echo ">> Create deploy user"
sudo useradd -g apache $cf_username
sudo echo $cf_username:$cf_password | sudo chpasswd

# copy ssh key
sudo mkdir -p /home/$cf_username/.ssh
sudo cp -f ./key/id_rsa /home/$cf_username/.ssh/id_rsa
sudo cp -f ./key/id_rsa.pub /home/$cf_username/.ssh/authorized_keys
sudo chmod 600 /home/$cf_username/.ssh/id_rsa
sudo chmod 644 /home/$cf_username/.ssh/authorized_keys
sudo chown -R $cf_username:apache /home/$cf_username/.ssh/

echo ">> Config Apache and vhost"
touch $cf_deploy_path/shared/app/config/vhost_apache.conf
sudo ln -ns $cf_deploy_path/shared/app/config/vhost_apache.conf /etc/httpd/conf.d/vhost_apache.conf
sudo mkdir -p $cf_deploy_path/current/public
sudo chown -R $cf_username:apache $cf_deploy_path

echo ">> Enable basic authenticate"
sudo htpasswd -cb /etc/httpd/.htpasswd $cf_username $cf_password
sudo service httpd restart

echo ">> Add deploy user to sudoers list"
sudo touch /etc/sudoers.d/$cf_username
sudo echo "$cf_username ALL=(ALL) NOPASSWD:/sbin/service httpd, /etc/init.d/httpd" > /etc/sudoers.d/$cf_username
