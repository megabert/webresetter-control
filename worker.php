<?PHP

$com_device = "/dev/ttyUSB0";

$job_dir    =  "/var/run/webresetter/jobs";
$status_dir =  "/var/run/webresetter/status";

$LOG_DEBUG  = 3;
$LOG_INFO   = 2;
$LOG_ERROR  = 1;

$log_level  = $LOG_DEBUG;

include "wc.php";	// not included. wc.php is proprietary software

function _log($msg_level,$msg) {

	define_syslog_variables();

	global $log_level, $LOG_ERROR, $LOG_INFO, $LOG_DEBUG;

	if ( $msg_level <= $log_level ) {

		$prio = ($msg_level == $LOG_ERROR) ? LOG_ERR   : "";
		$prio = ($msg_level == $LOG_INFO ) ? LOG_INFO  : $log_level;
		$prio = ($msg_level == $LOG_DEBUG) ? LOG_DEBUG : $log_level;

		openlog("webresetter", LOG_PID, LOG_LOCAL0);
		syslog($prio,$msg);
		closelog();

	}
}

function  _err($msg) { global $LOG_ERROR; _log($LOG_ERROR,   $msg); }
function _info($msg) { global $LOG_INFO;  _log($LOG_INFO,    $msg); }
function  _dbg($msg) { global $LOG_DEBUG; _log($LOG_DEBUG,   $msg); }

function init() {
	
	global $job_dir, $status_dir;

	if ( !file_exists("$job_dir")) {
		if (false !== mkdir("$job_dir",NULL,recursive)) {
			_err("Cannot create job directory $job_dir");
			exit(1);
		}
	}

	if ( !file_exists("$status_dir")) {
		if (false !== mkdir("$status_dir",NULL,recursive)) {
			_err("Cannot create status directory $status_dir");
			exit(1);
		}
	}

}

function write_status($host, $address, $status) {

	global $status_dir;

	if (false !== ($handle = fopen($status_dir."/".$address,"w"))) {
		_dbg("Writing status file $status_dir/".$address);
		fwrite($handle,"$host $address $status ".time()."\n");
		fclose($handle);
	} else {
		_err("Cannot open file $status_dir/".$address);
	}
}

function write_ok_status    ($host,$address) 	{  write_status($host, $address, "OK");     }
function write_failed_status($host,$address)    {  write_status($host, $address, "FAILED"); }


function check_device ($host,$address) {

	_dbg("host=$host, address=$address");

	if(checktks($address)) {
		write_ok_status($host,$address);
		_info("TKS Device $address is reachable");
		 _dbg("TKS Device $address is reachable");
	} else {
		write_failed_status($host,$address);
		_info("TKS Device $address is unreachable");
		 _dbg("TKS Device $address is unreachable");
	}
}

function hard_reset_device($host,$address) {

	_info("Resetting host $host with TKS-Device $address");
	hardResetTKS($address);
}

function process_files() {

	global $job_dir, $status_dir;

	if ($handle = opendir("$job_dir")) {
	  chdir($job_dir);
	  while (false !== ($file = readdir($handle))) {
		if (preg_match("/.*\.job/",$file)) {
		    include "./$file";
		    if ($job["action"]  != "" and $job["address"] != "" and $job["host"]  != "") {
			echo " \nProcessing job >".$job["action"]. "< for host ".$job["host"]. " with address ".$job["address"]."\n";
		
			if ($job["action"] == "check"    ) {      check_device($job["host"],$job["address"]); }
			if ($job["action"] == "hardreset") { hard_reset_device($job["host"],$job["address"]); }
			unlink($file);
			
		    } else {
			_err("Invalid Job-File: $file, job-action=" .$job["action"]
						    ." job-address=".$job["address"]
						    ." job-host="   .$job["host"]);
		    }
		
		}
	    }
	}

}

init();
while(1) {
	process_files();
	sleep(1);
	echo ".";
}	


?>
