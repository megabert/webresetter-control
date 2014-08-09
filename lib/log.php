<?PHP

include "config.php";

function _log($msg_level,$msg) {

        global $log_level, $LOG_ERROR, $LOG_INFO, $LOG_DEBUG, $LOG_DEBUG2, $LOG_DEBUG3;
	global $log_file;

        if ( $msg_level <= $log_level ) {

                $loghandle = fopen("$log_file","a");
                fputs($loghandle, date(DATE_RFC822)." ".$msg."\n");
                fclose($loghandle);
        }
}

function  lg_err ($msg) { global $LOG_ERROR;  _log($LOG_ERROR,   $msg); }
function  lg_info($msg) { global $LOG_INFO;   _log($LOG_INFO,    $msg); }
function  lg_dbg ($msg) { global $LOG_DEBUG;  _log($LOG_DEBUG,   $msg); }
function  lg_dbg2($msg) { global $LOG_DEBUG2; _log($LOG_DEBUG2,  $msg); }
function  lg_dbg3($msg) { global $LOG_DEBUG3; _log($LOG_DEBUG3,  $msg); }

?>
