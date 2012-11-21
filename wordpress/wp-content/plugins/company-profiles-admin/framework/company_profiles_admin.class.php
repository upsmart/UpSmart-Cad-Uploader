<?PHP
/*
	 Author: Aaron Tobias
	 Company: MIT Lincoln Labs
*/
require_once "mysqli-database-wrapper.classes.php";

class Company_Profiles_Admin extends Mysqli_Database_Wrapper{


	public function __construct(){
	  parent::__construct();
		$this->init_all_admin_loadUp_preparedStatements();
		$this->init_all_admin_edit_preparedStatements();
	}
	
	private function init_all_admin_loadUp_preparedStatements(){
	
	  /*
		if(!($this->store_query("newGame", "INSERT INTO games (id, unixtimestamp, single_or_multiplayer) VALUES (?,?,?)" ) ) ){
			echo "Failed to prepare newgame SQL: (" . $this->database->errno . ") " . $this->database->error;
			return $this->error;
	  }
		
		if(!($this->store_query("newGameType", "INSERT INTO game_types_has_games (game_types_id, games_id) VALUES (?, ?)" ) ) ){
			echo "Failed to prepare newGameType SQL: (" . $this->database->errno . ") " . $this->database->error;
			return $this->error;
	  }
		*/
	}
	
	private function init_all_admin_edit_preparedStatements(){
	
	/*
		//update memoized total user score
		if(!($this->store_query("updateTotalScore", "UPDATE registered_users SET totalScore=totalScore + ? WHERE id=?"  ) ) ){
			echo "Failed to prepare updateTotalScore SQL: (" . $this->database->errno . ") " . $this->database->error;
			return $this->error;
	  }
		
	  //game stats
		if(!($this->store_query("gameStats", "UPDATE user_plays_games SET score=?, duration=? WHERE games_id=? AND user_id=?" ) ) ){
			echo "Failed to prepare gameStats SQL: (" . $this->database->errno . ") " . $this->database->error;
			return $this->error;
	  }
		*/
	}
	
}	
?>