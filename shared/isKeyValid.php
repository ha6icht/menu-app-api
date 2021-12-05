<?php

class isKeyValid
{
    // database connection and table name
    private $conn;
    private $table_name = 'menu_app_api_users';

    // public $public_key = htmlspecialchars($_POST["public_key"]);

    // constructor with $db as database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // read products
    public function getKey($public_key)
    {
        // select all query
        $query = 'SELECT
                    private_key
                FROM
                    '.$this->table_name.'where public_key = :public_key';

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $public_key = htmlspecialchars(strip_tags($public_key));

        // bind new values
        $stmt->bindParam(':public_key', $public_key);

        // execute the query
        if ($stmt->execute()) {
            // get retrieved row
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // set value to $private_key
            $private_key = $row['private_key'];

            // write $private_key to $privKey
            $privKey = <<<EOD
                {$private_key}
            EOD;

            // write $public_key to $pubKey
            $pubKey = <<<EOD
                {$public_key}
            EOD;

            // set data
            $data = 'Is there a key?';

            // Encrypt the data to $encrypted using the public key
            openssl_public_encrypt($data, $encrypted, $pubKey);

            // Decrypt the data using the private key and store the results in $decrypted
            // if true return true
            if (openssl_private_decrypt($encrypted, $decrypted, $privKey)) {
                return true;
            }

            return false;
        }
        // if the query could not be executed return false
        return false;
    }
}
