<?php
class Members {
    private $CMS;
    private $mailSet;
    function __construct($CMS){
        $this->CMS = $CMS;
    }

    public function get($id, array $array=['ID']) {
        $RequestData = [];
        $Request = 'SELECT * FROM account WHERE';
        foreach ($array as $value) {
            if(count($RequestData) > 0) {
                $Request .= ' OR';
            }
            $Request .= " {$value} = ?";
            $RequestData[] = $id;
        }
        //echo $Request;
        $req = $this->CMS->DataBase->execute($Request,$RequestData)->fetch();
        if(!$req) return null;

        $listVar = $this->CMS->DataBase->execute('SELECT * FROM account_variable WHERE user_id = ?', [$req->uniqid])->fetchAll();

        $arrayobj = new ArrayObject($req);

        foreach($listVar as $value) {
            $arrayobj->offsetSet($value['name'], $value['content']);
        }

        return $req;
    }

    public function Register($pseudo, $email, $password, $birthday, $id){
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false){
            return false;
        }
        $Date = date('H:i:s d-m-Y');
        //$hashpassword = hash('sha512', $this->CMS->Config->server->privatekey.$password);
        $hashpassword = password_hash($password, PASSWORD_DEFAULT);

        $AccountCreate = $this->CMS->DataBase->execute(
            "INSERT INTO account (uniqid, username, mail_, password, birthday, date_, role) VALUES (?, ?, ?, ?, ?, now(), ?)",
            [$id, $pseudo, $email, $hashpassword, $birthday, 0]
        )->Success;

        if ($AccountCreate === true){
            $EmailConfirmation = $this->CMS->DataBase->execute("INSERT INTO confirmation (email, code, createat) VALUES (:email, :code, :createat)", [":email" => $email, ":code" => $this->CMS->Function->RandomCode(), ":createat" => $Date
            ])->Success;
            return ($AccountCreate && $EmailConfirmation);
        } else {
            return false;
        }
    }

    public function Login($email, $password) {
        $ip = $_SERVER['REMOTE_ADDR'];
        //$hashpassword = hash('sha512', $this->CMS->Config->server->privatekey.$password);
        $Date = date('H:i:s d-m-Y');
        $logs = ["date" => $Date, "ip" => $ip, "Message" => ""];
        $Connection = $this->CMS->DataBase->execute("SELECT * FROM account WHERE mail_ = :email", [":email" => $email]);
        $parse = $Connection->fetch();
        var_dump($parse);
        if ($parse !== false) {
            if (password_verify($password, $parse->password)){
                $logs["Message"] = "Connexion réussi";
                $_SESSION["IsConnected"] = true;
                $_SESSION["Role"] = (int) $parse->role;
                
                foreach ($parse as $key => $value) {
                    if (!in_array($key, ["id", "password"])){
                        $_SESSION[$key] = $value;
                    }
                }
                $AccountCreate = $this->CMS->DataBase->execute("INSERT INTO lastconnect (email, logs) VALUES (:email, :logs)", [":email" => $email, ":logs" => json_encode($logs, true)])->Success;
                return true;
            } else {
                $logs["Message"] = "Tentative de connexion au compte";
                $AccountCreate = $this->CMS->DataBase->execute("INSERT INTO lastconnect (email, logs) VALUES (:email, :logs)", [":email" => $email, ":logs" => json_encode($logs, true)])->Success;
                return false;
            }
        } else {
            return false;
        }        
    }

    public function Confirmed($email, $code) {
        $Request = $this->CMS->DataBase->execute("SELECT * FROM confirmation WHERE email = :email and code = :code" , [":email" => $email, ":code" => $code])->fetch();
        if ($Request === false){
           return false;
        } else {
            $idmember = $this->CMS->DataBase->execute("SELECT * FROM account WHERE email = :email", [":email" => $email])->fetch("id");      
            $Update = $this->CMS->DataBase->execute("UPDATE account SET confirmed = :confirmed where email = :email and id = :id", [":confirmed" => true, ":email" => $email, ":id" => $idmember])->Success;
            $Delete = $this->CMS->DataBase->execute("DELETE FROM confirmation WHERE id = :id and email = :email and code = :code", [":id" => $Request->id, ":email" => $email, ":code" => $code])->Success;
            return ($Update && $Delete);
        }
    }

    public function IsConfirmed($email){
        $Request = $this->CMS->DataBase->execute("SELECT * FROM account WHERE email = :email", [":email" => $email]);
        return $Request->fetch("confirmed") === "1";
    }
    public function SetPicture($Picture){}

    public function setPassword($str) {
        $hashpassword = hash('sha512', $this->CMS->Config->server->privatekey.$password);
        CMS->DataBase->execute('UPDATE account SET password = ? WHERE ID = ?', [$hashpassword, $_SESSION['ID']]);
    }
}