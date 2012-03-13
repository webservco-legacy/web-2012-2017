#!/bin/bash

# $Id$

#############
# VARIABLES #
#############

WEBS='/etc/init.d/httpd'
DBMS='/etc/init.d/mysqld'

DOC_INI='/home/radu/Documents/workspace/wsWEb/extra/docs/WEb.ini'
DOC_OUT_DIR='/home/radu/Documents/workspace/wsWEb/extra/docs/output/phpdoc'
DOC_URL='http://webdocs.dev/phpdoc/'
DOC_FILE='http://webdocs.dev/phpdoc/HTMLframesConverter/default/'

TEST_INI='/home/radu/Documents/workspace/wsWEb/extra/test/phpunit.xml'
TEST_OUT_DIR='/home/radu/Documents/workspace/wsWEb/extra/test/results'
TEST_RESULT_FORMAT='html'

TEST_LOG_FILE='/home/radu/Documents/workspace/wsWEb/extra/test/assets/WEb/var/log/test.log'

#############
# CONSTANTS #
#############

P_NAME=$(basename $0)
P_VERSION='$Revision$'
P_USER=$USER
TEST_RESULT_FILE="$TEST_OUT_DIR/testdox.$TEST_RESULT_FORMAT"

#############
# FUNCTIONS #
#############

function exit_error 
{
    clear
    echo "--------------------------------------------------------------------------------"
    echo " $P_NAME ERROR: $1" 1>&2
    echo "--------------------------------------------------------------------------------"
    exit 1
}

function exit_ok
{
    rm -f /tmp/WEb
    clear
    echo "--------------------------------------------------------------------------------"
    echo " Thanx for using $P_NAME, $P_USER!"
    echo "--------------------------------------------------------------------------------"
    exit 0
}

function p_header
{
    clear
    echo "--------------------------------------------------------------------------------"
    echo " $P_NAME run by $P_USER"
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

function check_user
{
    clear
    if [ $(id -u) = "0" ]; then
    	dialog --title "$P_NAME Error!"\
        --msgbox "\n Don't run $P_NAME as root!" \
        10 40
        exit_error "Don't run as root!"
    fi
}

function page_help
{
	clear
	dialog --title "$P_NAME Help" \
	--msgbox " \
	\n Lorem Ipsum Dolor \
	\n Sic Amet
	" \
	20 50
}

function page_about
{
	clear
	dialog --title "About $P_NAME" \
	--msgbox " \
	\n Name: $P_NAME \
	\n Version $P_VERSION
	" \
	20 50
}

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

function doc_run
{
	p_header "Generate Documentation"
	doc_clean
	phpdoc -c $DOC_INI
	xdg-open $DOC_URL > /dev/null 2>&1
	xdg-open $DOC_FILE > /dev/null 2>&1
	p_footer
}

function doc_clean
{
	p_header "Clean Up Documentation Output Directory"
	rm -rf $DOC_OUT_DIR/*
	p_footer
}

function test_run
{
	p_header "Run Unit Tests"
	test_clean
	phpunit --configuration $TEST_INI
	
	if [ -f $TEST_RESULT_FILE ]; then
		xdg-open $TEST_RESULT_FILE > /dev/null 2>&1
	fi
	
	if [ -f $TEST_LOG_FILE ]; then
		xdg-open $TEST_LOG_FILE > /dev/null 2>&1
	fi
	
	p_footer
}

function test_clean
{
	p_header "Clean Up Unit Tests Results Directory"
	rm -rf $TEST_OUT_DIR/*
	p_footer
}

function show_gui
{
	while [ 0 ]; do
		dialog --title "$P_NAME" --menu \
		"Hello $P_USER!" \
		18 60 7 \
		"s" "Server Management >>" \
		"d" "WEb Documentation >>" \
		"t" "WEb Unit Tests >>" \
		"h" "Help" \
		"a" "About" \
		"x" "Exit" \
		2> /tmp/WEb
		if [ $? = 1 ]; then
			exit_ok
		fi
		handle_menu
	done;
}

function page_server
{
	while [ 0 ]; do
		dialog --title "$P_NAME :: Server Management" --menu \
		"Hello $P_USER!" \
		18 60 7 \
		"ws" "Start Web Server" \
		"wk" "Stop Web Server" \
		"wr" "Restart Web Server" \
		"ds" "Start Database Management Server" \
		"dk" "Stop Database Management Server" \
		"dr" "Restart Database Management Server" \
		"b" "Back" \
		2> /tmp/WEb
		if [ $? = 1 ]; then
			show_gui
		fi
		handle_menu
	done;
}

function page_doc
{
	while [ 0 ]; do
		dialog --title "$P_NAME :: WEb Documentation" --menu \
		"Hello $P_USER!" \
		18 60 5 \
		"dc" "Clean Up Documentation Output Directory" \
		"dx" "Generate Documentation" \
		"b" "Back" \
		2> /tmp/WEb
		if [ $? = 1 ]; then
			show_gui
		fi
		handle_menu
	done;
}

function page_test
{
	while [ 0 ]; do
		dialog --title "$P_NAME :: WEb Unit Tests" --menu \
		"Hello $P_USER!" \
		18 60 5 \
		"tc" "Clean Up Unit Tests Results Directory" \
		"tx" "Run Unit Tests" \
		"b" "Back" \
		2> /tmp/WEb
		if [ $? = 1 ]; then
			show_gui
		fi
		handle_menu
	done;
}

function handle_menu
{
	R="`cat /tmp/WEb`"
	
	if [ $R = "h" ]; then
		page_help
	elif [ $R = "a" ]; then
		page_about
	elif [ $R = "ws" ]; then
		webs_start
	elif [ $R = "wk" ]; then
		webs_stop
	elif [ $R = "wr" ]; then
		webs_restart
	elif [ $R = "ds" ]; then
		dbms_start
	elif [ $R = "dk" ]; then
		dbms_stop
	elif [ $R = "dr" ]; then
		dbms_restart
	elif [ $R = "s" ]; then
		page_server
	elif [ $R = "d" ]; then
		page_doc
	elif [ $R = "dc" ]; then
		doc_clean
	elif [ $R = "dx" ]; then
		doc_run
	elif [ $R = "t" ]; then
		page_test
	elif [ $R = "tc" ]; then
		test_clean
	elif [ $R = "tx" ]; then
		test_run
	elif [ $R = "b" ]; then
		show_gui
	elif [ $R = "x" ]; then
		exit_ok
	fi
}

#############
# EXECUTION #
#############

#check not to run as root
check_user
#check CLI or GUI
if [ "$1" != "" ]; then
	while [ "$1" != "" ]; do
		case $1 in
			-h | --help )			page_help
									exit_ok
									;;
			-a | --about )			page_about
									exit_ok
									;;
			-ws | --webstart )		webs_start
									exit_ok
									;;
			-wk | --webstop )		webs_stop
									exit_ok
									;;
			-wr | --webrestart )	webs_restart
									exit_ok
									;;
			-ds | --dbstart )		dbms_start
									exit_ok
									;;
			-dr | --dbrestart )		dbms_restart
									exit_ok
									;;
			-dk | --dbstop )		dbms_stop
									exit_ok
									;;
			-dc | --docc )			doc_clean
									exit_ok
									;;
			-dx | --doc )			doc_run
									exit_ok
									;;
			-tc | --testc )			test_clean
									exit_ok
									;;
			-tx | --test )			test_run
									exit_ok
									;;
			* )						show_gui
		esac
	done
else
	show_gui
fi
