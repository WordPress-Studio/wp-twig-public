# WP theme development workflow with timber and twig

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
