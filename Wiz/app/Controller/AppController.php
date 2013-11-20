<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

	public $components = array('Cookie','Session');
	
	    //set an alias for the newly created helper: Html<->MyHtml
	
	    public $helpers = array('Html' => array('className' => 'MyHtml'));
	
 
	
	    public function beforeFilter() {
	
	          //$this->_setLanguage();
	
	        }
	
 
	
	    private function _setLanguage() {
	
	    //if the cookie was previously set, and Config.language has not been set
	
	    //write the Config.language with the value from the Cookie
	
	        if ($this->Cookie->read('lang') && !$this->Session->check('Config.language')) {
	
	            $this->Session->write('Config.language', $this->Cookie->read('lang'));
	
	        }
	
	        //if the user clicked the language URL
	
	        else if (   isset($this->params['language']) &&
	
	        ($this->params['language'] !=  $this->Session->read('Config.language'))
	
	                ) {
	
	            //then update the value in Session and the one in Cookie
	
	            $this->Session->write('Config.language', $this->params['language']);
	
	            $this->Cookie->write('lang', $this->params['language'], false, '20 days');
	
	        }
	
	    }
	
 
	
	    //override redirect
	
	    public function redirect( $url, $status = NULL, $exit = true ) {
	
	        if (is_array($url) && !isset($url['language']) && $this->Session->check('Config.language')) {
	
	            $url['language'] = $this->Session->read('Config.language');
	
	        }
	
	        parent::redirect($url,$status,$exit);
	
	    }
		
		/**
 * uploads files to the server
 * @params:
 *		$folder 	= the folder to upload the files e.g. 'img/files'
 *		$formdata 	= the array containing the form files
 *		$itemId 	= id of the item (optional) will create a new sub folder
 * @return:
 *		will return an array with the success of each file upload
 */
function uploadFiles($folder, $formdata, $itemId = null) {
	// setup dir names absolute and relative
	$folder_url = WWW_ROOT.$folder;
	$rel_url = $folder;
	
	// create the folder if it does not exist
	if(!is_dir($folder_url)) {
		mkdir($folder_url);
	}
		
	// if itemId is set create an item folder
	if($itemId) {
		// set new absolute folder
		$folder_url = WWW_ROOT.$folder.'/'.$itemId; 
		// set new relative folder
		$rel_url = $folder.'/'.$itemId;
		// create directory
		if(!is_dir($folder_url)) {
			mkdir($folder_url);
		}
	}
	
	// list of permitted file types, this is only images but documents can be added
	$permitted = array('application/x-rar-compressed', 'application/octet-stream','application/zip', 'application/octet-stream', 'application/x-zip-compressed');
	
	// loop through and deal with the files
	foreach($formdata as $file) {
		// replace spaces with underscores
		
		
		$filename = str_replace(' ', '_', $file['file']['name']);
		// assume filetype is false
		$typeOK = false;
		// check filetype is ok
		foreach($permitted as $type) {
			if($type == $file['file']['type']) {
				$typeOK = true;
				break;
			}
		}
		
		// if file type ok upload the file
		if($typeOK) {
			// switch based on error code
			switch($file['file']['error']) {
				case 0:
					// check filename already exists
					if(!file_exists($folder_url.'/'.$filename)) {
						// create full filename
						$full_url = $folder_url.'/'.$filename;
						$url = $rel_url.'/'.$filename;
						// upload the file
						$success = move_uploaded_file($file['file']['tmp_name'], $url);
					} else {
						// create unique filename and upload file
						ini_set('date.timezone', 'Europe/London');
						$now = date('Y-m-d-His');
						$full_url = $folder_url.'/'.$now.$filename;
						$url = $rel_url.'/'.$now.$filename;
						$success = move_uploaded_file($file['file']['tmp_name'], $url);
					}
					// if upload was successful
					if($success) {
						// save the url of the file
						$result['urls'][] = $url;
						$result['success'] = "File Sucessfuly Uploaded... Parsing File";
						$result['name'] = $file['file']['name'];
					} else {
						$result['errors'][] = "Error uploaded $filename. Please try again.";
					}
					break;
				case 3:
					// an error occured
					$result['errors'][] = "Error uploading $filename. Please try again.";
					break;
				default:
					// an error occured
					$result['errors'][] = "System error uploading $filename. Contact webmaster.";
					break;
			}
		} elseif($file['file']['error'] == 4) {
			// no file was selected for upload
			$result['nofiles'][] = "No file Selected";
		} else {
			// unacceptable file type
			$result['errors'][] = "$filename cannot be uploaded. Acceptable file types: zip, rar.";
		}
	}
return $result;
}
}
