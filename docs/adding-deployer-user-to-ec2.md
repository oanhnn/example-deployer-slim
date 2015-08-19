# Adding a deployer user to Amazon EC2 Server

## Login by default user

```
$ ssh -i generatedKey.pem ec2-user@xx.xx.xxx.xxx
```

## Create a new deployer user

```
$ sudo adduser deployer
$ sudo passwd deployer
```

### Edit user privileges

```
$ sudo visudo
```

**Add this to the last line**

```
#deployer user
deployer ALL=(ALL:ALL) ALL
deployer ALL=(ALL) NOPASSWD:/etc/init.d/php5-fpm *, /usr/sbin/service php5-fpm *
```

## Creating public and private keys

```
$ sudo su deployer
$ cd /home/deployer
$ ssh-keygen -b 2048 -f deployer -t RSA -C deployer@xx.xx.xxx.xxx
$ mkdir .ssh
$ chmod 700 .ssh
$ cat deployer.pub > .ssh/authorized_keys
$ chmod 600 .ssh/authorized_keys
$ sudo chown deployer:deployer .ssh
$ sudo chown deployer:deployer .ssh/authorized_keys
```

## Downloading your private key

```
$ sudo mv /home/deployer/deployer /home/ec2-user/
$ sudo mv /home/deployer/deployer.pub /home/ec2-user/
$ sudo chmod 0644 /home/ec2-user/deployer
$ sudo chmod 0644 /home/ec2-user/deployer.pub
```

**On your local machine**

```
$ scp -i generatedKey.pem ec2-user@xx.xx.xxx.xxx:/home/ec2-user/deployer deployer.pem
$ scp -i generatedKey.pem ec2-user@xx.xx.xxx.xxx:/home/ec2-user/deployer.pub deployer.pub
```

**On EC2**

```
$ sudo rm /home/ec2-user/deployer
$ sudo rm /home/ec2-user/deployer.pub
```

## Test it out

```
$ ssh -i deployer.pem deployer@xx.xx.xxx.xxx
```
