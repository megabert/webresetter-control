<?PHP

if ( preg_match("/\//",dirname($argv[0]))) { chdir(dirname($argv[0])); }

$base = preg_replace("/^(.*)\/[^\/]+$/","$1",getcwd());
$path = "$base/lib".PATH_SEPARATOR."$base/conf";
set_include_path(get_include_path().PATH_SEPARATOR.$path);

include "log.php";

function hardResetTKS() { lg_err("Not implemented: hardResetTKS"); 	exit(1);	} 
function checktks() 	{ lg_err("Not implemented: checktks");		exit(1);	}

function crc($string) {
 $crc_table = array( 0 => 0x0000, 1 => 0xcc01, 2 => 0xd801, 3 => 0x1400,
4 => 0xf001, 5 => 0x3c00, 6 => 0x2800, 7 => 0xe401, 8 => 0xa001, 9 =>
0x6c00, 10 => 0x7800, 11 => 0xb401, 12 => 0x5000, 13 => 0x9c01, 14 =>
0x8801, 15 => 0x4400 );
 //$chars = preg_split("/ */", $command);
 $crc16_word = '';
 $i = 0;
 while ($i < strlen( $string )) {
  $data = ord( substr( $string, $i, 1 ) );
  $r1_word = $crc_table[$crc16_word & 15];
  $crc16_word = $crc16_word >> 4 & 4095;
  $crc16_word = $crc16_word ^ $r1_word ^ $crc_table[$data & 15];
  $r1_word = $crc_table[$crc16_word & 15];
  $crc16_word = $crc16_word >> 4 & 4095;
  $crc16_word = $crc16_word ^ $r1_word ^ $crc_table[$data >> 4];
  $i++;
 }
 return $crc16_word;
}

?>
