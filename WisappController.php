<?php
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
class WisController extends AppController{

	public $uses = array('Wis', 'Example', 'Wisapp');
	public $helpers = array('Form','Html');
	
	public $components = array(
        'Session',
        'Auth' => array(
			'authenticate' => array( 
				'Form' => array('userModel' => 'Wis', 'fields' => array('username' => 'email'))),
            'loginRedirect' => array('controller'=>'wis', 'action' => 'profile'),
            'logoutRedirect' => array('controller' => 'wis', 'action' => 'login'),
			'loginAction' => array('controller'=>'wis', 'action'=>'login')
			)
		);
		
		public function beforeFilter() {
		
        $this->Auth->allow('index', 'view', 'login', 'add_wis', 'login', 'wisVerification', 'checkMail', 'forgotPassword', 'activatePassword');

		parent::beforeFilter();
		
		}
		
		public function index(){
		
		$models = App::Objects('Model');
		
		
		$user = $this->Auth->user();
		$this->set('user', $user);
		
		if ($this->request->is('post')) {
            
			if(is_numeric($this->request->data['Example']['search']))
				$wisapp_name = 'Wisapp01';
			else
				$wisapp_name = 'Wisapp02';
			
			if(strpos($this->request->data['Example']['search'], ':')){
					$data = explode(':', $this->request->data['Example']['search']);
					$wisapp_name = end($data);
					$id = $data[0];
			}		
			
			$this->set('wisapp_name', $wisapp_name);
			
			$this->WisApp = $this->Components->load($wisapp_name);
			if(!isset($id))
			$id = $this->request->data['Example']['search'];
			$results = $this->WisApp->display($id);
			
			$this->set('result',$results);			
			
			
        }
		
	}
		public function login() {
		
			$user = $this->Auth->user();
			$this->set('user', $user);
		
			if(isset($user))
				$this->redirect('/profile');
				
			if ($this->request->is('post')) {
				
				$Wis = $this->Wis->findByEmail($this->request->data['Wis']['email']);
				
				$verified = $Wis['Wis']['verified'];
				
				
				if($verified){
					if ($this->Auth->login()) {
				
						return $this->redirect($this->Auth->redirect());
					}
					else
						$this->Session->setFlash(__('Invalid username or password, try again'));
					}
				else
					$this->Session->setFlash(__('Account not verified. Please check your email and verify this account.'));	
				
			}
		}
		
		
		
			public function logout() {
			
				return $this->redirect($this->Auth->logout());
		
			}
		
		public function add_wis(){
		
		$user = $this->Auth->user();
			$this->set('user', $user);
			
		if ($this->request->is('post')) {
            $this->Wis->create();
			$this->request->data['Wis']['username'] = $this->request->data['Wis']['email'];
            if ($this->Wis->save($this->request->data)) {
                $this->Session->setFlash(__('User Registered. Please Confirm email address.'));
				
				$userId=$this->Wis->getLastInsertID();
				$user = $this->Wis->findById($userId);
				
				$this->Wis->sendVerificationMail($user);
				
				
                return $this->redirect(array('action' => 'checkMail'));
            }
            $this->Session->setFlash(__('The user could not be saved. Please, try again.'));
        }
		
		}
		
		/**
	 * Used to verify user's email address
	 *
	 * @access public
	 * @return void
	 */
	public function wisVerification() {
	
		if (isset($_GET['ident']) && isset($_GET['activate'])) {
			$WisId= $_GET['ident'];
			$activateKey= $_GET['activate'];
			$Wis = $this->Wis->read(null, $WisId);
			if (!empty($Wis)) {
				if (!$Wis['Wis']['verified']) {
					$theKey = $Wis['Wis']['password'];
					if ($activateKey==$theKey) {
						$Wis['Wis']['verified']=1;
						$this->Wis->save($Wis,false, array("verified"));
						$this->Session->setFlash(__('Thank you, your account is activated now'));
						$this->redirect('/login');
						
					}
				} else {
					$this->Session->setFlash(__('Thank you, your account is already activated'));
					$this->redirect('/login');
				}
			} else {
				$this->Session->setFlash(__('Sorry something went wrong, please click on the link again'));
				
			}
		} else {
			$this->Session->setFlash(__('Sorry something went wrong, please click on the link again'));
			
		}
		$this->redirect('/login');
	}
	
	
	public function edit($id = null) {
		
	$user = $this->Auth->user();
	$this->set('user', $user);
        $this->Wis->id = $id;
        if (!$this->Wis->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
			var_dump($this->request->data);
            if ($this->Wis->save($this->request->data)) {
                $this->Session->setFlash(__('User information updated.'));
                return $this->redirect(array('action' => 'profile'));
            }
            $this->Session->setFlash(__('The user could not be saved. Please, try again.'));
        } else {
			
            $this->request->data = $this->Wis->read(null, $id);
            unset($this->request->data['Wis']['password']);
        }
    }
	
