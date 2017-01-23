ServerName {{app.domain}}:80
<VirtualHost *:80>
    ServerAdmin webmaster@{{app.domain}}
    ServerName {{app.domain}}

    DocumentRoot {{deploy_path}}/current/public
    <Directory {{deploy_path}}/current/public>
        Options Indexes FollowSymLinks MultiViews
        AllowOverride All

        # Basic authenticate
        AuthType Basic
    	  AuthName "This is a protected site"
        AuthUserFile /etc/httpd/.htpasswd
    	  Require valid-user

        #<RequireAll>
        #    Require all granted
        #</RequireAll>
    </Directory>

    ErrorLog "|/usr/sbin/rotatelogs {{deploy_path}}/tmp/logs/httpd-error.log.%Y-%m-%d-%H_%M_%S 5M"

    # Possible values include: debug, info, notice, warn, error, crit,
    # alert, emerg.
    LogLevel warn

    CustomLog "|/usr/sbin/rotatelogs -l {{deploy_path}}/tmp/logs/httpd-access.log.%Y.%m.%d 86400" combined
</VirtualHost>
