# How-To: Deploying Safi to Nginx or Caddy

Configure production web servers to route HTTP requests through `public/index.php`.

---

## Nginx Configuration

```nginx
server {
    listen 80;
    server_name example.com;
    root /var/www/safi/public;

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.5-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

---

## Caddy Configuration

```caddy
example.com {
    root * /var/www/safi/public
    php_fastcgi unix//run/php/php8.5-fpm.sock
    file_server
}
```
