<?php

class WisappConfig extends AppController{

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
					case "name":
						$wisapp->name = $item->nodeValue;
						break;
					case "description":
						$wisapp->description = $item->nodeValue;
						break;
					case "tags":
						$wisapp->tags = $item->nodeValue;
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
		
			$script['create'] = array();
			$script['entities'] = array();
			$script['alter'] = array();
			$script = array();
			$entities = $this->_config->getElementsByTagName("entity");
			foreach($entities as $entity)
			{
				
				$tn = $entity->getAttribute("name");
				$script['entities'][] = $tn;
				
				//$script = $script . "------------TABLE ".$tn."------------------";
				$query = "CREATE TABLE IF NOT EXISTS ".$tn."s (id INT(11) NOT NULL AUTO_INCREMENT,".
				"PRIMARY KEY (id)) ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ";
				$script['create'][] = $query;
				$script['create'][] = "CREATE TABLE IF NOT EXISTS ".$tn."s_wisapps (id INT(11) NOT NULL AUTO_INCREMENT,  ".$tn."_id INT(11) NOT NULL, wisapp_id INT(11) NOT NULL,".
				"PRIMARY KEY (id)) ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ";
				$fields = $entity->getElementsByTagName("field");

				$models = App::Objects('Model');
				
				if(in_array($tn, $models))
				{
					
					$this->loadModel($tn);
					$fd = $this->$tn->Schema();
					$model_fields = array_keys($fd);
					$m_d = array();
					foreach($model_fields as $md)
						$m_d[] = strtolower($md);
					
					foreach($fields as $field)
					{
							
						/* CHeck if the entity already has that field*/
						if(!in_array(strtolower($field->nodeValue), $m_d)){
						if($field->getAttribute("required") == true){
							$query3 = " ALTER TABLE ".$tn."s ADD ".$field->nodeValue."_req ".$field->getAttribute("datatype")." NULL";
						}
						else{
							$query3 = " ALTER TABLE ".$tn."s ADD ".$field->nodeValue." ".$field->getAttribute("datatype")." NULL";
							$script['alter'][] = $query3;
							}
						}
					}
				}
				else{
				
				$dir = getcwd();	
				$model_dir = str_replace("webroot","Model\\",$dir);
				$model = new File($model_dir.$tn.'.php', true);
				
				if($model->size() == 0){
					$model->append('  
					<?php
					class '.$tn.' extends AppModel{
					
						public $hasAndBelongsToMany = array(
							\'Wisapp\' =>
								array(
								\'className\' => \'Wisapp\',
								\'joinTable\' => \''.strtolower($tn).'s_wisapps\',
							\'foreignKey\' => \''.strtolower($tn).'_id\',
							\'associationForeignKey\' => \'wisapp_id\',
							\'unique\' => true,
							\'conditions\' => \'\',
							\'fields\' => \'\',
							\'order\' => \'\',
							\'limit\' => \'\',
							\'offset\' => \'\',
							\'finderQuery\' => \'\',
							\'with\' => \'\'
							)
						);
						}'
						);
				}
				
				
				
				foreach($fields as $field)
				{
					
						
						if($field->getAttribute("required") == true){
							$query3 = " ALTER TABLE ".$tn."s ADD ".$field->nodeValue."_req ".$field->getAttribute("datatype")." NULL";
						}
						else{
							$query3 = " ALTER TABLE ".$tn."s ADD ".$field->nodeValue." ".$field->getAttribute("datatype")." NULL";
						$script['alter'][] = $query3;
						}
					
					
					
				}
			}
			}
			
			return $script;
		}
	} 
	
	?>
