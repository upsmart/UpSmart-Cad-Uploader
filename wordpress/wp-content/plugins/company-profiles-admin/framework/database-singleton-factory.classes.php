<?PHP

Class Config 
{ 
    const host = "localhost"; 
    const user = "goupsmar"; 
    const pass = "6pIlSh946q"; 
    const dbname = "goupsmar_upsmart";
	  const port = "";
    const dsn = "mysql:dbname=;host=;port=";		
}

Class Registry
{
    private static $_objects = array();

    public static function set($key, $object)
    {
        if (!array_key_exists($key, self::$_objects)) 
					self::$_objects[$key] = $object;
    }

    public static function get($key)
    {
        if (array_key_exists($key, self::$_objects)) {
					return self::$_objects[$key];
					}
        else 
					return false;
    }
}

Class DBFactory extends Config
{
    public static function getConnection($type)
    {
        switch ($type) {
            case 'mysqli':
                if (!(Registry::get('mysqli') instanceof mysqli))
									Registry::set('mysqli', new mysqli ( 
										parent::host, 
										parent::user, 
										parent::pass, 
										parent::dbname
										)
									);
									
									if (mysqli_connect_errno()) { 
									printf 
									( 
											"Connection Error: %s\n ", mysqli_connect_error() 
									); 
									debug_print_backtrace();
									} 
									else { /*echo "Database resource called.\n";*/ }
									
									return Registry::get('mysqli');
									
									
				    case 'pdo':
								if (!(Registry::get('PDO') instanceof PDO)) 
								Registry::set('PDO', new DbPdo(
										parent::dsn, 
										parent::user, 
										parent::pass
									)
								);
								return Registry::get('PDO');
								
            // I can add more php sql drivers if needed
        }
    }
}
?>
