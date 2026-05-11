<?php

    // jpcache configuration file
    //
    // Some settings are specific for the type of cache you are running, like
    // file- or database-based.
    //

    /**
     * Include Directory
     *
     * IMPORTANT!!!
     *
     * You must set this include path variable in jpcache.php!
     * $includedir = "/path/to/jpcache";
     */

    /**
     * Caching Backend Type
     *
     * @var string $JPCACHE_TYPE The backend type
     * Valid types are: "file", "mysql" or "sqlite"
     */
    $JPCACHE_TYPE = "file";

    // DOH! Strip out this check for performance if you are sure you did set it.
    if (!isset($JPCACHE_TYPE))
    {
        exit("[jpcache-config.php] No JPCACHE_TYPE has been set!");
    }

    /**
     * General configuration options.
     */
    $JPCACHE_TIME         =   900; // Default number of seconds to cache a page
    $JPCACHE_DEBUG        =   0;   // Turn debugging on/off
    $JPCACHE_IGNORE_DOMAIN=   1;   // Ignore domain name in request(single site)
    $JPCACHE_ON           =   1;   // Turn caching on/off
    $JPCACHE_USE_GZIP     =   1;   // Whether or not to use GZIP
    $JPCACHE_POST         =   0;   // Should POST's be cached (default OFF)
    $JPCACHE_GC           =   1;   // Probability % of garbage collection
    $JPCACHE_GZIP_LEVEL   =   9;   // GZIPcompressionlevel to use (1=low,9=high)
    $JPCACHE_CLEANKEYS    =   0;   // Set to 1 to avoid hashing storage-key:
                                   // you can easily see cachefile-origin.
    $JPCACHE_FLUSH_KEY    =   1;   // Flush page from cache when ?flush=JPCACHE_FLUSH_KEY
                                   // You should set this to a unique value so only you can flush pages



    /**
     * File based caching setting.
     */
    $JPCACHE_DIR          =  $_SERVER['DOCUMENT_ROOT']."jpcache/tmp/jpcache"; // Directory where jpcache must store
                                   // generated files. Please use a dedicated
                                   // directory, and make it writable
    $JPCACHE_FILEPREFIX   = "jpc-";// Prefix used in the filename. This enables
                                   // us to (more accuratly) recognize jpcache-
                                   // files.

    /**
     * DB based caching settings.
     */

    //MySQL Settings
    $JPCACHE_DB_HOST      = "localhost"; // Database Server
    $JPCACHE_DB_DATABASE  = "jpcache";   // Database-name to use
    $JPCACHE_DB_USERNAME  = "sqluser";   // Username
    $JPCACHE_DB_PASSWORD  = "passwd";    // Password

    //SQLite Settings
    $JPCACHE_SQLITE_DATABASE = '/tmp/jpcache/jpcache.db';

    //General DB based settings
    $JPCACHE_DB_TABLE     = "CACHEDATA"; // Table that holds the data
    $JPCACHE_OPTIMIZE     = 0;           // If 'OPTIMIZE TABLE' after garbage
                                         // collection is executed.
                                         // Note: This may not work with some versions of MySQL.
                                         //       See http://dev.mysql.com/doc/refman/4.1/en/optimize-table.html
?>
