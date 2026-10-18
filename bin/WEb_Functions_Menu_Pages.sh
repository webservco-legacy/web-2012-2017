#!/bin/bash

# $Id$

function page_server
{
    while [ 0 ]; do
        dialog --title "${P_NAME} :: Server Management" --menu \
        "Hello $P_USER!" \
        18 60 7 \
        "ws" "Start Web Server" \
        "wk" "Stop Web Server" \
        "wr" "Restart Web Server" \
        "ds" "Start Database Management Server" \
        "dk" "Stop Database Management Server" \
        "dr" "Restart Database Management Server" \
        "b" "Back" \
        2> "/tmp/${P_NAME}"
        if [ $? = 1 ]; then
            show_gui
        fi
        handle_menu
    done;
}

function page_doc
{
    while [ 0 ]; do
        dialog --title "${P_NAME} :: WEb Documentation" --menu \
        "Hello $P_USER!" \
        18 60 5 \
        "dc" "Clean Up Documentation Output Directory" \
        "dx" "Generate Documentation" \
        "b" "Back" \
        2> "/tmp/${P_NAME}"
        if [ $? = 1 ]; then
            show_gui
        fi
        handle_menu
    done;
}

function page_test
{
    while [ 0 ]; do
        dialog --title "${P_NAME} :: WEb Unit Tests" --menu \
        "Hello $P_USER!" \
        18 60 5 \
        "tc" "Clean Up Unit Tests Results Directory" \
        "tx" "Run Unit Tests" \
        "b" "Back" \
        2> "/tmp/${P_NAME}"
        if [ $? = 1 ]; then
            show_gui
        fi
        handle_menu
    done;
}