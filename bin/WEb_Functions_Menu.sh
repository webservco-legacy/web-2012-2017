#!/bin/bash

# $Id$

function handle_menu
{
	R=`cat /tmp/"${P_NAME}"`
	
	if [ "${R}" == "h" ]; then
		page_help
	elif [ "${R}" == "a" ]; then
		page_about
	elif [ "${R}" == "ws" ]; then
		webs_start
	elif [ "${R}" == "wk" ]; then
		webs_stop
	elif [ "${R}" == "wr" ]; then
		webs_restart
	elif [ "${R}" == "ds" ]; then
		dbms_start
	elif [ "${R}" == "dk" ]; then
		dbms_stop
	elif [ "${R}" == "dr" ]; then
		dbms_restart
	elif [ "${R}" == "s" ]; then
		page_server
	elif [ "${R}" == "d" ]; then
		page_doc
	elif [ "${R}" == "dc" ]; then
		doc_clean
	elif [ "${R}" == "dx" ]; then
		doc_run
	elif [ "${R}" == "t" ]; then
		page_test
	elif [ "${R}" == "tc" ]; then
		test_clean
	elif [ "${R}" == "tx" ]; then
		test_run
	elif [ "${R}" == "b" ]; then
		show_gui
	elif [ "${R}" == "x" ]; then
		exit_ok
	fi
}