	function profile(){
	
		$user = $this->Auth->user();
		$wis = $this->Wis->find();
		$this->set('user', $user);
	}
	
		/**
	 * Used to send forgot password email to user
	 *
	 * @access public
	 * @return void
	 */
	public function forgotPassword() {
	
		$user = $this->Auth->user();
		$this->set('user', $user);
		if ($this->request -> isPost()) {
			$this->Wis->set($this->data);
				$email  = $this->data['Wis']['email'];
				$user = $this->Wis->findByUsername($email);
				if (empty($user)) {
					$user = $this->Wis->findByEmail($email);
					if (empty($user)) {
						$this->Session->setFlash(__('Incorrect Email/Username'));
						return;
					}
				}
				// check for inactive account
				if ($user['Wis']['id'] != 1 and $user['Wis']['verified']==0) {
					$this->Session->setFlash(__('Your registration has not been confirmed yet please verify your email before reset password'));
					return;
				}
				$this->Wis->forgotPassword($user);
				$this->Session->setFlash(__('Please check your mail for reset your password'));
				$this->redirect('/login');
			
		}
	}
	
	
	public function checkMail(){
		
		
		
	}
	
	/**
	 *  Used to reset password when user comes on the by clicking the password reset link from their email.
	 *
	 * @access public
	 * @return void
	 */
	public function activatePassword() {
		if ($this->request -> isPost()) {
			if (!empty($this->data['Wis']['ident']) && !empty($this->data['Wis']['activate'])) {
				echo $this->set('ident',$this->data['Wis']['ident']);
				echo $this->set('activate',$this->data['Wis']['activate']);
				$this->Wis->set($this->data);
					echo $WisId= $this->data['Wis']['ident'].'<br>';
					echo $activateKey= $this->data['Wis']['activate'];
					$Wis = $this->Wis->read(null, $WisId);
					if (!empty($Wis)) {
						$password = $Wis['Wis']['password'];
						echo $thekey = $activateKey;
						if ($thekey==$activateKey) {
							$Wis['Wis']['password']=$this->data['Wis']['password'];
							$this->Wis->save($Wis,false, array("password"));
							$this->Session->setFlash(__('Your password has been reset successfully'));
							$this->redirect('/login');
						} else {
							$this->Session->setFlash(__('Something went wrong, please send password reset link again'));
						}
					} else {
						$this->Session->setFlash(__('Something went wrong, please click again on the link in email'));
					}
				
			} else {
				$this->Session->setFlash(__('Something went wrong, please click again on the link in email'));
			}
		} else {
			if (isset($_GET['ident']) && isset($_GET['activate'])) {
				$this->set('ident',$_GET['ident']);
				$this->set('activate',$_GET['activate']);
			}
		}
	}
	
	
	function balance(){
		
		$user = $this->Auth->user();
		$this->set('user', $user);
		
	}
	
	function requests(){
		$user = $this->Auth->user();
		
		if($user['type'] == 4){
			
			$requests_raw = $this->Wis->find('all');
			
			$requests = array();
			$i = 0; 
			
			foreach ($requests_raw as $raw){
				
				if(count($raw['Requests'])){
					
					$requests[$i] = array();
					
					foreach($raw['Requests'] as $request){
						$requests[$i][0] = $raw['Wis']['first_name'].' '.$raw['Wis']['last_name'];
						$requests[$i][1] = $request['type'];
						$requests[$i][2] = $request['amount'];
						$requests[$i][3] = $request['request_date'];
						$requests[$i][4] = '<a href="transfer/'.$request['id'].'">Transfer</a>';
						
						$i++;
						
					}
						
					
				}
			}
			
			$this->set('requests', $requests);
			
			
		}
		else
		{
			$this->Session->setFlash(__('You are not authorized to view this page'));
			$this->redirect('/profile');
		}
		
		
	}	
	
	public function requestTransfer(){
		
		$user = $this->Auth->user();
		$this->set('user', $user);
		
		if ($this->request->is('post')) {
            $this->Wis->Requests->create();
			$d = getdate();
			
			echo $this->request->data['Requests']['request_date'] = $d['year'].'-'.$d['mon'].'-'.$d['mday'].' '.$d['hours'].':'.$d['minutes'].':'.$d['seconds'];
            if ($this->Wis->Requests->save($this->request->data)) {
                $this->Session->setFlash(__('Request Sent'));
				
				$requestId=$this->Wis->Requests->getLastInsertID();
				$this->Wis->Requests->sendRequestMail($user, $requestId);
				
				
                return $this->redirect(array('action' => 'profile'));
            }
            $this->Session->setFlash(__('The user could not be saved. Please, try again.'));
        }
		
	}
	
