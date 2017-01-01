#
# Table structure for table 'tx_schemainjector_domain_model_injector'
#
CREATE TABLE tx_schemainjector_domain_model_injector (

        uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
        pid int(11) DEFAULT '0' NOT NULL,

        inject_page_id int(11) DEFAULT 0 NOT NULL,
        inject_page_name varchar(50) DEFAULT '' NOT NULL,
        inject_file_name varchar(50) DEFAULT '' NOT NULL,

        PRIMARY KEY (uid),
        KEY parent (pid)

);
