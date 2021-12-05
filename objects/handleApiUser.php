<?php
class HandleApiUser{
    // database connection and table name
    private $conn;
    private $table_name = "menu_app_api_users";

    // object variables
    public $username;
    public $email;
    public $firstname;
    public $lastname;
    public $user_password;
    public $created;

    // database connection
    public function __construct($db){
        $this->conn = $db;
    }

    function getUser(){

        $query = "SELECT COUNT(*) as num_username FROM ".$this->table_name." WHERE username=:username;";
        $stmt = $this->conn->prepare($query);
        $this->username = htmlspecialchars(strip_tags($this->username));
        /*$this->email = $stmt->htmlspecialchars(strip_tags($this->email));
        $this->firstname = $stmt->htmlspecialchars(strip_tags($this->firstname));
        $this->lastname = $stmt->htmlspecialchars(strip_tags($this->lastname));
        $this->user_password = $stmt->htmlspecialchars(strip_tags($this->user_password));*/
        $stmt->bindParam(":username",$this->username);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row["num_username"]==0){
            return false;
        }
        return true;
    }
    function setUser(){
        $query = "INSERT INTO ".$this->table_name." 
            SET username=:username, 
            email=:email, 
            firstname=:firstname, 
            lastname=:lastname, 
            user_password=:user_password, 
            created=:created;";
        $stmt = $this->conn->prepare($query);
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->firstname = htmlspecialchars(strip_tags($this->firstname));
        $this->lastname = htmlspecialchars(strip_tags($this->lastname));
        $this->user_password = htmlspecialchars(strip_tags($this->user_password));
        $stmt->bindParam(":username",$this->username);
        $stmt->bindParam(":email",$this->email);
        $stmt->bindParam(":firstname",$this->firstname);
        $stmt->bindParam(":lastname",$this->lastname);
        $stmt->bindParam(":user_password",$this->user_password);
        $stmt->bindParam(":created",$this->created);
        if($stmt->execute()){
            return true;
        }
        return false;
    }
    function updateUser(){
        $query = "UPDATE ".$this->table_name."
            SET email=:email, 
            firstname=:firstname, 
            lastname=:lastname, 
            user_password=:user_password 
            WHERE username=:username;";
        $stmt = $this->conn->prepare($query);
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->firstname = htmlspecialchars(strip_tags($this->firstname));
        $this->lastname = htmlspecialchars(strip_tags($this->lastname));
        $this->user_password = htmlspecialchars(strip_tags($this->user_password));
        $stmt->bindParam(":username",$this->username);
        $stmt->bindParam(":email",$this->email);
        $stmt->bindParam(":firstname",$this->firstname);
        $stmt->bindParam(":lastname",$this->lastname);
        $stmt->bindParam(":user_password",$this->user_password);
        if($stmt->execute()){
            return true;
        }
        return false;
    }   
}