	public function userRequest($userId, $requestId){
		
		$user = $this->Auth->user();
		
		if($user['type'] == 4){
		$request = $this->Wis->Requests->find('all', array("conditions"=>array("Requests.wis_id"=>$userId, "Requests.id"=>$requestId)));
		$id = $request[0]['Requests']['wis_id'];
		$wis = $this->Wis->find('all', array("conditions"=>array("Wis.id"=>$id)));
		$this->set('request', $request);
		$this->set('wis', $wis);
		}
		else{
			$this->Session->setFlash(__('You are not authorized to view this page'));
			$this->redirect('/profile');
		}
		
	}
	
	public function apps($id){
	
		
		$user = $this->Auth->user();
		$this->set('user', $user);
		
		$users = $this->Wis->find('all', array('conditions'=>array('Wis.id'=>$id)));
		
		$this->set('waki', $users);
	
	}
	
	public function app($id){
	
		$app = $this->Wisapp->find('all', array('conditions'=>array('id'=>$id)));
		$this->set('app', $app);
	}
	
	public function create_app(){
		
		if ($this->request->is('post')) {
            $this->Wisapp->create();
			$this->request->data['Wisapp']['logo'] = $this->request->data['Wisapp']['logo']['name'];
			//$this->request->data['Wisapp']['wis_id'] = $id; //USES AUTO INCREMENT HERE
			$config = new WisappConfig('../upload/wisapp/config.xml');
			//echo var_dump($config->getWisapp());
            if ($this->Wisapp->save($config->getWisapp())) {
				
				$sql = $config->generateSQLScript();
				$this->Wisapp->query($sql);
              //  $this->Session->setFlash(__('WisApp created.'));
				
				//$userId=$this->Wis->getLastInsertID();
				//$user = $this->Wis->findById($userId);
				
				//$this->Wis->sendVerificationMail($user);
				
				//$path = new File('/wisapp/config.xml');
				
				
				
                return $this->redirect(array('controller'=>'wis','action' => 'apps', 10));
            }
            $this->Session->setFlash(__('The user could not be saved. Please, try again.'));
        }
	}
	
	function upload_files(){
	
		
	}
	
