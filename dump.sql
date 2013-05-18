/*******************************************************************************
   Description: Dump
   DB Server: Sqlite
********************************************************************************/
DROP TABLE IF EXISTS ip_address;
CREATE TABLE ip_address
(
	id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
	ip NVARCHAR(15) NOT NULL,
	mask INTEGER NOT NULL
);

INSERT INTO ip_address VALUES (NULL,"10.1.0.0",16);
INSERT INTO ip_address VALUES (NULL,"127.0.0.0",8);
INSERT INTO ip_address VALUES (NULL,"204.11.52.128",25);
INSERT INTO ip_address VALUES (NULL,"192.168.0.1",27);
