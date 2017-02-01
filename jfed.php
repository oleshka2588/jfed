<?php
/**
class Views
{
    public $name;
    public function showName()
    {

        
        return($this->name);
    }
    public function enterName($TName)
    {
        $this->name = $TName;
        
    }
}**/
$servername = "localhost";
$username = "root";
$password = "";
$bd = "jfed";

// Create connection
$conn = mysqli_connect($servername, $username, $password) or die("Error " . mysqli_error($conn));

// Check connection
/**
if (!$conn) {
    die("Connection failed: " . mysql_error());

}
**/	
echo "Connected successfully";


?>