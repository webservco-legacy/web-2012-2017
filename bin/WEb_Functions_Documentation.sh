#!/bin/bash

# $Id$

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