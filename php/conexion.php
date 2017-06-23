<?php
	header("Content-Type: text/html;charset=utf-8");
	class Connection{
		private $dbhost="localhost";
		private $dbuser="postgres";
		private $dbpass="pokemon";
		private $dbname="testing";
		private $error;
		private $conexion;
		
		public function __construct(){
			$cadena="pgsql:host=".$this->dbhost.";dbname=".$this->dbname;
			$options = array(
				//PDO::ATTR_PERSISTENT => true,
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
			);
			try{
				$this->conexion=new PDO($cadena,$this->dbuser,$this->dbpass,$options);
			}catch(PDOException $e){
				$this->error=$e->getMessage();
			}
		}	
		
		public function close() {
			$this->conexion = null;
		}
		
		public function prepare($statement) {
			return $this->conexion->prepare($statement);
		}  
		
		public function execute(){
			return $this->conexion->execute();
		}
		
		public function commit(){
			return $this->conexion->commit();
		}
		
		public function rollBack(){
			return $this->conexion->rollBack();
		}

		public function beginTransaction(){
			return $this->conexion->beginTransaction();
		}
		
		public function lastInsertId($id){
			return $this->conexion->lastInsertId($id);
		}
		
		public function getError(){
			return $this->error;
		}
		public function IsSessionID($session_id){
			$datos = Array('session_id' => $session_id);
			$query = $db->prepare('SELECT * FROM usuario WHERE session_id =:session_id');
			foreach ($datos as $campo => $valor) {
				$query->bindValue(":".$campo,$valor);
			}
			$query->execute();
			$cant_filas = $query->rowCount();
			if($cant_filas > 0){
				return true;
			}else{
				return false;
			}
		}
	}
	
	
	
	
	
	
?>
