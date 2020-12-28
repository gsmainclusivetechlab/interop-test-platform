#!/bin/sh
if [ "${HTTPS_SSL_CERT}" = 'certbot' ]; then
    FILE_CERT=$(cat /etc/nginx/ssl/letsencrypt/live/${PROJECT_DOMAIN}/fullchain.pem  | openssl x509 -noout -dates | grep notAfter | sed -e 's?.*=??g;s?GMT.*??;s? $??g')
    NGINX_CERT=$(curl --insecure -vvI https://localhost:8443 2>&1 | awk 'BEGIN { cert=0 } /^\* SSL connection/ { cert=1 } /^\*/ { if (cert) print }' | grep expire | sed 's?.*: ??g;s?GMT.*??;s? $??g')
    FILE_CERT_DATE=$(date --date="$FILE_CERT" --utc +"%s")
    NGINX_CERT_DATE=$(date --date="$NGINX_CERT" --utc +"%s")
    echo $FILE_CERT_DATE
    echo $NGINX_CERT_DATE
    if [ "$FILE_CERT_DATE" -gt "$NGINX_CERT_DATE" ]; then
        echo "RELOADING NGNIX TO RENEW CERT"
        nginx -s reload
    fi
fi
