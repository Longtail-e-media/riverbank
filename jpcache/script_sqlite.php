<?php
	/**
	 * jpcache SQLite Database Setup
	 *
	 * Usage:
	 *  php -f script_sqlite.php
	 * Or:
	 * Copy to web accessible directory, access the file, delete when done.
	 */

	/**
	 * @var string $db_file The fully pathed database file to be created
	 */
	$db_file = '/path/to/jpcache/jpcache.db';

	/**
	 * @var string $query The database creation string
	 */
	$query = 'CREATE TABLE CACHEDATA (
	   CACHEKEY varchar(255) NOT NULL,
	   CACHEEXPIRATION int(11) NOT NULL,
	   GZDATA blob,
	   DATASIZE int(11),
	   DATACRC int(11),
	   PRIMARY KEY (CACHEKEY)
	 )';

	$db = sqlite_open($db_file);
	sqlite_query($db , $query);
	chmod($db_file, 0666);
?>
