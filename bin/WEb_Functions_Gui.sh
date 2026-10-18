#!/bin/bash

# $Id$

function p_header
{
    clear
    echo "--------------------------------------------------------------------------------"
    echo " ${P_NAME} run by $P_USER"
    echo " TASK: $1" 1>&2
    echo "--------------------------------------------------------------------------------"
    echo "                                                                                "
}

function p_footer
{
    echo "                                                                                "
    echo "--------------------------------------------------------------------------------"
    echo " done! | Press ENTER to continue"
    echo "--------------------------------------------------------------------------------"
    read
    clear
}

function show_gui
{
    while [ 0 ]; do
        dialog --title "${P_NAME}" --menu \
        "Hello $P_USER!" \
        18 60 7 \
        "s" "Server Management >>" \
        "d" "WEb Documentation >>" \
        "t" "WEb Unit Tests >>" \
        "h" "Help" \
        "a" "About" \
        "x" "Exit" \
        2> "/tmp/${P_NAME}"
        if [ $? = 1 ]; then
            exit_ok
        fi
        handle_menu
    done;
}