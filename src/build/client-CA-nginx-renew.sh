#!/bin/sh

sleep 30

NEW_CA_CLIENTS_CERTS=/var/www/html/storage/app/certificates/certificates.pem
NEW_CA_CLIENTS_CERTS_FAILED=/var/www/html/storage/app/certificates/NGINX-ERROR
NGINX_CA_CLIENTS_CERTS=/etc/nginx/ssl/client-certs/CA.crt

if [ -f $NEW_CA_CLIENTS_CERTS ]
    then
        echo "TRYING TO INSTALL NEW CERT"
        mv $NGINX_CA_CLIENTS_CERTS /tmp/CA.crt_backup
        mv $NEW_CA_CLIENTS_CERTS $NGINX_CA_CLIENTS_CERTS
        nginx -t 2> /tmp/nginx_status
        ERR=`cat /tmp/nginx_status`

        if [[ $(expr match "$ERR" '.*test failed.*') != 0 ]]
            then
                echo "ERROR"
                mv $NEW_CA_CLIENTS_CERTS $NEW_CA_CLIENTS_CERTS_FAILED
                mv /tmp/CA.crt_backup $NGINX_CA_CLIENTS_CERTS
                echo $ERR >> $NEW_CA_CLIENTS_CERTS_FAILED
        fi
        if [[ $(expr match "$ERR" '.*test is successful.*') != 0 ]]
            then
                echo "SUCCESS"
                nginx -s reload
                rm /tmp/CA.crt_backup
        fi
        rm /tmp/nginx_status
fi
