<?PHP

if ( preg_match("/\//",dirname($argv[0]))) { chdir(dirname($argv[0])); }

$base = preg_replace("/^(.*)\/[^\/]+$/","$1",getcwd());
$path = "$base/lib".PATH_SEPARATOR."$base/conf";
set_include_path(get_include_path().PATH_SEPARATOR.$path);

include "log.php";

function hardResetTKS() { lg_err("Not implemented: hardResetTKS"); 	exit(1);	} 
function checktks() 	{ lg_err("Not implemented: checktks");		exit(1);	}
function crc() 		{ lg_err("Not implemented: crc");		exit(1);	}

?>
