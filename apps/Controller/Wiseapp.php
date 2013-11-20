<?php

class Wiseapp extends AppController{
	
		public $uses = array('Wis','Wisapp');
		var $result_hits;
		var $sugesstion_hits;
		var $like_hits;
		var $name;
		var $logo;
		var $fav_hits;
		var $status;
		var $tags;
		
		/* THis funtions initialises a WIsapp given the id of that WIsapp
		/*
		/*
		*/
		
		function __construct($id) {
			
			$this->id = $id;
			$wisapp = $this->Wisapp->findById($id);
			$this->result_hits = $wisapp['Wisapp']['result_hits'];
			$this->suggestion_hits = $wisapp['Wisapp']['suggestion_hits'];
			$this->like_hits = $wisapp['Wisapp']['likes'];
			$this->name = $wisapp['Wisapp']['name'];
			$this->logo = $wisapp['Wisapp']['logo'];
			$this->fav_hits = $wisapp['Wisapp']['favorites'];
			$this->group_id = $wisapp['Wisapp']['group_id'];
			$this->tags = $wisapp['Wisapp']['tags'];
			$this->status = $wisapp['Wisapp']['status'];
			
		}
		
		/* THis funtions disables a WIsapp
		/*
		/*
		*/
		
		function disable(){
			
			$data = array();
			$data['Wisapp'] = array();
			
			$data['Wisapp']['id'] = $this->id;
			
			if($this->status == 1){
				$data['Wisapp']['status'] = 0;
				$this->Wisapp->save($data);
				return true;
			}
			else
				return false;
			
			
		}
		
		/* THis funtions enables a WIsapp
		/*
		/*
		*/
		
		function enable(){
			
			$data = array();
			$data['Wisapp'] = array();
			
			$data['Wisapp']['id'] = $this->id;
			
			if($this->status == 0){
				$data['Wisapp']['status'] = 1;
				$this->Wisapp->save($data);
				return true;
			}
			else
				return false;	
		}
		
		/* THis funtion determines if a Wisapp can execute a search string provided by the user
		/*@var search: An array of the search string provided by the user
		/*@return: An integer or false if the Wisapp cannot execute the string
		*/
		
		function canExecute($search){
			
			$return = 0;
			
			
			
			$tags = explode(',', $this->tags);
			$tmp = array();
			foreach($tags as $tag)
				$tmp[] = trim($tag);
			$tags = $tmp;	
			
			if(count($search))
			{
				foreach($search as $string){
					
					if(in_array($string, $tags))
						++$return;
					if (strpos(strtolower($this->name), strtolower($string)) )
						$return = $return + 5;
				}
				
			}
			else
				return false;
				
				

				
			if($return > 0)
				return $return;
			else
				return false;
				
			
		
		}
		
		function delete(){
		
			$this->Wisapp->delete($this->id);
		
		}
		
		function update($data){
		
			$this->data['Wisapp']['id'] = $this->id;
			$this->Wisapp->save($data);
		}
	
	}
	
	?>
