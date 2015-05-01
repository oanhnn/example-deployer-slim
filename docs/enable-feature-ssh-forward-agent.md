1. On your local machine, content file `~/.ssh/config` : 
   ```
   Host <your-server-ip>
     ForwardAgent yes
   ```

2. On your server, uncomment line `#AllowAgentForwarding yes` in file `/etc/ssh/sshd_config` to allow agent forwarding feature.
   ```
   AllowAgentForwarding yes
   #AllowTcpForwarding yes
   #GatewayPorts no
   #X11Forwarding no
   ```
   Reload sshd service on your server.
   ```shell
   $ sudo service sshd restart
   ```

3. Test forward agent from your local machine.
   ```ssh
   $ ssh-add /path/to/your-key-file
   $ ssh <username>@<server_ip>
   $ ssh-add -l
   ```
   Check your key really loaded.

4. Finally, using `deployer` v3.0 and forward agent feature.

   ```php
   server('your-server', 'xxx.xxx.xxx.xxx', 22)
       ->user('username')
       ->forwardAgent()
       ->stage(['dev'])
       ->env('deploy_path', '/var/www/apps/yourappname')
       ->env('branch', 'master')
   ```

Wish you success !!!   

Refs:   
https://developer.github.com/guides/using-ssh-agent-forwarding/