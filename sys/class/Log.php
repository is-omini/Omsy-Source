<?php

class Log {
    public $CMS;
    function __construct($CMS) {
        $this->CMS = $CMS;
    }

    public function added($message, $type = 0) {
        $this->CMS->DataBase->execute("INSERT INTO web_log (type, message, log_date) VALUES (?,?,now())", [$type, $message]); 
    }

    public function Report($e): void {
        $Date = date('H:i:s d-m-Y');
        if (gettype($e) === "object"){
            $e = ["File"=> $e->getFile(), "Message" => $e->getMessage(), "Datetime" => $Date];
        } else {
            $e["Datetime"] = $Date;
        }   
        $this->CMS->DataBase->execute("INSERT INTO logs (id, Datetime, file, Message) VALUES (null, :Datetime, :File, :Message)", $e); 
        exit(htmlspecialchars("File : ".$e["File"]." - ".$e["Message"]));
    }
}
