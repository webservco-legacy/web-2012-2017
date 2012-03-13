<!-- $Id$ -->

<refentry id="{@id}">
	<refnamediv>
		<refname>Config</refname>
		<refpurpose>Configuration settings</refpurpose>
	</refnamediv>
	<refsynopsisdiv>
		<author>
			Radu Maier
			<authorblurb>
				{@link mailto:radu@webserv.co radu@webserv.co}
			</authorblurb>
		</author>
	</refsynopsisdiv>
	{@toc}
	<refsect1 id="{@id intro}">
		<title>Hello World</title>
		<para>I'm the documentation</para>
	</refsect1>
	<refsect1 id="{@id test}">
		<title>Subtitle</title>
		<para>
			Text.
		</para>
		<refsect2 id="{@id tester}">
			<title>Title</title>
			<para>
				Text.
			</para>
		</refsect2>
	</refsect1>
	<refsect1 id="{@id GetMethod}">
		<title>Get</title>
		<para>
			The $cfgSetting parameter can be:
		</para>
		<itemizedlist>
			<listitem>
				<para>blank: get all settings for current environment</para>
			</listitem>
			<listitem>
				<para>string: get a first level setting</para>
			</listitem>
			<listitem>
				<para>special string: get a nested setting</para>
			</listitem>
			<listitem>
				<para>array: mainly used as a result of processing the special string</para>
			</listitem>
		</itemizedlist>
		<para>
			TIP: If you want to get all configuration settings for a different environment,
			set the $cfgSetting parameter to NULL, and use a valid value for the
			$cfgEnv parameter.
		</para>
	</refsect1>
	<refsect1 id="{@id SetMethod}">
		<title>Set</title>
		<para>Dolor Sic Amet</para>
	</refsect1>
</refentry>