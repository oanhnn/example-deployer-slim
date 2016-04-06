Using [Deployer][] to deploy [Laravel][] application on [Homestead][]
---

1. Install [vagrant][] and [virtualbox][] if not installed.

2. Add box `laravel/homestead` into vagrant if not added.

   ```shell
   $ vagrant box add laravel/homestead
   ```

3. [Generate your public and private key][key-generate] if not existed.

4. Init project

   ```shell
   $ cd /path/to/project
   $ mkdir key && 
   $ cp /path/to/public_key key/
   $ cp /path/to/private_key key/
   $ composer require deployer/deployer
   $ composer require laravel/homestead --dev
   ```

5. Make `Homestead.yaml` and `Vagrantfile` by command:

   ```shell
   $ ./vendor/bin/homestead make
   ```

   Two files `Homestead.yaml` and `Vagrantfile` are created in root directory of project.
   Edit your `Homestead.yaml` file like:

   ```yml
   ---
   ip: "192.168.10.10"                 # your homestead's machine IP
   memory: 1024
   cpus: 1
   hostname: deployer-on-homestead     # your homestead's hostname
   name: deployer-on-homestead         # your homestead's machine name
   provider: virtualbox
   authorize: "./key/id_rsa.pub"       # your public key
   keys:
       - "./key/id_rsa"                 # your private key
   folders:
       - map: "./"
         to: "/home/vagrant/deployer-on-homestead"
   sites: []

   ```

6. Make your `deploy.php` file follow documents of Deployer or [deployer-example][]

7. Make your `Homestead` and run deployer

   ```shell
   $ vagrant up
   $ vagrant ssh
   $ cd /home/vagrant/deployer-on-homestead
   $ ./vendor/bin/dep deploy <stage>
   ```

**Goodluck!** :smile:

All source code is availabled in [here][example-source]


[Homestead]:        https://laravel.com/docs/5.2/homestead
[Laravel]:          https://laravel.com/
[Deployer]:         http://deployer.org/
[vagrant]:          https://www.vagrantup.com/
[virtualbox]:       https://www.virtualbox.org/
[deployer-example]: https://github.com/oanhnn/deployer-example
[example-source]:   https://github.com/oanhnn/deployer-on-homestead
[key-generate]:     https://help.github.com/articles/generating-a-new-ssh-key-and-adding-it-to-the-ssh-agent/
