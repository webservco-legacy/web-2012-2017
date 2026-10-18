#!/bin/bash

# $Id$

function webs_start
{
    p_header "Start Web Server"
    echo -n "Enter root "
    su -c "$WEBS start;exit"
    p_footer
}

function webs_stop
{
    p_header "Stop Web Server"
    echo -n "Enter root "
    su -c "$WEBS stop;exit"
    p_footer
}

function webs_restart
{
    p_header "Restart Web Server"
    echo -n "Enter root "
    su -c "$WEBS restart;exit"
    p_footer
}

function dbms_start
{
    p_header "Start Database Management Server"
    echo -n "Enter root "
    su -c "$DBMS start;exit"
    p_footer
}

function dbms_stop
{
    p_header "Stop Database Management Server"
    echo -n "Enter root "
    su -c "$DBMS stop;exit"
    p_footer
}

function dbms_restart
{
    p_header "Restart Database Management Server"
    echo -n "Enter root "
    su -c "$DBMS restart;exit"
    p_footer
}