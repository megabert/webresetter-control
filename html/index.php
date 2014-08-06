<HTML>

<?PHP

	//	Webresetter PHP Webinterface

?>
	<HEAD>

<style type="text/css">

form {
	padding-left: 10%;
}

form > span {
	padding-left:  3px;
	padding-right: 8px;
	display: inline-block;
	width: 600px;
}

#SUBMIT {
	padding-left: 30%;
	display: block;
	margin-top: 1em;
}

#SERVERNAME {
	padding-left: 5px;
	margin-left: 5px;
	color: red;
}

#TKSID {
	color: blue;
	padding-left: 9px;
}

#LOCATION {
	color: grey;
	padding-left: 9px;
}

#MSGINFO {
	font-size: 1.5em;
	color: green;
	display: block;
	width: 1000px;
	margin-left: 5em;
	padding-top: 40px;
	padding-bottom: 20px;
}

#DENIED {
	font-size: 4em;
	padding-left: 30%;
}

</style>

	</HEAD>
	<BODY>
		<H1 ALIGN=CENTER>PHP-Webresetter</H1>
		<FORM ACTION="index.php" method="POST">
<?PHP

include "config.php";
include "log.php";
include "jobs.php";

$opt_yes        = "";
$opt_mac        = "";
$opt_server     = "";
$opt_tksid      = "";

$servername     = "";
$tksid          = "";
$macaddress     = "";

$tksid_by_servername   = array ();
$tksid_by_macaddress   = array ();
$servername_by_tksid   = array ();
$comment_by_servername = array ();

function read_server_data() {

        global $server_data_file, $tksid_by_servername, $tksid_by_macaddress, $comment_by_servername;

        error_reporting(0);
        $data = fopen($server_data_file,"r");
        if(!$data) {
                $e = error_get_last();
                errmsg("Can not open data file: $server_data_file:".$e["message"]);
                exit(1);
        }
        error_reporting(E_ALL);
        while (false !== ($line = fgets($data))){
                list ($servername,$tksid,$macaddress,$comment) = explode(",",$line);

		if (preg_match('/^amt$/i'   ,$tksid))      { continue; }
		if (preg_match('/^server$/i',$servername)) { continue; }
		if (preg_match('/^#/'       ,$servername)) { continue; }

                $macaddress                             = strtolower($macaddress);
                $servername                             = strtolower($servername);
                $tksid                                  = strtolower($tksid);

                $tksid_by_servername["$servername"]     = $tksid;
                $tksid_by_macaddress["$servername"]     = $tksid;
                $servername_by_tksid["$tksid"]          = $servername;
		$comment_by_servername["$servername"]	= ($comment!="")?$macaddress." ".$comment:"";
        }
        fclose($data);
}

function display_reset_form() {

	read_server_data();
	global $tksid_by_servername, $comment_by_servername;
	

	// $Servername, $tksid, $commet
	$count=0;
	foreach(array_keys($tksid_by_servername) as $servername) {
		$count++;
		echo "<SPAN>\n";
		echo "<SPAN ID='SERVERNAME'><INPUT TYPE='RADIO' name='TKSIDSERVER' VALUE='".$tksid_by_servername["$servername"]."___".$servername."'>$servername</SPAN>\n";
		echo "<SPAN ID='TKSID'>TKS-ID ".$tksid_by_servername["$servername"]."</SPAN>\n";
		echo "<SPAN ID='COMMENT'>".$comment_by_servername["$servername"]."</SPAN>\n";
		echo "</SPAN>\n"; }
	
	echo "<SPAN ID='SUBMIT'> <INPUT TYPE='SUBMIT' NAME='SUBMIT' VALUE='Reset OHNE R&uuml;ckfrage'></SPAN>\n";

}

if ( $_SERVER["REMOTE_USER"] != "admin" ) {
	echo "<H1 ID='DENIED'>Access Denied!</H1>";
} else {
	display_reset_form();

	if ( $_POST and $_POST['SUBMIT'] ) {
		if(array_key_exists('TKSIDSERVER',$_POST)) {
			list ($tksid, $servername) = explode("___",$_POST['TKSIDSERVER']);
			echo "<SPAN ID='MSGINFO'>Resetanfrage f&uuml;r Server $servername / TKSID $tksid wird durchgef&uuml;hrt.</SPAN>\n";
			system("/usr/bin/sudo /usr/bin/php /home/webresetter/work/php/webreset -y -i $tksid");
		}
	}
		
}
 

			
	
?>
		</FORM>
</BODY>
</HTML>
