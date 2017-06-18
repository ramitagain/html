<?php

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
	}
	
	
	
	
	
	
?>
