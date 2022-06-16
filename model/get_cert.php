<?php
require "../../common/common.php";

class Get_cert {
    
    public $sp_id;
    public $sp_password;
    public $user_id;
    public $transaction_id;
    public $serial_number;

    function getSpId(){
        return $this->sp_id;
    }

    function setSpId($sp_id){
        $this->sp_id = $sp_id;
    }

    function setSpPassword($sp_password){

        $this->sp_password =  $sp_password;
    }

    function setUserId($user_id){

        $this->user_id =  $user_id;
    }

    function setTransactionId($transaction_id){

        $this->transaction_id =  $transaction_id;
    }

    function setSerialNumber($serial_number){

        $this->serial_number = $serial_number;
    }

    function createJson(){
        $array = Array( "sp_id"=>$this->sp_id, 
        "sp_password" => $this->sp_password,
        "user_id" => $this->user_id, 
        "transaction_id" => $this->transaction_id,
        "serial_number" => $this->serial_number
        );
        return json_encode($array);
    }
}
?>