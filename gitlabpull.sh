#!/bin/bash

set -f

if [ "update" = $CI_JOB_STAGE ]; then
    #fase update server qc
    ssh -o StrictHostKeyChecking=no "${SSH_USER_STAGING}@${SSH_STAGING}" bash <<EOF
    cd /data/users/qc/sources/pwm-auth/ci
    //git pull
EOF
fi
