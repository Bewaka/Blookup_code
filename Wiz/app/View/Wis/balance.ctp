<?php
echo $this->Html->link('English', array('language'=>'eng'));

echo $this->Html->link('Français', array('language'=>'fra'));
?>


<h1><?php echo __('Check Balance'); ?></h1>

<div style="float:right;">
	<?php if(!isset($user)) echo $this->Html->link(__('Login'), array("controller"=>"wakis", "action"=>"login")); else echo $this->Html->link(__('Logout'), array("controller"=>"wakis", "action"=>"logout"));
	?>
	
	<?php $home =  __('Home'); ?>
	<?php echo $this->Html->link( $home , array("controller"=>"wakis", "action"=>"index")); ?>
</div>

<div style="float:left; margin-right:30px;">
</div><?php echo $this->Html->link(__('Edit Profile'), array("controller"=>"wakis", "action"=>"edit", $user['id'])); ?>

<div>

</br></br>
	<?php echo __('Your balance is'); echo $user['balance'].' FCFA.'; ?></br></br>
	
	<?php echo $this->Html->link(__('Request Transfer'), array("controller"=>"wakis", "action"=>"requestTransfer"))?>

</div>



