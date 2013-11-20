<?php
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

require_once("Wiseapp.php");
include "Waki.php";
include "allWisapps.php";

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
			$search = explode(' ', $this->request->data['Example']['search']);
			$wisapps = $this->_executeWisapp($search);
			
			if(count($wisapps))
			var_dump($wisapps[0]['points']);
			
			
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
	
	function _executeWisapp($search){
		
		$wisapps = new allWisapps();
		$candidates = array();
		$i=0;
		foreach($wisapps->wisapps as $wisapp){
			
			if($p = $wisapp->canExecute($search)){
				
				$candidates[$i] = array();
				$candidates[$i]['Wisapp'] = $wisapp;
				$candidates[$i]['points'] = $p;
				$i++;
				}
			
			/* Check if searchstring matches any data in defined entities and return associated Wisapps*/
				
		}
		
		if(count($candidates)){
			
			foreach($candidates as $candidate){
				
				
			
				/* Check if this wisapp is among the users Favorite Wisapps*/
				
				/* Check the history of usage stats of this wisapp by the user*/
				
				/* Get Likes of the Wisapp*/
				
				/* Check wisapp review from BEWAKA*/
				
				/* Check Wisapp tags and user tag matching*/
				
				
			
			}
		
		}
		
		return $candidates;
		
	}
}
	

	
	
	
	
	
	
