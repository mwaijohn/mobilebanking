<?php
class Connection{
    static function returnConnection(){

        $conn_config = file_get_contents('../resource.json');
        $config_data = json_decode($conn_config,true);

        $servername = "";
        $username = "";
        $password = "";
        $conn = null;

        foreach ($config_data as $key => $value) {
          $servername = $value['servername'];
          $username = $value['username'];
          $password = $value['password'];
        }
        try {
            $conn = new PDO("mysql:host=$servername;dbname=_bankdb", $username);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //echo "Connected successfully";
            return $conn;
            }
        catch(PDOException $e)
            {
            echo "Connection failed: " . $e->getMessage();
            return null;
            }
    }
}

//$nn = new Connection();
//$nn->returnConnection();
$conn = Connection::returnConnection();
?>
