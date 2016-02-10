<?php
/**
* 
*/
class MyController extends \Phalcon\Mvc\Controller
{
	function onConstruct()
	{
		echo "const<br>";
	}

	function initialize()
	{
		echo "init<br>";
	}

	function IndexAction()
	{
		
	}

	function testAction($param = "ТЕСТ")
	{
		echo "Параметр равен {$param}";
	}
}
?>