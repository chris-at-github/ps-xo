#
# Extend TtContent
#
CREATE TABLE tt_content (
	tx_xo_variant int(11) unsigned DEFAULT '0',
	tx_xo_header_class int(11) unsigned DEFAULT '0',
	tx_xo_file int(11) unsigned DEFAULT '0' NOT NULL,
	tx_xo_address int(11) unsigned DEFAULT '0' NOT NULL
);

#
# Extend TtAddress
#
CREATE TABLE tt_address (
	tx_xo_content int(11) unsigned DEFAULT '0' NOT NULL
);