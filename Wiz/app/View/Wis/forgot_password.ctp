



<?php echo $this->Form->create('Waki', array('action' => 'forgotPassword')); ?>
<h1><?php echo __('Forgot Password'); ?></h1>

<div style="float:right;">
	<?php if(!isset($user)) echo $this->Html->link('Login', array("controller"=>"wakis", "action"=>"login")); else echo $this->Html->link('Logout', array("controller"=>"wakis", "action"=>"logout"));
	?>
	
	<?php echo $this->Html->link('Home', array("controller"=>"wakis", "action"=>"index")); ?>
</div>

<?php echo $this->Form->input("email" ,array('label' => 'Email/Password'))?>

<?php echo $this->Form->end('Reset'); ?>

