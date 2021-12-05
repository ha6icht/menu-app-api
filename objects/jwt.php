<?php
class JWT{
    
    // database connection and table name
    private $conn;
    private $table_name = "menu_app_api_users";

    // object variables
    public $salt;
    public $jwt;
    public $sub;
    public $username;
    public $created;

    // database connection
    public function __construct($db){
        $this->conn = $db;
    }

    function isUsernameSet(){
        $query = "SELECT username, public_key FROM ". $this->table_name." WHERE username=:username;";
        $stmt = $this->conn->prepare($query);
        $this->username = htmlspecialchars(strip_tags($this->username));
        $stmt->bindParam(":username",$this->username);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        //echo $row["username"]."<br>";
        if ('' == $row["username"] && '' == $row["public_key"]) {
            return 0;
        } elseif (!('' == $row["username"]) && '' == $row["public_key"]) {
            return 1;
        }
        return 2;
    }

    function getSalt(){
        //generate random string
        $rand_salt = openssl_random_pseudo_bytes(32);

        //change binary to hexadecimal
        $this->salt = bin2hex($rand_salt);

        //token generated
        //echo 'generateSalt(): '.$this->salt.'<br>';
    }

    function getJsonWebToken(){
        // Create token header as a JSON string
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);

        // Create token payload as a JSON string
        $payload = json_encode(['sub' => $this->sub,
                                'name' => $this->username,
                                'iat' => time(),
                            ]);

        //echo $payload.'<br>';
        //echo $this->salt.'<br>';

        // Encode Header to Base64Url String
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));

        // Encode Payload to Base64Url String
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

        // Create Signature Hash
        $signature = hash_hmac('sha256', $base64UrlHeader.'.'.$base64UrlPayload, $this->salt, true);

        // Encode Signature to Base64Url String
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        // Create JWT
        $this->jwt = $base64UrlHeader.'.'.$base64UrlPayload.'.'.$base64UrlSignature;
        //echo 'getJsonWebToken(): '.$this->jwt;
    }
    function setValues(){
        $query = "INSERT INTO ".$this->table_name." 
        SET username=:username, public_key=:jwt, private_key=:salt, created=:created;";
        //echo $query;
        $stmt =$this->conn->prepare($query);
        //$stmt->htmlspecialchars(strip_tags($this->username));
        $stmt->bindParam(":username",$this->username);
        $stmt->bindParam(":jwt",$this->jwt);
        $stmt->bindParam(":salt",$this->salt);
        $stmt->bindParam(":created",$this->created);
        $stmt->execute();
    }
    function updateJWT(){
        $query = "UPDATE ".$this->table_name." 
        SET public_key=:jwt, private_key=:salt 
        WHERE username=:username;";
        //echo $query;
        $stmt =$this->conn->prepare($query);
        $this->username =htmlspecialchars(strip_tags($this->username));
        $stmt->bindParam(":username",$this->username);
        $stmt->bindParam(":jwt",$this->jwt);
        $stmt->bindParam(":salt",$this->salt);
        $stmt->execute();
    }
}