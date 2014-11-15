<?PHP

if ( preg_match("/\//",dirname($argv[0]))) { chdir(dirname($argv[0])); }

$base = preg_replace("/^(.*)\/[^\/]+$/","$1",getcwd());
$path = "$base/lib".PATH_SEPARATOR."$base/conf";
set_include_path(get_include_path().PATH_SEPARATOR.$path);

include "config.php";

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

function checktks($address) {
 global $com_device;
 $dec = hexdec($address);
 $command = sprintf('#TO_KBD%c%c%c%cV', $dec >> 16, $dec >> 8 & 255, $dec & 255, 4);
 $crc = crc($command);
 $command = sprintf('%s%c%c', $command, $crc >> 8, $crc & 255);
 exec("/bin/stty -F $com_device 2400 raw", $out);
 $com = fopen("$com_device", 'r+');
 fputs($com, $command);
 stream_set_blocking($com, 0);
 $s = microtime(true);
 $timeout = 1;
 $buffer = '';
 while (true) {
  if($timeout <= microtime(true) - $s) {
   break;
  }
  if(strlen($buffer) > 20) {
   break;
  }
  $buffer .= fgets($com, 128);
 }
 fclose( $com );
 $a = str_replace('#TO__PC', '', $buffer);
 $x[0] = ord($a[0]);
 $x[1] = ord($a[1]);
 $x[2] = ord($a[2]);
 $tks = '';
 foreach ($x as $key => $value) {
  $value = strtoupper(dechex($value));
  if(strlen($value) == 1) {
   $value = '0' . $value;
  }
  $tks .= $value;
 }
 if(strlen($tks) == 4) {
  $tks = '00' . $tks;
 }
 if(strlen($tks) == 5) {
  $tks = '0' . $tks;
 }
 if($tks == $address) {
  return true;
 }
 return false;
}

function hardResetTKS($address) {
 global $com_device;
 $dec = hexdec($address);
 $command = sprintf('#TO_KBD%c%c%c%cR', $dec >> 16, $dec >> 8 & 255, $dec & 255, 4);
 $crc = crc($command);
 $command = sprintf('%s%c%c', $command, $crc >> 8, $crc & 255);
 exec("/bin/stty -F $com_device 2400 raw", $out);
 $com = fopen("$com_device", 'r+');
 fputs($com, $command);
 stream_set_blocking($com, 0);
 $s = microtime(true);
 $timeout = 1;
 $buffer = '';
 while (true) {
  if($timeout <= microtime(true) - $s) {
   break;
  }
  if(strlen($buffer) > 20) {
   break;
  }
  $buffer .= fgets($com, 128);
 }
 fclose( $com );
 $a = str_replace('#TO__PC', '', $buffer);
 $x[0] = ord($a[0]);
 $x[1] = ord($a[1]);
 $x[2] = ord($a[2]);
 $tks = '';
 foreach ($x as $key => $value) {
  $value = strtoupper(dechex($value));
  if(strlen($value) == 1) {
   $value = '0' . $value;
  }
  $tks .= $value;
 }
 if(strlen($tks) == 4) {
  $tks = '00' . $tks;
 }
 if(strlen($tks) == 5) {
  $tks = '0' . $tks;
 }
 if($tks == $address) {
  return true;
 }
 return false;
}

?>
