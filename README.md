# PHP Example files

I create symbolic links from /var/www/html to this repository
to track the PHP files but still have them available to the 
web server. It is best to not keep the repository in a home directory
since the permissions are more complicated. I put them in 
/etc/myphp. The PHP files are owned by my user but part of 
the www-data group so that Apache has access to them. The 
directory also needs these permissions. The PHP files are set
to permissions of 640.
```
sudo ln -s /path/to/original_target /path/to/symlink_name
sudo chown seth /etc/myphp
sudo chgrp www-data /etc/myphp
sudo chmod 640 *.php
```
