<?PHP

$base = preg_replace("/^(.*)\/[^\/]+$/","$1",getcwd());

$path = "$base/lib".PATH_SEPARATOR."$base/conf";
set_include_path(get_include_path().PATH_SEPARATOR.$path);


$com_device 		= "/dev/ttyUSB0";
$job_dir    		= "$base/jobs";
$status_dir 		= "$base/status";
$log_file   		= "$base/log/webresetter.log";
$server_data_file 	= "$base/conf/server.csv";

$LOG_ERROR  = 1;
$LOG_INFO   = 2;
$LOG_DEBUG  = 3;
$LOG_DEBUG2 = 4;
$LOG_DEBUG3 = 5;

$log_level  = $LOG_INFO;

?>
