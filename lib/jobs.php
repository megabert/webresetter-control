<?PHP

include "config.php";

function mk_test_job  ($tks_address, $servername) { mk_job($tks_address, $servername, "check");         }
function mk_reset_job ($tks_address, $servername) { mk_job($tks_address, $servername, "hardreset");     }

function mk_job ($tks_address, $info, $check_type) {

	global $job_dir;

        $tmp    = tempnam($job_dir,"tmp");
        $handle = fopen($tmp,"w");
	fputs($handle,
"<?PHP
	\$job = array(
	\"action\" 	=>	\"$check_type\",
	\"address\"	=>	\"$tks_address\",
	\"info\"	=>	\"$info\",
	);
?>
");
	fclose($handle);
	rename($tmp,$tmp.".job");
}

?>
