<?php
/**
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
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

//$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php //echo $cakeDescription ?>:
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		//echo $this->Html->meta('icon');

		echo $this->Html->css('cake.generic');
		echo $this->Html->css('style'); 
		echo $this->Html->css('jquery-ui'); 
		echo $this->Html->script('jquery');
		echo $this->Html->script('jquery-ui');
		echo $this->Html->script('script');
		echo $this->Html->script('lightbox');
		

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
</head>

<body>
	
	<div id="tabs" style="display:none;">
     <ul>
	<li><a href="#sign_up">Login</a></li>
	<li><a href="#add_waki">Register</a></li>
</ul>
	<div id="sign_up">
                <h3 id="see_id" class="spriteds" >Please Login</h3>
                <span>Please sign in using the form below</span>
                <div id="sign_up_form">
                	<?php echo $this->Form->create('Wis', array("action"=>"login")); 
						echo $this->Form->input('email');
						echo $this->Form->input('password');
						echo '</br>';
						echo $this->Form->end('Login'); 
					
					?>
                    
                </div>
                
                <a href="#">click here</a> to sign up!
                <a id="close_x" class="close spriteds" href="#">close</a>
     </div>
     
     
     
    
    <div id="add_waki">
                <h3 id="see_id" class="spriteds" >Add New Waki</h3>
                <span>Create New Account</span>
                <div id="sign_up_form">
                	<?php

						echo $this->Form->create('Wis', array("action"=>"add_wis"));
	
						echo $this->Form->input('first_name');
						echo $this->Form->input('last_name');
						echo $this->Form->input('email');
						echo $this->Form->input('password');
						echo $this->Form->input('cpassword',array("type"=>"password", "label"=>"Confirm Password"));
						echo $this->Form->input('username',array("type"=>"hidden"));
						echo '</br>';
						echo $this->Form->end('Add User');

					?>
                    
                </div>
                
                <a href="#">click here</a> to sign up!
                <a id="close_x" class="close spriteds" href="#">close</a>
     </div>
     </div>
	<div id="headers">
	
	<div id="logo" style="float:left;">
    <?php echo $this->Html->image('logo.png', array('alt' => 'Bewaka Logo', "class"=>"logo")); ?>
    </div>
	<div id="search-box" style="float:left;">
		<?php echo $this->Form->create('Wis', array("action"=>"index"));		?>
			<input id="namanyay-search-box" name="data[Example][search]" size="40" type="text" placeholder=" Search"/>
			<input id="namanyay-search-btn" value="Search" type="submit"/>
		<?php echo $this->Form->end();?>
	</div>
	<div id="login" style="float:left;">
	
		<?php if(!isset($user)) :?>
		<a href="#" id="try-1" >Login</a>
		<?php endif; ?>
		<?php if(isset($user)) echo 'Welcome '.$this->Html->link($user['first_name'], array("controller"=>"wis", "action"=>"profile", $user['id']));   ?>
		
		<?php echo $this->Html->link('Home', array("controller"=>"wis", "action"=>"index")); ?>
		
	</div>
</div>
	<div id="container">
		
		<div id="content">

			<?php echo $this->Session->flash(); ?>

			<?php echo $this->fetch('content'); ?>
		</div>
		
		<div id="footers">
			<div id="links"></div>
			<div id="media"></div>
		</div>
	</div>
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>