	public function add_app(){
		$user = $this->Auth->user();
		$this->set('user', $user);
		if ($this->request->is('post')) {
		
			/* Use AppController function to upload file*/
			$res = $this->uploadFIles('files/uploads', $this->request->data, null);
			
			/*Check if the file was uploaded sucessfully*/
			if(isset($res['success'])){
				$this->Session->setFlash($res['success']);
				$zip = new ZipArchive;
				
				$zip_files = $zip->open($res['urls'][0]);
				if ($zip_files === TRUE) {
					/* REMOVE .ZIP OR .RAR FROM NAME OF THE COMPRESSED FILE*/
					$res['name'] = str_replace(".zip","",$res['name']);
					$res['name'] = str_replace(".rar","",$res['name']);
					
					$zip->extractTo('wisapps/'.$res['name'].'/files/');
					$zip->close();
					
					/*Check if config, view and model files are present*/
					$files = scandir('wisapps/'.$res['name'].'/files/'.$res['name']);
					$check = 0;
					if(in_array($res['name'].'.model', $files)){
						$check++;
						
					}
					else{
						$this->Session->setFlash('Model File not Found');
					}
					
					if(in_array($res['name'].'.view', $files)){
						$check++;
					}
					else{
						$this->Session->setFlash('View File not Found');
					}
					
					if(in_array('config.xml', $files)){
						$check++;
					}
					else{
						$this->Session->setFlash('Configuration File not Found');
					}
					
					
					
					if($check == 3){
						
						
						$wisapp = new WisappConfig('wisapps/'.$res['name'].'/files/'.$res['name'].'/config.xml');

						$wis = $wisapp->getWisapp();
						
						$entities = $wisapp->generateSQLScript();
						
						/* Create Necessary tables for entities that don't exist and are specified in config file*/
						foreach($entities['create'] as $create)
							$this->Wis->query($create);
							
						/*Add atributes to entities created or to existant entities*/	
						if(isset($entities['alter']))
						foreach($entities['alter'] as $alter)
							$this->Wis->query($alter);
						$arr = array();
						foreach($wis as $key => $value) {
							 $arr['Wisapp'][$key] = $value;

						}
						$arr['Wisapp']['wis_id'] = $user['id'];
						$wiss = $this->Wisapp->findByName($arr['Wisapp']['name']);
						
						if(isset($wiss))
							$arr['Wisapp']['id'] = $wiss['Wisapp']['id'];
						
						$this->Wisapp->save($arr['Wisapp']);

						$dir = getcwd();
						
						$component_dir = str_replace("webroot","Controller\\Component\\",$dir);
						$view_dir = str_replace("webroot","View\\Elements\\",$dir);
						
						$model = new File($component_dir.$res['name'].'Component.php', true);
						$view = new File($view_dir.$res['name'].'.ctp', true);
						$view->write("");
						$model->write("");
						$append_model_function = "";
						$append = ' <?php app::uses(\'Component\', \'Controller\'); class '.$res['name'].'Component extends Component { ';
						foreach($entities['entities'] as $entity)
							$append_model_function .= ' $'.$entity.' = ClassRegistry::init(\''.$entity.'\'); ';
						$model_contents = file_get_contents('wisapps/'.$res['name'].'/files/'.$res['name'].'/'.$res['name'].'.model');
						$model_contents = str_replace('display($id) {', 'display($id) { '.$append_model_function.'', $model_contents);
						
						$view_contents = file_get_contents('wisapps/'.$res['name'].'/files/'.$res['name'].'/'.$res['name'].'.view');
						$model->append($append);
						$model->append($model_contents.'}');
						$view->append($view_contents);
						
					}
				} else {
					
				}
			}
				
			if(isset($res['errors'])){
			
				foreach($res['errors'] as $error){
					$this->Session->setFlash($error);
				}
			}
		
		}
	}
}

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
					case "name":
						$wisapp->name = $item->nodeValue;
						break;
					case "description":
						$wisapp->description = $item->nodeValue;
						break;
					case "tags":
						$str="";
						$tags = $item->getElementsByTagName("tags");
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
				
				if($model->size() == 0)
					$model->append('  

					class '.$tn.' extends AppModel{}');
				
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
	
	
	
	/* The class of the user/Waki */
	
	class Waki extends AppController{
	public $uses = array('Wis','Wisapp');
	var attributes = array();
	var type;
	var id;
	var email;
	
	
	/*
	/* This Function Instantiates the class and initialises its attributes
	/* @var id: The id of the Waki
	*/
	
	function __construct($id) {
			
			$this->id = $id;
			$this->attributes = $this->Wis->findById($id);
			$this->email = $this->attributes[0]['Wis']['email'];
			$this->type = $this->attributes[0]['Wis']['type'];
			
		}
	
	/*
	/* This Function gets all the attributes of the Waki
	/* @return array
	*/	
	function getAttributes(){
	
		$attributes = $this->Wis->findByID($this->id);
		return $attribues[0]['Wis'];
	
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
	function getHistory(){
	
		$history = $this->History->find('id', array("conditions"=>array("wis_id"=>$this_id)));
		return $history;
	}
	
	
	/*
	/* This Function gets Experiences of the User
	/* @return array of History
	*/
	function getExperiences(){
	
		$experiences = $this->Experience->find('id', array("conditions"=>array("wis_id"=>$this_id)));
		return $experiences;
	}
	
	/*
	/* This Function gets Educations of the User
	/* @return array of History
	*/
	function getEducations(){
	
		$educations = $this->Education->find('id', array("conditions"=>array("wis_id"=>$this_id)));
		return $educations;
	}
	
	
	
	}
	
	class Wisapp extends AppController{
	
		public $uses = array('Wis','Wisapp');
		var result_hits;
		var sugesstion_hits;
		var like_hits;
		var name;
		var logo;
		var fav_hits;
		var group_id;
		var status;
		var tags;
		
		/* THis funtions initialises a WIsapp given the id of that WIsapp
		/*
		/*
		*/
		
		function __construct($id) {
			
			$this->id = $id;
			$wisapp = $this->Wisapp->findById($id);
			$this->result_hits = $this->wisapp[0]['Wisapp']['result_hits'];
			$this->suggestion_hits = $this->wisapp[0]['Wisapp'][''];
			$this->like_hits = $this->wisapp[0]['Wisapp']['likes'];
			$this->name = $this->wisapp[0]['Wisapp']['name'];
			$this->logo = $this->wisapp[0]['Wisapp']['logo'];
			$this->fav_hits = $this->wisapp[0]['Wisapp']['favorites'];
			$this->group_id = $this->wisapp[0]['Wisapp']['group_id'];
			$this->tags = $this->wisapp[0]['Wisapp']['tags'];
			$this->status = $this->wisapp[0]['Wisapp']['status'];
			
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
			
			$tags = $this->tags;
			
			$tags = explode(',' $tags);
			
			if(count($search))
			{
				foreach($search as $string){
					if(in_array($string, $tags))
						$return++;
					if (strpos($string, $this->name))
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
		
			
		}
	
	}
	
	
