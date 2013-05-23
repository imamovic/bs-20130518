/*******************************************************************************
   Description: Dump
   DB Server: Sqlite
********************************************************************************/
DROP TABLE IF EXISTS ip_address;
CREATE TABLE ip_address
(
	id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
	ip NVARCHAR(15) NOT NULL,
	mask INTEGER NOT NULL,
	ip_number BIGINT NOT NULL
);

INSERT INTO ip_address VALUES (NULL,"10.1.0.0",16,167837696);
INSERT INTO ip_address VALUES (NULL,"127.0.0.0",8,2130706432);
INSERT INTO ip_address VALUES (NULL,"204.11.52.128",25,-871680896);
INSERT INTO ip_address VALUES (NULL,"192.168.0.1",27,-1062731775);
INSERT INTO ip_address VALUES (NULL,"80.76.201.32",27,1347209504);
INSERT INTO ip_address VALUES (NULL,"192.168.3.0",24,-1062731008);

