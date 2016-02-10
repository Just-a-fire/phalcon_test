<?php
/**
* 
*/
class IndexController extends \Phalcon\Mvc\Controller
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
		$this->assets->addJs('js/autocomplete.js');
	}

	function testAction($param = "ТЕСТ")
	{
		echo "Параметр равен {$param}";
	}
}
?>