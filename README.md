# Framework
Welcome to my project!
It's my first simply framework 

## Installation
For install this Framework copy this content into your hosts web directory and create .htaccess file
``` apacheconf
Options +FollowSymLinks
IndexIgnore */*
RewriteEngine on
RewriteCond %{REQUEST_URI} !^/(web)
RewriteRule (.*) /web/$1
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /web/index.php
```
or edit yor virtual hosts file
