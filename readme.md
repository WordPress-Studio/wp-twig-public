# WP theme development workflow with timber and twig
**Story for making this theme ** 

As a frontend developer I have to achieve complex design make with wordpress theme. Client's requirement from wordpress 
theme at least modify wordpress content, images. 

So I believe timeline is very important to complete a project. So I create this theme, I can achieve any custom design 
with this theme. As a frontend developer don't need to learn PHP but need to learn little bit template syntax of TWIG + wordpress template structure is enough. To create a wordpress theme from scratch with this template

## Features 
- Wordpress theme options with - Redux Option Panel 
- Page Custom Meta box with CMB2
- Bootstrap CSS Configured 

## Docker command
Inside docker run bash script 

docker exec -it {id} bash 


## WP CLI commands 
wp core download --allow-root
wp config create --dbname=testdb --dbuser=testuser --dbpass=testpassword --dbhost=db --dbprefix=wp_  --allow-root <<PHP
define( 'WP_DEBUG', true );
define( 'WP_MEMORY_LIMIT', '128M' );
define('FS_METHOD', 'direct');
PHP

wp core install  --url="http://localhost" --title="Your Blog Title" --admin_user="you@example.com" --admin_password="admin" --admin_email="you@example.com" --allow-root
wp plugin install woocommerce  --allow-root
wp plugin activate woocommerce  --allow-root
wp plugin delete akismet hello  --allow-root



wp db drop --allow-root
wp db create --allow-root


