<?php


class Transaction extends AppModel {
	
	public $belongsTo = array(
	’User’ => array(
	’className’ => ’Waki’,
	’foreignKey’ => ’id’
	)
	);


}
