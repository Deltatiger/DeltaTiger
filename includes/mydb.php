<?php
/**
 * Description of mydb
 *  Temprary Db Class
 * @author Admin
 */
class mydb {
    private $connection;
    private $dbName;
    
    function __construct() {
        $server = "localhost";
        $username = "root";
        $password = "";
        $db = "deltatiger";
        
        $this->connection = mysql_connect($server, $username, $password);
        if(!$this->connection)    {
            //Cant Connect ?....
            die("Could Not Connect");
        } else {
            mysql_select_db($db, $this->connection);
        }
        $this->dbName = $db;
        unset($password);
    }


    public function query($query) {
        $query = mysql_query($query) or die(mysql_error());
        return $query;
    }
    
    public function returnDBName()	{
    	return $this->dbName;
    }
    
    public function return_DB_name()	{
    	return $this->dbName;
    }
}

?>
