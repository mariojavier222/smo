<?php

class Database{
	private $con;
	private $dbhost="";
	private $dbuser="";
	private $dbpass="";
	private $dbname="";
	
	function __construct($host, $user, $pass, $db){
		
		$this->dbhost = $host;
		$this->dbuser = $user;
		$this->dbpass = $pass;
		$this->dbname = $db;
		$this->connect_db();
		
	}
	public function connect_db(){
		$this->con = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);
		
		if($this->con->connect_errno){
			die("Conexión a la base de datos falló " . $this->con->connect_error . $this->con->connect_errno);
		}
	}//fin function
	public function sanitize($var){
	  $return = mysqli_real_escape_string($this->con, $var);
	  return $return;
	}
	public function ejecutar($sql){
	    $data = array();
		$cons = $this->con->query($sql);
		if(!$cons)
		{
			$data["success"] = false;
			$data["error"] = "Se produjo un error en la sentencia [$sql]: " . $this->con->error." Num:" . $this->con->errno;
			
		}
		else
	    {
	    	$data["success"] = true;
	    	$data["msg"] = "Consulta ejecutada correctamente";
	    	$data["id"] = $this->con->insert_id;
	    }
		return $data;
	}
	public function consulta($sql){	  
		try {
		    $return = $this->con->query($sql);
		    return $return;
		} catch (Exception $e) {
		    if($this->con->error){
				/*die("Se produjo un error [$sql]. Error: " . $this->con->error." Num:" .$this->con->errno);*/
				echo "Se produjo un error [$sql]. Error: " . $this->con->error." Num:" .$this->con->errno;
			}
			echo 'Excepción capturada: ',  $e->getMessage(), "\n";
		}
	  
	  	
	  
	}

	public function getConsulta($sql) {        
        $result = $this->consulta($sql);
        //echo $sql;
        if ($result->num_rows>0){
            return true;
        }else{
            return false;
        }
    }//fin function

    public function ultimoID(){
    	return $this->con->insert_id;
    }

    public function getLink()
    {
    	return $this->con;
    }

    public function cerrarConexion()
    {    	
    	$this->con->close();
    }
	
}//fin class
?>