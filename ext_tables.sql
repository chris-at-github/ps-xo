#
# Extend SysCategory
#
CREATE TABLE sys_category (
	tx_xo_link varchar(1024) DEFAULT '' NOT NULL
);

#
# Extend Pages
#
CREATE TABLE pages (
	tx_xo_flash int(11) unsigned DEFAULT '0' NOT NULL,
	tx_xo_no_breadcrumb smallint(5) unsigned DEFAULT '0' NOT NULL,
	tx_xo_no_sticky smallint(5) unsigned DEFAULT '0' NOT NULL,
	tx_xo_no_link smallint(5) unsigned DEFAULT '0' NOT NULL,
	tx_xo_breadcrumb_hidden smallint(5) unsigned DEFAULT '0' NOT NULL,
	tx_xo_navigation_layout varchar(50) DEFAULT '0' NOT NULL,
	tx_xo_navigation_content int(11) unsigned NOT NULL DEFAULT '0'
);

#
# Extend TtContent
#
CREATE TABLE tt_content (
	tx_xo_no_frame smallint(5) unsigned DEFAULT '0' NOT NULL,
	tx_xo_variant int(11) unsigned DEFAULT '0',
	tx_xo_header_class int(11) unsigned DEFAULT '0',
	tx_xo_file int(11) unsigned DEFAULT '0' NOT NULL,
	tx_xo_address int(11) unsigned DEFAULT '0' NOT NULL,
	tx_xo_elements int(11) unsigned DEFAULT '0' NOT NULL,
	tx_xo_page int(11) unsigned DEFAULT '0' NOT NULL,
	tx_xo_flash int(11) unsigned DEFAULT '0' NOT NULL,
	tx_xo_parent int(11) unsigned DEFAULT '0' NOT NULL
);

#
# Extend TtAddress
#
CREATE TABLE tt_address (
	record_type varchar(100) NOT NULL DEFAULT '0',
	tx_xo_content int(11) unsigned DEFAULT '0' NOT NULL,
	tx_xo_schemaorg_media int(11) unsigned DEFAULT '0' NOT NULL,
	tx_xo_directors varchar(255) DEFAULT '' NOT NULL,
	tx_xo_commercial_register varchar(100) DEFAULT '' NOT NULL,
	tx_xo_registered_office varchar(100) DEFAULT '' NOT NULL,
	tx_xo_vat_number varchar(50) DEFAULT '' NOT NULL,
	tx_xo_opening_hours_description text,
	tx_xo_opening_hours int(11) unsigned DEFAULT '0' NOT NULL,
	tx_xo_instagram varchar(255) DEFAULT '' NOT NULL,
	tx_xo_youtube varchar(255) DEFAULT '' NOT NULL
);

#
# Table structure for table 'tx_xo_domain_model_elements'
#
CREATE TABLE tx_xo_domain_model_elements (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	title varchar(255) DEFAULT '' NOT NULL,
	title_type varchar(50) DEFAULT '' NOT NULL,
	description text,
	link varchar(1024) DEFAULT '' NOT NULL,
  media int(11) unsigned DEFAULT '0',
  thumbnail int(11) unsigned DEFAULT '0',

	record_type varchar(100) NOT NULL DEFAULT '0',
	sorting int(11) DEFAULT '0' NOT NULL,
	tx_xo_content int(11) DEFAULT '0' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted smallint(5) unsigned DEFAULT '0' NOT NULL,
	hidden smallint(5) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(255) DEFAULT '' NOT NULL,
	t3ver_state smallint(6) DEFAULT '0' NOT NULL,
	t3ver_stage int(11) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3ver_move_id int(11) DEFAULT '0' NOT NULL,

	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource mediumblob,
	l10n_state text,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY t3ver_oid (t3ver_oid,t3ver_wsid),
	KEY language (l10n_parent,sys_language_uid)
);

CREATE TABLE tx_xo_domain_model_openinghours (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	address int(11) unsigned DEFAULT '0' NOT NULL,
	days text,
	days_title varchar(255) DEFAULT '' NOT NULL,
	open_at time DEFAULT NULL,
	close_at time DEFAULT NULL,
	sorting int(11) DEFAULT '0' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted smallint(5) unsigned DEFAULT '0' NOT NULL,
	hidden smallint(5) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(255) DEFAULT '' NOT NULL,
	t3ver_state smallint(6) DEFAULT '0' NOT NULL,
	t3ver_stage int(11) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3ver_move_id int(11) DEFAULT '0' NOT NULL,

	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource mediumblob,
	l10n_state text,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY t3ver_oid (t3ver_oid, t3ver_wsid),
	KEY language (l10n_parent, sys_language_uid),
	KEY address (address)
);

CREATE TABLE tx_xo_pages_content_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid_local,uid_foreign),
	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);