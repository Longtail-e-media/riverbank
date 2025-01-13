<?php

    /* jpcache_db_connect()
     *
     * Makes connection to the database
     */
    function jpcache_db_connect()
    {
        $GLOBALS["sql_link"] = @mysql_connect($GLOBALS["JPCACHE_DB_HOST"],
                                              $GLOBALS["JPCACHE_DB_USERNAME"],
                                              $GLOBALS["JPCACHE_DB_PASSWORD"]);
    }

    /* jpcache_db_disconnect()
     *
     * Closes connection to the database
     */
    function jpcache_db_disconnect()
    {
        mysql_close($GLOBALS["sql_link"]);
    }

    /* jpcache_db_query($query)
     *
     * Executes a given query
     */
    function jpcache_db_query($query)
    {
        jpcache_debug("Executing SQL-query " . substr($query, 0, 100));
        $ret = @mysql_db_query($GLOBALS["JPCACHE_DB_DATABASE"],
                               $query,
                               $GLOBALS["sql_link"]);
        return $ret;
    }

    /* jpcache_restore()
     *
     * Will try to restore the cachedata from the db.
     */
    function jpcache_restore()
    {
        $res = jpcache_db_query("select GZDATA, DATASIZE, DATACRC from ".
                                    $GLOBALS["JPCACHE_DB_TABLE"].
                                " where CACHEKEY='".
                                    mysql_real_escape_string($GLOBALS["jpcache_key"]).
                                "' and (CACHEEXPIRATION>".
                                    time().
                                " or CACHEEXPIRATION=" . mysql_real_escape_string($GLOBALS["JPCACHE_TIME"]) . ")"
                               );

        if ($res && mysql_num_rows($res))
        {
            if ($row = mysql_fetch_array($res))
            {
        			 jpcache_debug("Restoring cachedata to global scope");
                // restore data into global scope from found row
                $GLOBALS["jpcachedata_gzdata"]   = $row["GZDATA"];
                $GLOBALS["jpcachedata_datasize"] = $row["DATASIZE"];
                $GLOBALS["jpcachedata_datacrc"]  = $row["DATACRC"];
                return true;
            }
        }
        return false;
    }

    /* jpcache_write()
     *
     * Will (try to) write out the cachedata to the db
     */
    function jpcache_write($gzdata, $datasize, $datacrc)
    {
        $dbtable = $GLOBALS["JPCACHE_DB_TABLE"];

        // XXX: Later on, maybe implement locking mechanism inhere.

        // Check if it already exists
        $res = jpcache_db_query("select CACHEEXPIRATION from $dbtable".
                                " where CACHEKEY='".
                                    mysql_real_escape_string($GLOBALS["jpcache_key"]).
                                "'"
                               );


        if (!$res || mysql_num_rows($res) < 1)
        {
            // Key not found, so insert
            jpcache_debug("Cache key not found, inserting now");
            $res = jpcache_db_query("insert into $dbtable".
                                    " (CACHEKEY, CACHEEXPIRATION, GZDATA,".
                                    " DATASIZE, DATACRC) values ('".
                                        mysql_real_escape_string($GLOBALS["jpcache_key"]).
                                    "',".
                                        (($GLOBALS["JPCACHE_TIME"] != 0) ?
                                        (time()+$GLOBALS["JPCACHE_TIME"]) : 0).
                                    ",'".
                                        mysql_real_escape_string($gzdata).
                                    "', $datasize, $datacrc)"
                                   );
            // This fails with unique-key violation when another thread has just
            // inserted the same key. Just continue, as the result is (almost)
            // the same.
        }
        else
        {
            // Key found, so update
            jpcache_debug("Cache key found, updating now");
            $res = jpcache_db_query("update $dbtable set CACHEEXPIRATION=".
                                        (($GLOBALS["JPCACHE_TIME"] != 0) ?
                                        (time()+$GLOBALS["JPCACHE_TIME"]) : 0).
                                    ", GZDATA='".
                                        mysql_real_escape_string($gzdata).
                                    "', DATASIZE=$datasize, DATACRC=$datacrc where".
                                    " CACHEKEY='".
                                        mysql_real_escape_string($GLOBALS["jpcache_key"]).
                                    "'"
                                   );
            // This might be an update too much, but it shouldn't matter
        }
    }

    /* jpcache_do_gc()
     *
     * Performs the actual garbagecollection
     */
    function jpcache_do_gc($mode = '')
    {

         // Flush expirable pages (non-zero)
         if ($mode === 'FLUSH')
         {
            jpcache_db_query("delete from ".
                                $GLOBALS["JPCACHE_DB_TABLE"].
                             " where CACHEEXPIRATION!=0"
                            );
         }
         elseif ($mode === 'FLUSHALL')
         {
         	// Flush all pages!
            jpcache_db_query("delete from ".
                                $GLOBALS["JPCACHE_DB_TABLE"]
                            );
         }
         elseif ($mode === 'FLUSHSINGLE')
         {
         	// Flush single page!
            if (isset($GLOBALS["jpcache_key"])) {
            	jpcache_db_query("delete from ".
                                $GLOBALS["JPCACHE_DB_TABLE"].
                                " where CACHEKEY='" . $GLOBALS["jpcache_key"] . "'"
                            );
            }
         }
         else
         {
         	// Flush expired pages
            jpcache_db_query("delete from ".
                                $GLOBALS["JPCACHE_DB_TABLE"].
                             " where CACHEEXPIRATION<=".
                                time().
                             " and CACHEEXPIRATION!=0"
                            );
        }

        // Are we allowed to do an optimize table-call?
        // As noted, first check if this works on your mysql-installation!
        if ($GLOBALS["JPCACHE_OPTIMIZE"])
        {
            jpcache_db_query("OPTIMIZE TABLE ".$GLOBALS["JPCACHE_DB_TABLE"]);
        }
    }


    /* jpcache_do_start()
     *
     * Additional code that is executed before main jpcache-code kicks in.
     */
    function jpcache_do_start()
    {
        // Connect to db
        jpcache_db_connect();
    }

    /* jpcache_do_end()
     *
     * Additional code that is executed after caching has been performed,
     * but just before output is returned. No new output can be added!
     */
    function jpcache_do_end()
    {
        // Disconnect from db
        jpcache_db_disconnect();
    }

?>