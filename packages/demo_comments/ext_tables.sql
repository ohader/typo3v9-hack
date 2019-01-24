#
# Table structure for table 'tx_democomments_domain_model_comment'
#
CREATE TABLE tx_democomments_domain_model_comment (

	date int(11) DEFAULT '0' NOT NULL,
	email varchar(255) DEFAULT '' NOT NULL,
	message text,

);
