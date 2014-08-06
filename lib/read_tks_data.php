<?PHP

function read_server_data() {

        global $server_data_file, $tksid_by_servername, $tksid_by_macaddress, $servername_by_tksid, $location_by_servername;

        error_reporting(0);
        $data = fopen($server_data_file,"r");
        if(!$data) {
                $e = error_get_last();
                lg_err("Can not open data file: $server_data_file:".$e["message"]);
                exit(1);
        }
        error_reporting(E_ALL);
        while (false !== ($line = fgets($data))){
                list ($servername,$tksid,$location) = explode(",",$line);

                if (preg_match('/^amt$/i'   ,$tksid))      { continue; }
                if (preg_match('/^server$/i',$servername)) { continue; }
                if (preg_match('/^#/'       ,$servername)) { continue; }

                # $macaddress                           = strtolower($macaddress);
                $servername                             = strtolower($servername);
                $tksid                                  = strtolower($tksid);

                $tksid_by_servername["$servername"]     = $tksid;
                $servername_by_tksid["$tksid"]          = $servername;
                $location_by_servername["$servername"]  = $location;

        }
        fclose($data);
}

?>
