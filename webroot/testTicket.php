<?php

//function get_trusted_url($user,$server,$view_url) {
//    $params = ':embed=yes&:toolbar=yes';
//
//    $ticket = get_trusted_ticket($server, $user, $_SERVER['REMOTE_ADDR']);
//    var_dump($ticket);
//    if (strcmp($ticket, "-1") != 0) {
//        return "http://$server/trusted/$ticket/$view_url?$params";
//    }
//    else
//        return 0;
//}
//
//Function get_trusted_ticket($wgserver, $user, $remote_addr) {
//    $params = array(
//        'username' => $user,
//        'client_ip' => $remote_addr
//    );
//
//    //return http_parse_message(http_post_fields("http://$wgserver/trusted", $params))->body;
//    return http_post_data("http://$wgserver/trusted", $params);
//}


//echo get_trusted_url("cquest-rc\svetoslav.bozhinov","92.247.42.231","views/Superstore/Product")

//$opts = array('http' =>
//    array(
//        'method'  => 'POST',
//        'header'  => 'Content-type: application/x-www-form-urlencoded',
//        'content' => 'username=cquest-rcc\svetoslav.bozhinov&target_site=92.247.42.231'
//    )
//);
//
//$context  = stream_context_create($opts);
//
//$result = file_get_contents('http://92.247.42.231/trusted', false, $context);
//
//if ($result === false) {
//
//    throw new Exception("Problem reading data from $url, $php_errormsg");
//}
//
//else print_r($result);

//$server = "92.247.42.231";

//$username = "cquest-rc\svetoslav.bozhinov";

//$ch = curl_init($server);
//$data = array('username' => $username);
//
//curl_setopt($ch, CURLOPT_POST, true);
//curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//
//$ticket = curl_exec($ch);
//

    $params = array(
        'username' => 'cquest-rc\liliyar',
        'client_ip' => $_SERVER['REMOTE_ADDR']
    );

//$message = new HttpMessage();
function get_trusted_ticket($wgserver, $user, $remote_addr) {
    $server = $wgserver;
    $url = 'http://'.$server.'/trusted';
    $fields_string ='target_site=$remote_addr&username=$user';

    $ch = curl_init($url);
    $data = array('username' => $user, 'client_ip' => $remote_addr);

    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch,CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $ticket = curl_exec($ch);

        curl_close($ch);

    return $ticket;

}

function get_trusted_url($user,$server,$view_url) {
    $params = ':embed=yes&:toolbar=yes';

    $ticket = get_trusted_ticket($server, $user, $_SERVER['REMOTE_ADDR']);

    if (strcmp($ticket, "-1") != 0) {
        return "http://$server/trusted/$ticket/$view_url?$params";
    }
    else
        return 0;
}

//var_dump(get_trusted_ticket('92.247.42.231', 'cquest-rc\liliyar', $_SERVER['REMOTE_ADDR']));
//exit;
?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>Tableau Dashboard</title>

    <!--<script type='text/javascript' src='http://tableausrv.cquest-rc.com/javascripts/api/viz_v1.js'></script>-->
    <script type='text/javascript' src='http://92.247.42.231/javascripts/api/tableau-2.min.js'></script>
    <script type="text/javascript">
        function initViz() {

            var containerDiv = document.getElementById("vizContainer"),
                url = "<?php echo get_trusted_url("cquest-rc\liliyar","92.247.42.231","views/Superstore/Product")?>",
                options = {
                    hideTabs: true,
                    onFirstInteractive: function () {
                        console.log("Run this code when the viz has finished loading.");
                    }
                };

            var viz = new tableau.Viz(containerDiv, url, options);

            //alert("ZZZZ");
            // Create a viz object and embed it in the container div.

        }
    </script>
</head>

<body onload="initViz();">

<div id="vizContainer" style="width:800px; height:700px;"></div>
</body>

</html>