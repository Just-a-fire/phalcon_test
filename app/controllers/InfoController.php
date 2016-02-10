<?php
/**
* 
*/
class InfoController extends \Phalcon\Mvc\Controller
{
	function ShowAction()
	{
		$value = $this->request->getPost("livesearch");
		// $wiki_page = file_get_contents('http://ru.wikipedia.org/wiki/'.$value);
		// $wiki_page = file_get_contents('https://ru.wikipedia.org/w/api.php?format=json&action=query&prop=extracts&exintro=&explaintext=&titles='.$value);
		// $wiki_page = file_get_contents('https://en.wikipedia.org/w/api.php?format=json&action=query&prop=extracts&exintro=&explaintext=&titles=Stack%20Overflow');
		$wiki_page = file_get_contents('https://ru.wikipedia.org/w/api.php?format=json&action=query&prop=revisions&rvprop=content&titles='.$value);
		$res = json_decode($wiki_page, true);

		$description = current(current($res['query']['pages'])['revisions'])['*'];

		$pos_s = mb_strpos($description, 'Столица');
		if ($pos_s !== false) {
			$pos_e = mb_strpos($description, '}}', $pos_s);
			$sentence = mb_substr($description, $pos_s, $pos_e - $pos_s);	
			$pos_m = mb_strpos($sentence, '|');
			if ($pos_m === false)
				$capital = $sentence;
			else {
				$capital = mb_substr($sentence, $pos_m + mb_strlen('|'));
				$capital = trim($capital);
				$pos_b = mb_strpos($capital, ']]') ? mb_strpos($capital, ']]') : mb_strpos($capital, '}}');
				if ($pos_b !== false)
					$capital = mb_substr($capital, 0, $pos_b);
			}
		} else {
			$capital = 'Не найдена';
		}
		
		$this->view->setVar('capital', $capital);
		

		$pos_s = mb_strpos($description, 'Население по переписи') ? mb_strpos($description, 'Население по переписи') : mb_strpos($description, 'Население');
		if ($pos_s !== false) {
			$pos_e = mb_strpos($description, '|', $pos_s);
			$sentence = mb_substr($description, $pos_s, $pos_e - $pos_s);	
			$pos_m = mb_strpos($sentence, '}}') ? mb_strpos($sentence, '}}') : mb_strpos($sentence, '= ');
			if ($pos_m === false)
				$population = $sentence;
			else {
				$population = mb_substr($sentence, $pos_m + mb_strlen('}}'));
				$population = trim($population);
				$pos_b = mb_strpos($population, '[') ? mb_strpos($population, '[') : mb_strpos($population, '{{');
				if ($pos_b !== false)
					$population = mb_substr($population, 0, $pos_b);
				// $population = intval($population);
			}
		} else {
			$population = 'Не найдено';
		}

		$this->view->setVar('population', $population);


		$pos_s = mb_strpos($description, 'Валюта');
		if ($pos_s !== false) {
			$pos_e = mb_strpos($description, '|', $pos_s);
			$sentence = mb_substr($description, $pos_s, $pos_e - $pos_s);	
			$pos_m = mb_strpos($sentence, '[[');
			if ($pos_m === false)
				$valuta = $sentence;
			else {
				$valuta  = mb_substr($sentence, $pos_m + mb_strlen('[['));
				$pos_b = mb_strpos($valuta, ']]');
				if ($pos_b !== false)
					$valuta  = mb_substr($valuta, 0, $pos_b);
			}
		} else {
			$valuta  = 'Не найдено';
		}

		$this->view->setVar('valuta', $valuta);


		$xmlContent = file_get_contents("https://pogoda.yandex.ru/static/cities.xml");
		$tablesRaw = new SimpleXMLElement($xmlContent);
		// $cities = simplexml_load_file("https://pogoda.yandex.ru/static/cities.xml");
		$cities = $tablesRaw;


		// print_r($cities);

		// foreach ($cities->country[0]->attributes() as $a => $b) {
		//     echo $a,'="',$b,"\"\n";
		// }
		$country_counter = 0;
		foreach ($cities as $key => $arr) {
			if ($arr['name'][0] == $value) {		
				// print_r($arr->attributes());
				// print_r("<br>key ".$country_counter."<br>");
				$cc = next($arr);
				$city_counter = 0;
				foreach ($cc as $key_ => $value_) {
					if ($value_ == $capital) {
						// print_r($value_);
						break;
					}
					++$city_counter;
				}
				// print_r(next($arr));
				// print_r($cities->$key);
				break;
			}
			++$country_counter;
		}

		$elements = $tablesRaw->country[$country_counter]->city[$city_counter];
		$cid = 0;
		foreach ($elements->attributes() as $a => $b) {
			if ($a == 'id')
				// echo $a,'="',$b,"\"\n";
				$cid = $b;
		}
		$data_file = 'http://export.yandex.ru/weather-ng/forecasts/'.$cid.'.xml';    
		$xml = simplexml_load_file($data_file);


		$arrayqq = json_decode(json_encode((array)$xml->fact[0]->temperature), TRUE);
		

		$this->view->setVar('temperature', $arrayqq[0]);



	}
}
?>