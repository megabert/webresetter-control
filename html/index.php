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

$base = preg_replace("/^(.*)\/[^\/]+$/","$1",getcwd());
$path = "$base/lib".PATH_SEPARATOR."$base/conf";
set_include_path(get_include_path().PATH_SEPARATOR.$path);

include "config.php";
include "log.php";
include "jobs.php";
include "read_tks_data.php";

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
			system("/usr/bin/php /home/webresetter_work/bin/webreset -y -i $tksid");
		}
	}
		
}
 

			
	
?>
		</FORM>
</BODY>
</HTML>
