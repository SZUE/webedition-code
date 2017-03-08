/*FIXME: in later versions we have to drop  SHOP_TABLE . '_old' */
CREATE TABLE ###TBLPREFIX###tblOrder (
	ID int unsigned NOT NULL auto_increment,
	shopname tinytext NOT NULL DEFAULT '',
	customOrderNo varchar(100) DEFAULT NULL,
	invoiceNo varchar(100) DEFAULT NULL,
  customerID int unsigned default 0,
	customerData blob NOT NULL,
	customFields TEXT default NULL,
	pricesNet tinyint unsigned NOT NULL default '1',
  priceName TEXT default '',
	shippingCost decimal(10,3) NOT NULL default '0',
	shippingNet tinyint unsigned NOT NULL default '1',
  shippingVat decimal(4,2) NOT NULL default '0',
	calcVat tinyint unsigned NOT NULL default '1',
  DateOrder TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  DateConfirmation DATETIME default NULL,
  DateShipping DATETIME default NULL,
  DatePayment DATETIME default NULL,
  DateCancellation DATETIME default NULL,
  DateFinished DATETIME default NULL,
	PRIMARY KEY  (ID),
	KEY DateOrder (DateOrder),
	KEY customerID(customerID)
) ENGINE=MyISAM;