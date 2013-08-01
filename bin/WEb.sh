#!/bin/bash

# $Id$

#############
# CONSTANTS #
#############
P_PATH='/home/radu/Documents/Projects/Eclipse/ws_WEb/bin/'
P_NAME=$(basename $0)
P_VERSION='$Revision$'
P_USER=$USER
TEST_RESULT_FILE="$TEST_OUT_DIR/testdox.$TEST_RESULT_FORMAT"

################################################################################
# INCLUDE FUNCTIONS
################################################################################
. "${P_PATH}WEb_Configuration.sh"
. "${P_PATH}WEb_Functions_Documentation.sh"
. "${P_PATH}WEb_Functions_General.sh"
. "${P_PATH}WEb_Functions_Gui.sh"
. "${P_PATH}WEb_Functions_Menu_Pages.sh"
. "${P_PATH}WEb_Functions_Menu.sh"
. "${P_PATH}WEb_Functions_Pages.sh"
. "${P_PATH}WEb_Functions_Server.sh"
. "${P_PATH}WEb_Functions_Tests.sh"

################################################################################
# ACTUAL EXECUTION
################################################################################

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