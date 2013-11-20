<?php
echo $this->Html->link('English', array('language'=>'eng'));

echo $this->Html->link('Français', array('language'=>'fra'));
?>


<h1><?php echo __('Welcome '); echo $user['first_name'].' '.$user['last_name']; ?></h1></br></br>

<div style="float:right;">
	<?php if(!isset($user)) echo $this->Html->link('Login', array("controller"=>"wakis", "action"=>"login")); else echo $this->Html->link('Logout', array( "action"=>"logout"));
	?>
	
	<?php $home = __('Home'); ?>
	<?php echo $this->Html->link( $home , array("controller"=>"wis", "action"=>"index")); ?>
	<?php echo $this->Html->link(__('Apps') , array("controller"=>"wis", "action"=>"apps", $user['id'])); ?>
	<?php echo $this->Html->link('Edit Profile', array("controller"=>"wis", "action"=>"edit", $user['id'])); ?>
</div>

<div style="float:left; margin-right:30px;"><h2>Profile</h2></div>



<div>
	</br></br></br></br>
	<b><?php echo __('User Type: '); ?>  <?php switch($user['type']){
		
		case 0:
		echo 'WakiDat';
		break;
		case 1:
		echo 'WakiDev';
		break;
		case 2:
		echo 'Both';
		break;
		
	} ?></br></br>
	
	<?php echo __('Fist Name: '); ?> <?php echo $user['first_name']; ?> </br></br>
	<?php echo __('Last Name: '); ?> <?php echo $user['last_name']; ?> </br></br>
	<?php echo __('Email: '); ?>  <?php echo $user['email']; ?></br></br>
	<?php echo __('Phone Number: '); ?>  <?php echo $user['phone']; ?></br></br>
	<?php echo __('City: '); ?>  <?php echo $user['city']; ?></br></br>	
	<?php echo __('Balance: '); ?>  <?php echo $user['balance']; ?></br></br></b>


</div>



