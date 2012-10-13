<?PHP

interface Stored_Query_Engine{
		
		public function store_query($keyName, $query);
		public function get_stored_query($keyName);
		public function close_stored_queries($keyNames);
}

?>