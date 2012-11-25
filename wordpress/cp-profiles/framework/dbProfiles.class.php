<?PHP

class DBS_PROFILE{

   //private variables
   private $database, $host, $user, $pass;
   private $page;
   private $conn;
   private $fields = array();
   private $error;
   public $debug = 0;

   function DBS_PROFILE($template){
     $this->host = "localhost";
     $this->user = "goupsmar";
     $this->pass = "6pIlSh946q";
     $this->database = "goupsmar_upsmart";
     $this->page = $template;
     $this->error = 0;
   }
   
   public function connect(){
     if (!function_exists('mysqli_connect')) {
	  //echo "mysqli does not exist";
	  return $this->error;
     }  
     
     $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->database);
     if ($this->conn->connect_errno) {
      echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
       return $this->error;
     }
     return $this->conn ;
   }
   
   
	private function bindVars($stmt,$params) {
	    if ($params != null) {
	        $types = '';                        //initial sting with types
	        foreach($params as $param) {        //for each element, determine type and add
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
	 
	        $bind_names[] = $types;             //first param needed is the type string
	                                            // eg:  'issss'
	 
	        for ($i=0; $i<count($params);$i++) {//go through incoming params and added em to array
	            $bind_name = 'bind' . $i;       //give them an arbitrary name
	            $$bind_name = $params[$i];      //add the parameter to the variable variable
	            $bind_names[] = &$$bind_name;   //now associate the variable as an element in an array
	        }
	 
	                                            //call the function bind_param with dynamic params
	        call_user_func_array(array($stmt,'bind_param'),$bind_names);
	    }
	    return $stmt;                           //return the bound statement 
	}
	
     //http://stackoverflow.com/questions/201468/are-dynamic-prepared-statements-bad-with-php-mysqli
     //http://stackoverflow.com/questions/280798/is-there-a-way-to-bind-an-array-to-mysqli-prepare

   /*
   Function Use: 
   @arg1: array, SELECT clause return values
   @arg2: array, FROM clause table values
   @arg3: associative array of WHERE clause options
     key: a row attribute in a table
     options: array,
       index 0: assignment operator
       index 1: value
   */
   public function query($query){
   	return $this->conn->query($query);
   }
   
   public function retrieve( $values, $tables, $whereClause)
   {
     if(!is_array($values) || !is_array($tables) || !is_array($whereClause)){
      return $this->error;
     }
     
     //$params = array();
     $numValues = count($values);
     
     for($i=0;$i< $numValues; $i++) {
        $params[] = &$values[$i]; 
     }
     
     $requestVal = join( ", ", $values );
     $tables = join( ", ", $tables );
     
     $whereStatement = array();
     $preparedValues = array();
     
     foreach( $whereClause as $key => $options){
     
        if(!is_array($options)) { return $this->error; }
        	
	$whereStatement [] = $key . $options[0] ."?";
	$preparedValues [] = $options[1];

     }
     
     if($debug){
     echo "<p>" . "SELECT " . $requestVal . " FROM ". $tables ." WHERE ". join(' AND ',$whereStatement) . "</p>"; 
     echo "<p>" . join(', ',$preparedValues) . "</p>";
     }
     
     $stmt = $this->conn->stmt_init();
     if($stmt->prepare( "SELECT " . $requestVal . " FROM ". $tables ." WHERE ".  join(' AND ',$whereStatement) ) ){
	    
	$this->bindVars($stmt, $preparedValues);
	$stmt->execute();

	//bind results dynamically
	call_user_func_array(array($stmt, 'bind_result'), $params); 
	/* get resultset for metadata */
	$result = $stmt->result_metadata();
	
	/* retrieve field information from metadata result set */
	$field = $result->fetch_field();
	
	$results = array(); 
	$rows = array();

	while ($stmt->fetch()) {   
            if($debug){ echo "<p> here </p>"; }
	    foreach($values as $key => $val)
	    {
	        $results[$key] = $val;
	        
	    }
	    $rows[] = $results;

	}
	if($debug){
	var_dump($rows);
	}
	$stmt->close();
	return $rows;

     }
     else{
    	 echo "failed: (" . $stmt->errno . ") " . $stmt->error;
    	 return $stmt->errno;
     }

   }
  
   public function clean($elem)
   {
     if(!is_array($elem))
       $elem = htmlentities($elem,ENT_QUOTES,"UTF-8");
     else
       foreach ($elem as $key => $value)
           $elem[$key] = $this->clean($value);
           
    return $elem;
   }
   
   public function determineLogo($compLogo){
   	
   	$imgsrc = "";
   	$logoData = array();
   
	if($compLogo == "."){
	 $imgsrc = "images/Questionmark.jpg";
	 $compLogo = "/cp-profiles/images/Questionmark.jpg";
	}
	else{
	 $imgsrc = "companies/" . $compLogo;
	 $compLogo = "/cp-profiles/companies/" . $compLogo;
	}
	$logoData[] = $imgsrc;
	$logoData[] = $compLogo;
	
	return $logoData;
   }
   
public function scaleImg($imgsrc, $max_width = 230, $max_height = 230){

	//getting the image dimensions
	list($original_width, $original_height) = getimagesize($imgsrc);

	$dimensions = array();
  
	if (($original_width > $max_width) OR ($original_height > $max_height))
	{
	//original width exceeds, so reduce the original width to maximum limit.
	//calculate the height according to the maximum width.
	    if(($original_width > $max_width) AND ($original_height <= $max_height))
	    {   
	        $percent = $max_width/$original_width;  
	        $new_width = $max_width;
	        $new_height = round ($original_height * $percent);
	    }
	
	    //image height exceeds, recudece the height to maxmimum limit.
	    //calculate the width according to the maximum height limit.
	    if(($original_width <= $max_width) AND ($original_height > $max_height))
	    {
	        $percent = $max_height/$original_height;
	        $new_height = $max_height;
	        $new_width = round ($original_width * $percent);
	    }
	
	    //both height and width exceeds.
	    //but image can be vertical or horizontal.
	    if(($original_width > $max_width) AND ($original_height > $max_height))
	    {
	        //if image has more width than height
	        //resize width to maximum width.
	        if ($original_width > $original_height)
	        {
	            $percent = $max_width/$original_width;
	            $new_width = $max_width;
	            $new_height = round ($original_height * $percent );
	        }
	
	        //image is vertical or square. More height than width.
	        //resize height to maximum height.  
	        else
	        {
	        $new_height = $max_height;
	        $percent = $max_height/$original_height;
	        $new_height = $max_height;
	        $new_width = round ($original_width * $percent);
	        }
	    }
	} 
	array_push($dimensions, $new_height, $new_width);
	
	return $dimensions;
}
   
   

   public function return_404() {
     status_header(404);
     nocache_headers();
     include( get_404_template() );
     exit;   
   }


}
?>