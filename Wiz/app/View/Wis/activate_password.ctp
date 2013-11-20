
				<h1><?php echo __('Reset Password'); ?></h1>

					<?php echo $this->Form->create('Waki', array('action' => 'activatePassword')); ?>
					<?php echo $this->Form->input("password" ,array("type"=>"password", "label"=>"New Password")); ?>
					<?php echo $this->Form->input("cpassword" ,array("type"=>"password",'label' => 'Confirm Password')); ?>

					<?php   if (!isset($ident)) {
							$ident='';
						}
						if (!isset($activate)) {
							$activate='';
						}   ?>
						<?php echo $this->Form->hidden('ident',array('value'=>$ident))?>
						<?php echo $this->Form->hidden('activate',array('value'=>$activate))?>
						<?php echo $this->Form->Submit(__('Reset'));?>
						
						<?php echo $this->Form->end(); ?>
				