<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Web Service Client</title>
    </head>
    <body>
        <?php
        include_once ('../config/config.php');
        $client = new SoapClient(BASE_URL . 'services/wsdl.wsdl');
        $response = $client->getAllPatients(14);
        //$response = $client->getPatient(14, 'ahmas@gmail.com');
        $result = json_decode($response);
        echo '<pre>';
        print_r($result);
        echo '</pre>';
        ?>
    </body>
</html>
