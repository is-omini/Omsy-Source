<?php
class DataBase {
    private $PDO;
    private $Result;
    private $CMS;
    public $Success;
    function __construct($CMS) {
        $this->CMS = $CMS;
        include("./sys/class/PDO_htmlspecialchars.php");
        try {
            $this->PDO = new PDO(
                "mysql:host=".$CMS->Config->database->host.";dbname=".$CMS->Config->database->dbname, 
                $CMS->Config->database->username, 
                $CMS->Config->database->password
            );
           $this->PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
           $this->PDO->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
           $this->PDO->setAttribute(PDO::ATTR_STATEMENT_CLASS, ['PDO_htmlspecialchars']);
        } catch (Exception $e) {
           //$Data->Call("Log")->Report($e);
        }

    }
    public function fetch($key = null){
        /*
        if ($this->Result === null){
            return null;
        }
        */
        if ($key !== null){
            return $this->Result->fetch()->$key;
        }
        return $this->Result->fetch();
    }
    public function fetchAll($index = null){
        if ($this->Result === null){
            return null;
        }
        if ($index !== null){
            return $this->Result->fetchAll()[$index];
        }
        return $this->Result->fetchAll();
    }  
    public function execute($Request, $Params = null, $Protect = true) {  
        try {
            $stmt = $this->PDO->prepare($Request);
            if ($Params === null){
              $this->Success = $stmt->execute();
            } else {
              $this->Success = $stmt->execute($Params);
            }
            $this->Result = $stmt;
           
        } catch (Exception $e) {
            $this->Success = false;
        }
        return $this;
    }
    private function Print($Request, $Type, $Key, $Value) : void{
        exit(
        "</br></br></br></br>/!\ Warning when using an SQL query in the format: ".str_replace($Value, "\$_".$Type."['$Key']", $Request).".</br>
        This poses risks. Use :key or (?) instead.</br>
        Example 1: DataBase::execute(\"INSERT INTO example (name) VALUES (?)\", [\$_".$Type."['$Key']])</br>
        Example 2: DataBase::execute(\"INSERT INTO example (name) VALUES (:name)\", [\":name\" => \$_".$Type."['$Key']])</br>
        Unless you're certain it doesn't go through a GET request, use the parameter Protect:false.</br>
        Example 1: DataBase::execute(Request: \"$Request\", Protect:false)</br>
        Example 2: DataBase::execute(\"$Request\", Execute:[], Protect:false)");

    }
}