#!/bin/bash

# $Id$

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