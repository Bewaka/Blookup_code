
<?php
echo $this->Html->link('English', array('language'=>'eng'));

echo $this->Html->link('Français', array('language'=>'fra'));
?>


<h1><?php echo $waki[0]['Waki']['first_name'].' '.$waki[0]['Waki']['last_name'];?></h1></br></br>

<?php $home =  __('Home'); ?>
<?php echo $this->Html->link( $home , array("controller"=>"wakis", "action"=>"index")); ?>

<b>Request :</b> <?php switch($request[0]['Requests']['type']){
	case 1:
	echo 'WisApp Dev';
	break;
	case 2:
	echo 'Credit';
	break;
	case 3:
	echo 'None';
	break;
	
}?></br></br>
<b>Amount: </b><?php echo $request[0]['Requests']['amount'].' FCFA' ; ?></br></br>
<b>Date Sent: </b><?php echo $request[0]['Requests']['request_date'] ; ?></br></br>