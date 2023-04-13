#!/bin/bash

set -f

if [ "update" = $CI_JOB_STAGE ]; then
    #fase update server qc
    ssh -o StrictHostKeyChecking=no "${SSH_USER_STAGING}@${SSH_STAGING}" bash <<EOF
    cd /data/users/qc/sources/sippmu
    git pull
    docker exec -it --user $UID sippmu-simkatmuh-1 composer install
EOF
fi
