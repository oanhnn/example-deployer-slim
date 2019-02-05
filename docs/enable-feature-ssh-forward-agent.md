1. On your local machine, content file `~/.ssh/config` : 
   ```
   Host <your-server-ip>
     ForwardAgent yes
   ```
    Note: if on your team, each user connects using their own server account, you should also each specify `User <your-server-user>` here in the config instead of with a deployer `->user('username')` call (which would require a single server account _username_ to be used by the whole team).
    > it’s better to omit information such as user, port, identityFile, forwardAgent and use it from the ~/.ssh/config file instead —[Deployer docs 'Hosts'](https://deployer.org/docs/hosts.html)
    
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

Note: Some servers enaable AllowAgentForwarding by default, in which case it will be allowed unless there is an entry `AllowAgentForwarding no`, so this step may not be necessary.

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
       ->user('username')  // Omit if specifying User in ssh config
       ->stage(['dev'])
       ->env('deploy_path', '/var/www/apps/yourappname')
       ->env('branch', 'master')
   ```

Wish you success !!!   

Refs:   
https://developer.github.com/guides/using-ssh-agent-forwarding/
