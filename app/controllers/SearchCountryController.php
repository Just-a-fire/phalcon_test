<?php
/**
* 
*/
class SearchCountryController extends \Phalcon\Mvc\Controller
{

    public function searchAction()
    {
    	$connection = new Phalcon\Db\Adapter\Pdo\Mysql(array(
            "host"     => "localhost",
            "username" => "root",
            "password" => "",
            "dbname"   => "test_phalcon"
        ));
    	$value = $this->request->getPost("value");
    	
		$resultset = $connection->query("SELECT * FROM country WHERE name LIKE '%$value%'");
		$countries = $connection->fetchAll("SELECT * FROM country WHERE name LIKE '%$value%' LIMIT 5", Phalcon\Db::FETCH_ASSOC);
		foreach ($countries as $country) {
		    echo $country['name']."!!!";
		}
    }
}
?>