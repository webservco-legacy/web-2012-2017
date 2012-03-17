#!/bin/bash

# $Id$

function exit_error 
{
    clear
    echo "--------------------------------------------------------------------------------"
    echo " ${P_NAME} ERROR: $1" 1>&2
    echo "--------------------------------------------------------------------------------"
    exit 1
}

function exit_ok
{
    rm -f /tmp/${P_NAME}
    clear
    echo "--------------------------------------------------------------------------------"
    echo " Thanx for using ${P_NAME}, $P_USER!"
    echo "--------------------------------------------------------------------------------"
    exit 0
}

function check_user
{
    clear
    if [ "$(id -u)" == "0" ]; then
    	dialog --title "${P_NAME} Error!"\
        --msgbox "\n Don't run ${P_NAME} as root!" \
        10 40
        exit_error "Don't run as root!"
    fi
}