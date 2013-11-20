<?php

	/* The class of the user/Waki */
	
	class Waki extends AppController {
	
	public $uses = array('Wis','Wisapp');
	var $attributes = array();
	var $type;
	var $id;
	var $email;
	
	
	/*
	/* This Function Instantiates the class and initialises its attributes
	/* @var id: The id of the Waki
	*/
	
	function __construct($id) {
			
			$this->id = $id;
			$this->attributes = $this->Wis->findById($id);
			$this->email = $this->attributes['Wis']['email'];
			$this->type = $this->attributes['Wis']['type'];
			
		}
	
	/*
	/* This Function gets all the attributes of the Waki
	/* @return array
	*/	
	function getAttributes(){
	
		$attributes = $this->Wis->findByID($this->id);
		return $attribues['Wis'];
	
	}
	
	/*
	/* This Function gets the favorite Wisapps of the Waki
	/* @return array of Wisapps
	*/
	function getWisApps(){
	
		$wisapps = $this->Wisapp->find('id', array("conditions"=>array("wis_id"=>$this_id, "status"=>1)));
		$favorites = array();
		
		foreach($wisapps as $wisapp){
			$favorites[] = new Wisapp($wisapp['Wisapp']['id']);	
		}
		
		return $favorites;
	
	}
	
	/*
	/* This Function gets the History of things the waki searches for 
	/* @return array of History
	*/
	function getHistory($number){
	
		$history = $this->attributes['History'];
		return $history;
	}
	
	
	/*
	/* This Function gets Experiences of the User
	/* @return array of History
	*/
	function getExperiences(){
	
		$experiences = $this->attributes['Experience'];
		return $experiences;
	}
	
	/*
	/* This Function gets Educations of the User
	/* @return array of History
	*/
	function getEducations(){
	
		$educations = $this->attributes['Education'];
		return $educations;
	}
	
	
	
	}
	?>
