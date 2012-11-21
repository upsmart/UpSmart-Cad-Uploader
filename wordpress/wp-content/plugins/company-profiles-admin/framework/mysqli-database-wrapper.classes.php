<?php

require_once "database-singleton-factory.classes.php";
require_once "stored-query-engine.interface.php";

Class Database_Type{

    protected $database;
		
    protected function __construct($type) {
			$this->database = DBFactory::getConnection($type);
		}
}

//mysqli custom wrapper for game
Class Mysqli_Database_Wrapper extends Database_Type implements Stored_Query_Engine{

    private $debug;
		protected $error;
		protected $success;
		
		// Associative array of keyName => query instance 
		private $storedQueries = array();
		
    protected function __construct() {
			parent::__construct('mysqli');
			$this->error = false;
			$this->success = true;
		}
		
		public function pingConnection(){
			if ($this->database->ping()) {
				printf ("Our connection is still ok!\n");
				} else {
				printf ("Error: %s\n", $mysqli->error);
				}
		}
		
		function __destruct() {
			//remove all stored queries
			
			
			//close database connection
		}
		
		public function store_query($keyName, $query){
		$q = $this->database->stmt_init();
			if($q = $this->database->prepare($query)) {
				$this->storedQueries[$keyName] = &$q;
				return $this->success;
			}
			else{
				return $this->error;
			}
		}
		
		public function get_stored_query($keyName){
			return $this->storedQueries[$keyName];
		}
		
		public function close_stored_queries($keyNames){
			if(is_array($keyNames)){
				foreach($keynames as $key => $squery){
				$squery->close();
					unset($this->storedQueries['$key']);
				}
			}
			else{
				return $this->error;
			}
		}
		
    private function _bindParams($query, $args, $argOffset = 0){
			$args = array_merge( array($args[1+$argOffset]), array_slice($args, 2+$argOffset) );
			$args_ref = array();
			$types = '';                        //initial sting with types
			foreach($args as $param) {          //for each element, determine type and add
					if(is_int($param)) {
							$types .= 'i';              //integer
					} elseif (is_float($param)) {
							$types .= 'd';              //double
					} elseif (is_string($param)) {
							$types .= 's';              //string
					} else {
							$types .= 'b';              //blob and unknown
					}
			}
			$args_ref[]=$types;
			foreach($args as $k => &$arg) {
					$args_ref[$k+1] = &$arg; 
			}
			call_user_func_array(array($query, 'bind_param'), $args_ref);
		}
    
    private function _execute($query){
		
			$query->execute();

			if ($query->errno) {
				if ($this->debug) {
					echo mysqli_error($this->database);
					debug_print_backtrace();
				}
				return false;
			}

			if ($query->affected_rows > -1) {
					return $query->affected_rows;
			}

			$params = array();
			$meta = $query->result_metadata();
			while ($field = $meta->fetch_field()) {
					$params[] = &$row[$field->name];
			}
			call_user_func_array(array($query, 'bind_result'), $params);

			$result = array();
			while ($query->fetch()) {
					$r = array();
					foreach ($row as $key => $val) {
							$r[$key] = $val;
					}
					$result[] = $r;
			}

			return $result;
		}
		
		
		public function execute_stored($query){
				if (func_num_args() > 1) {
						$this->_bindParams($query, func_get_args());
						$result = $this->_execute($query);
						return $result;
				}
		}
		
		/*standard query*/
    public function get($query) {
				
				if($query = $this->database->prepare($query)) {
					
					if (func_num_args() > 1) {
							$this->_bindParams($query, func_get_args());
					}
					
					$result = $this->_execute($query);
					$query->close(); 
					
					return $result;
				} 
				else {
					if ($this->debug) {
							echo $this->database->error;
							debug_print_backtrace();
					}
					return $this->database->error;
				}
    }
		
		/*Specific query to be used for performing datbase updates */
		function update($query){
				
				if($query = $this->database->prepare($query)) {
					
					if (func_num_args() > 1) {
							$this->_bindParams($query, func_get_args());
					}
					
					$result = $this->_execute($query);
					$rows_affected = $this->database->affected_rows;
					$query->close(); 
					
					/* The magic */
					if($rows_affected == 0)
						return -2;
					
					return $result;
				} 
				else {
					if ($this->debug) {
							echo $this->database->error;
							debug_print_backtrace();
					}
					return $this->database->error;
				}
		}
		
		// this is incomplete for now ... need to store results in a multidimensional array and return it
		public function multiQuery($queryArray){
		    
				if(is_array($queryArray)){
				
					if ($this->database->multi_query(implode(';', $queryArray)) ) {
						  $i = 0;
							do {
							    $i++;
									/* store first result set */
									if ($result = mysqli_store_result($link)) {
											while ($row = mysqli_fetch_row($result)) {
													printf("%s\n", $row[0]);
											}
											mysqli_free_result($result);
									}
									/* print divider */
									if (mysqli_more_results($link)) {
											printf("-----------------\n");
									}
							} 
							while ($this->database->more_results() && $this->database->next_result());
							
							if ($this->database->errno) { 
									echo "Batch execution prematurely ended on statement ". $i . "\n"; 
									var_dump($statements[$i], $this->database->error); 
							} 
					}
				}
		}
}

?>