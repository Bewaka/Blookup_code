<?php
	App::uses('AuthComponent', 'Controller/Component');
	App::uses('CakeEmail', 'Network/Email');   
	           
	class Waki extends AppModel{
		
		public $hasAndBelongsToMany = array(
		'Tag' => array(
			'className' => 'Tag',
			'joinTable' => 'tag_waki',
			)
		);
		
		
		
		public $hasMany = 'Requests';
	
		public $validate = array(
				"type~" => array(
					'rule' => 'notEmpty',
					'message'=> 'Please select group'),
					"type" => array(
						'rule' => array('comparison', '!=', 0),
						'message'=> 'Please select group'),
				'username'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter username',
						'last'=>true),
					'mustUnique'=>array(
						'rule' =>'isUnique',
						'message' =>'This username already taken',
					'last'=>true),
					'mustBeLonger'=>array(
						'rule' => array('minLength', 8),
						'message'=> 'Username must be greater than 3 characters',
						'last'=>true),
					),
				'first_name'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter first name')
					),
				'last_name'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'on' => 'create',
						'message'=> 'Please enter last name')
					),
				'email'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter email',
						'last'=>true),
					'mustBeEmail'=> array(
						'rule' => array('email'),
						'message' => 'Please enter valid email',
						'last'=>true),
					'mustUnique'=>array(
						'rule' =>'isUnique',
						'message' =>'This email is already registered',
						)
					),
				'oldpassword'=>array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter old password',
						'last'=>true),
					'mustMatch'=>array(
						'rule' => array('verifyOldPass'),
						'message' => 'Please enter correct old password'),
					),
				'password'=>array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter password',
						'on' => 'create',
						'last'=>true),
					'mustBeLonger'=>array(
						'rule' => array('minLength', 8),
						'message'=> 'Password must be greater than 5 characters',
						'on' => 'create',
						'last'=>true),
					'mustMatch'=>array(
						'rule' => array('verifies'),
						'message' => 'Both passwords must match'),
						//'on' => 'create'
					),
					'cpassword'=>array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter password',
						'on' => 'create',
						'last'=>true),
					'mustBeLonger'=>array(
						'rule' => array('minLength', 8),
						'message'=> 'Password must be greater than 5 characters',
						'on' => 'create',
						'last'=>true),
					),
				'captcha'=>array(
					'mustMatch'=>array(
						'rule' => array('recaptchaValidate'),
						'message' => ''),
					)
			);
			
			public function verifies() {
				return ($this->data['Waki']['password']===$this->data['Waki']['cpassword']);
			}
			
			public function verifyOldPass() {
				$userId = $this->userAuth->getUserId();
				$user = $this->findById($userId);
				$oldpass=$this->userAuth->makePassword($this->data['Waki']['oldpassword'], $user['Waki']['salt']);
				return ($user['Waki']['password']===$oldpass);
			}
			
			
			
			public function forgotPassword($user) {
			
				$userId=$user['Waki']['id'];
				$email = new CakeEmail();
				$fromConfig = EMAIL_FROM_ADDRESS;
				$fromNameConfig = EMAIL_FROM_NAME;
				$email->from(array( $fromConfig => $fromNameConfig));
				$email->sender(array( $fromConfig => $fromNameConfig));
				$email->to($user['Waki']['email']);
				$email->subject(EMAIL_FROM_NAME.': Request to Reset Your Password');
				$activate_key = $user['Waki']['password'];
				$link = Router::url("/activatePassword?ident=$userId&activate=$activate_key",true);
				$body= "Welcome ".$user['Waki']['first_name'].", let's help you get signed in

			You have requested to have your password reset on ".EMAIL_FROM_NAME.". Please click the link below to reset your password now :

			".$link."


			If above link does not work please copy and paste the URL link (above) into your browser address bar to get to the Page to reset password

			Choose a password you can remember and please keep it secure.

			Thanks,\n".

			EMAIL_FROM_NAME;
			try{
				$result = $email->send($body);
			} catch (Exception $ex){
				// we could not send the email, ignore it
				$result="Could not send forgot password email to userid-".$userId;
			}
			$this->log($result, LOG_DEBUG);
		}
	
	public function beforeSave($options = array()) {
	
    if (isset($this->data[$this->alias]['password'])) {
        $this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
    }
    return true;
	}
	
	/**
	 * Used to send email verification mail to user
	 *
	 * @access public
	 * @param array $user user detail array
	 * @return void
	 */
	public function sendVerificationMail($user) {
		$userId=$user['Waki']['id'];
		$email = new CakeEmail();
		$fromConfig = EMAIL_FROM_ADDRESS;
		$fromNameConfig = EMAIL_FROM_NAME;
		$email->from(array( $fromConfig => $fromNameConfig));
		$email->sender(array( $fromConfig => $fromNameConfig));
		$email->to($user['Waki']['email']);
		$email->subject('Email Verification Mail');
		$activate_key = $user['Waki']['password'];
		$link = Router::url("/wakiVerification?ident=$userId&activate=$activate_key",true);
		$body="Hi ".$user['Waki']['first_name'].", Click the link below to complete your registration \n\n ".$link;
		try{
			$result = $email->send($body);
		} catch (Exception $ex){
			// we could not send the email, ignore it
			$result="Could not send verification email to userid-".$userId;
		}
		$this->log($result, LOG_DEBUG);
	}
	
	function LoginValidate() {
		$validate1 = array(
				'email'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter email or username')
					),
				'password'=>array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter password')
					)
			);
		$this->validate=$validate1;
		return $this->validates();
	}
	
}