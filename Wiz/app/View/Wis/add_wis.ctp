
<h1>Add Waki </h1>
<div style="float:right;">
	<?php if(!isset($user)) echo $this->Html->link('Login', array("controller"=>"wakis", "action"=>"login")); else echo $this->Html->link('Logout', array("controller"=>"wakis", "action"=>"logout"));
	?>
	
	<?php echo $this->Html->link('Home', array("controller"=>"wakis", "action"=>"index")); ?>
</div>
<?php

	echo $this->Form->create('Waki');
	
	echo $this->Form->input('first_name');
	echo $this->Form->input('last_name');
	echo $this->Form->input('email');
	echo $this->Form->input('password');
	echo $this->Form->input('cpassword',array("type"=>"password", "label"=>"Confirm Password"));
	echo $this->Form->input('username',array("type"=>"hidden"));
	
	echo $this->Form->end('Add User');

?>