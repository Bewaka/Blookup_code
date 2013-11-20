
<div style="float:right;">
	
	
	<?php echo $this->Html->link('Add User', array("controller"=>"wis", "action"=>"add_waki")); ?>
	<?php echo $this->Html->link('Home', array("controller"=>"wis", "action"=>"index")); ?>
	<?php echo $this->Html->link('Password Reset', array("controller"=>"wis", "action"=>"forgotPassword")); ?>
	
</div></br></br>

<div class="users form">
<?php echo $this->Session->flash('auth'); ?>
<?php echo $this->Form->create('Wis'); ?>
    <fieldset>
        <legend><?php echo __('Please enter your username and password'); ?></legend>
        <?php 
		echo $this->Form->input('email');
        echo $this->Form->input('password');
    ?>
    </fieldset>
<?php echo $this->Form->end(__('Login')); ?>
</div>