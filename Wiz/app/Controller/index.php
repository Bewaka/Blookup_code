<?php
   
   class WisappConfig {

		var $_config;
		var $_view;
		var $_model;
		
		function __construct($path) {
			$this->_path = $path;
			$xmlDoc = new DOMDocument();
			$xmlDoc->load($path);

			$this->_config = $xmlDoc->documentElement;
		}
	
		function getWisapp() {
	
			$wisapp = new stdClass();
			foreach ($this->_config->childNodes AS $item)
			{
				switch($item->nodeName)
				{
					case "title":
						$wisapp->title = $item->nodeValue;
						break;
					case "description":
						$wisapp->description = $item->nodeValue;
						break;
					case "tags":
						$str="";
						$tags = $item->getElementsByTagName("tag");
						foreach($tags as $tag)
						{
							if($str) $str = $str . "," . $tag->nodeValue ;
							else $str = $str . $tag->nodeValue ;
						}					
						$wisapp->tags = $str;
						break;
					case "version":
						$wisapp->version = $item->nodeValue;
						break;
					case "logo":
						$wisapp->logo = $item->nodeValue;
						break;
				}
			}

			return $wisapp;		
		}
		
		function generateSQLScript() 
		{
			$script = "";
			$entities = $this->_config->getElementsByTagName("entity");
			foreach($entities as $entity)
			{
				$tn = $entity->getAttribute("name");
				$script = $script . "------------TABLE ".$tn."------------------<br /><br />";
				$query = "CREATE TABLE ".$tn." (id INT(11) NOT NULL AUTO_INCREMENT,".
				"PRIMARY KEY (id)) ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8";
				$script = $script . $query. "<br />";
				$fields = $entity->getElementsByTagName("field");
				foreach($fields as $field)
				{
					$query = "ALTER TABLE ".$tn." ADD ".$field->nodeValue." TEXT NULL". "<br />";
					$script = $script . $query;
				}
				
			}
			return $script;
		}
	} 
  $config = new	WisappConfig("ex1.txt");
  $wisapp = $config->getWisapp();
  print var_dump($wisapp);
  $sql = $config->generateSQLScript();
  print $sql;

?>