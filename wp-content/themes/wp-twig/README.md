## Dependent plugins
- Timber : https://wordpress.org/plugins/timber-library/
- Advanced custom fields: https://www.advancedcustomfields.com/resources/

## For the designer
- Twig for Template Designers: https://twig.symfony.com/doc/3.x/templates.html


## What's here?

`static/` is where you can keep your static front-end scripts, styles, or images. In other words, your Sass files, JS files, fonts, and SVGs would live here.

`templates/` contains all of your Twig templates. These pretty much correspond 1 to 1 with the PHP files that respond to the WordPress template hierarchy. At the end of each PHP template, you'll notice a `Timber::render()` function whose first parameter is the Twig file where that data (or `$context`) will be used. Just an FYI.

`bin/` and `tests/` ... basically don't worry about (or remove) these unless you know what they are and want to.

## Other Resources

The [main Timber Wiki](https://github.com/jarednova/timber/wiki) is super great, so reference those often. Also, check out these articles and projects for more info:

* [This branch](https://github.com/laras126/timber-starter-theme/tree/tackle-box) of the starter theme has some more example code with ACF and a slightly different set up.
* [Twig for Timber Cheatsheet](http://notlaura.com/the-twig-for-timber-cheatsheet/)
* [Timber and Twig Reignited My Love for WordPress](https://css-tricks.com/timber-and-twig-reignited-my-love-for-wordpress/) on CSS-Tricks
* [A real live Timber theme](https://github.com/laras126/yuling-theme).
* [Timber Video Tutorials](http://timber.github.io/timber/#video-tutorials) and [an incomplete set of screencasts](https://www.youtube.com/playlist?list=PLuIlodXmVQ6pkqWyR6mtQ5gQZ6BrnuFx-) for building a Timber theme from scratch.




# Important .htaccess rule

<pre style="background-color:#000; color: #fff">
    # ----------------------------------------------------------------------
    # | Cache expiration                                                   |
    # ----------------------------------------------------------------------

    # Serve resources with a far-future expiration date.
    #
    # (!) If you don't control versioning with filename-based cache busting, you
    # should consider lowering the cache times to something like one week.
    #
    # https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Cache-Control
    # https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Expires
    # https://httpd.apache.org/docs/current/mod/mod_expires.html

    <IfModule mod_expires.c>

        ExpiresActive on
        ExpiresDefault                                      "access plus 1 month"

      # CSS

        ExpiresByType text/css                              "access plus 1 year"


      # Data interchange

        ExpiresByType application/atom+xml                  "access plus 1 hour"
        ExpiresByType application/rdf+xml                   "access plus 1 hour"
        ExpiresByType application/rss+xml                   "access plus 1 hour"

        ExpiresByType application/json                      "access plus 0 seconds"
        ExpiresByType application/ld+json                   "access plus 0 seconds"
        ExpiresByType application/schema+json               "access plus 0 seconds"
        ExpiresByType application/geo+json                  "access plus 0 seconds"
        ExpiresByType application/xml                       "access plus 0 seconds"
        ExpiresByType text/calendar                         "access plus 0 seconds"
        ExpiresByType text/xml                              "access plus 0 seconds"


      # Favicon (cannot be renamed!) and cursor images

        ExpiresByType image/vnd.microsoft.icon              "access plus 1 week"
        ExpiresByType image/x-icon                          "access plus 1 week"

      # HTML

        ExpiresByType text/html                             "access plus 0 seconds"


      # JavaScript

        ExpiresByType application/javascript                "access plus 1 year"
        ExpiresByType application/x-javascript              "access plus 1 year"
        ExpiresByType text/javascript                       "access plus 1 year"


      # Manifest files

        ExpiresByType application/manifest+json             "access plus 1 week"
        ExpiresByType application/x-web-app-manifest+json   "access plus 0 seconds"
        ExpiresByType text/cache-manifest                   "access plus 0 seconds"


      # Markdown

        ExpiresByType text/markdown                         "access plus 0 seconds"


      # Media files

        ExpiresByType audio/ogg                             "access plus 1 month"
        ExpiresByType image/apng                            "access plus 1 month"
        ExpiresByType image/avif                            "access plus 1 month"
        ExpiresByType image/avif-sequence                   "access plus 1 month"
        ExpiresByType image/bmp                             "access plus 1 month"
        ExpiresByType image/gif                             "access plus 1 month"
        ExpiresByType image/jpeg                            "access plus 1 month"
        ExpiresByType image/png                             "access plus 1 month"
        ExpiresByType image/svg+xml                         "access plus 1 month"
        ExpiresByType image/webp                            "access plus 1 month"
        ExpiresByType video/mp4                             "access plus 1 month"
        ExpiresByType video/ogg                             "access plus 1 month"
        ExpiresByType video/webm                            "access plus 1 month"


      # WebAssembly

        ExpiresByType application/wasm                      "access plus 1 year"


      # Web fonts

        # Collection
        ExpiresByType font/collection                       "access plus 1 month"

        # Embedded OpenType (EOT)
        ExpiresByType application/vnd.ms-fontobject         "access plus 1 month"
        ExpiresByType font/eot                              "access plus 1 month"

        # OpenType
        ExpiresByType font/opentype                         "access plus 1 month"
        ExpiresByType font/otf                              "access plus 1 month"

        # TrueType
        ExpiresByType application/x-font-ttf                "access plus 1 month"
        ExpiresByType font/ttf                              "access plus 1 month"

        # Web Open Font Format (WOFF) 1.0
        ExpiresByType application/font-woff                 "access plus 1 month"
        ExpiresByType application/x-font-woff               "access plus 1 month"
        ExpiresByType font/woff                             "access plus 1 month"

        # Web Open Font Format (WOFF) 2.0
        ExpiresByType application/font-woff2                "access plus 1 month"
        ExpiresByType font/woff2                            "access plus 1 month"


      # Other

        ExpiresByType text/x-cross-domain-policy            "access plus 1 week"

    </IfModule>

    # ----------------------------------------------------------------------
    # | Compression                                                        |
    # ----------------------------------------------------------------------

    <IfModule mod_deflate.c>

        # Force compression for mangled `Accept-Encoding` request headers
        #
        # https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Accept-Encoding
        # https://calendar.perfplanet.com/2010/pushing-beyond-gzipping/

        <IfModule mod_setenvif.c>
            <IfModule mod_headers.c>
                SetEnvIfNoCase ^(Accept-EncodXng|X-cept-Encoding|X{15}|~{15}|-{15})$ ^((gzip|deflate)\s*,?\s*)+|[X~-]{4,13}$ HAVE_Accept-Encoding
                RequestHeader append Accept-Encoding "gzip,deflate" env=HAVE_Accept-Encoding
            </IfModule>
        </IfModule>

        # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

        # Compress all output labeled with one of the following media types.
        #
        # https://httpd.apache.org/docs/current/mod/mod_filter.html#addoutputfilterbytype

        <IfModule mod_filter.c>
            AddOutputFilterByType DEFLATE "application/atom+xml" \
                                          "application/javascript" \
                                          "application/json" \
                                          "application/ld+json" \
                                          "application/manifest+json" \
                                          "application/rdf+xml" \
                                          "application/rss+xml" \
                                          "application/schema+json" \
                                          "application/geo+json" \
                                          "application/vnd.ms-fontobject" \
                                          "application/wasm" \
                                          "application/x-font-ttf" \
                                          "application/x-javascript" \
                                          "application/x-web-app-manifest+json" \
                                          "application/xhtml+xml" \
                                          "application/xml" \
                                          "font/eot" \
                                          "font/opentype" \
                                          "font/otf" \
                                          "font/ttf" \
                                          "image/bmp" \
                                          "image/svg+xml" \
                                          "image/vnd.microsoft.icon" \
                                          "text/cache-manifest" \
                                          "text/calendar" \
                                          "text/css" \
                                          "text/html" \
                                          "text/javascript" \
                                          "text/plain" \
                                          "text/markdown" \
                                          "text/vcard" \
                                          "text/vnd.rim.location.xloc" \
                                          "text/vtt" \
                                          "text/x-component" \
                                          "text/x-cross-domain-policy" \
                                          "text/xml"

        </IfModule>

        # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

        # Map the following filename extensions to the specified encoding type in
        # order to make Apache serve the file types with the appropriate
        # `Content-Encoding` response header (do note that this will NOT make
        # Apache compress them!).
        #
        # If these files types would be served without an appropriate
        # `Content-Encoding` response header, client applications (e.g.: browsers)
        # wouldn't know that they first need to uncompress the response, and thus,
        # wouldn't be able to understand the content.
        #
        # https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Encoding
        # https://httpd.apache.org/docs/current/mod/mod_mime.html#addencoding

        <IfModule mod_mime.c>
            AddEncoding gzip              svgz
        </IfModule>

    </IfModule>

    # ----------------------------------------------------------------------
    # | Server-side technology information                                 |
    # ----------------------------------------------------------------------

    # Remove the `X-Powered-By` response header that:
    #
    #  * is set by some frameworks and server-side languages (e.g.: ASP.NET, PHP),
    #    and its value contains information about them (e.g.: their name, version
    #    number)
    #
    #  * doesn't provide any value to users, contributes to header bloat, and in
    #    some cases, the information it provides can expose vulnerabilities
    #
    # (!) If you can, you should disable the `X-Powered-By` header from the
    #     language/framework level (e.g.: for PHP, you can do that by setting
    #     `expose_php = off` in `php.ini`).
    #
    # https://php.net/manual/en/ini.core.php#ini.expose-php

    <IfModule mod_headers.c>
        Header unset X-Powered-By
        Header always unset X-Powered-By
    </IfModule>

    # ----------------------------------------------------------------------
    # | File access                                                        |
    # ----------------------------------------------------------------------

    # Block access to directories without a default document.
    #
    # You should leave the following uncommented, as you shouldn't allow anyone to
    # surf through every directory on your server (which may include rather
    # private places such as the CMS's directories).

    <IfModule mod_autoindex.c>
        Options -Indexes
    </IfModule>

    # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    # Block access to all hidden files and directories except for the
    # visible content from within the `/.well-known/` hidden directory.
    #
    # These types of files usually contain user preferences or the preserved state
    # of a utility, and can include rather private places like, for example, the
    # `.git` or `.svn` directories.
    #
    # The `/.well-known/` directory represents the standard (RFC 5785) path prefix
    # for "well-known locations" (e.g.: `/.well-known/manifest.json`,
    # `/.well-known/keybase.txt`), and therefore, access to its visible content
    # should not be blocked.
    #
    # https://www.mnot.net/blog/2010/04/07/well-known
    # https://tools.ietf.org/html/rfc5785

    <IfModule mod_rewrite.c>
        RewriteEngine On
        RewriteCond %{REQUEST_URI} "!(^|/)\.well-known/([^./]+./?)+$" [NC]
        RewriteCond %{SCRIPT_FILENAME} -d [OR]
        RewriteCond %{SCRIPT_FILENAME} -f
        RewriteRule "(^|/)\." - [F]
    </IfModule>

    # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    # Block access to files that can expose sensitive information.
    #
    # By default, block access to backup and source files that may be left by some
    # text editors and can pose a security risk when anyone has access to them.
    #
    # https://feross.org/cmsploit/
    #
    # (!) Update the `<FilesMatch>` regular expression from below to include any
    #     files that might end up on your production server and can expose
    #     sensitive information about your website. These files may include:
    #     configuration files, files that contain metadata about the project (e.g.:
    #     project dependencies, build scripts, etc.).

    <IfModule mod_authz_core.c>
        <FilesMatch "(^#.*#|\.(bak|conf|dist|fla|in[ci]|log|orig|psd|sh|sql|sw[op])|~)$">
            Require all denied
        </FilesMatch>
    </IfModule>

    # ----------------------------------------------------------------------
    # | Server software information                                        |
    # ----------------------------------------------------------------------

    # Prevent Apache from adding a trailing footer line containing information
    # about the server to the server-generated documents (e.g.: error messages,
    # directory listings, etc.).
    #
    # https://httpd.apache.org/docs/current/mod/core.html#serversignature

    ServerSignature Off


</pre>