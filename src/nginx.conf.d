server {
        listen 80;
        server_name localhost;

        root /usr/share/nginx/html/BC;
        index index.php;

        location / {
                rewrite ^/$ /index.php last;
                rewrite ^(/.*)(/.*)$ /index.php last;
                return 404;
        }

        location /src {
                root /usr/share/nginx/html/BC;
        }

        error_page 404 /404.html;
        error_page 500 502 503 504 /50x.html;

        location = /404.html {
                root /usr/share/nginx/html;
        }

        location = /50x.html {
                root /usr/share/nginx/html;
        }

        location ~ \.php {
                try_files $uri =403;
                fastcgi_pass unix:/var/run/php-fpm/php-fpm.sock;
                fastcgi_index index.php;
                fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
                include fastcgi_params;
        }

}