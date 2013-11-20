 <?php app::uses('Component', 'Controller'); class wildAppComponent extends Component { 
public function display($id) {  $Animal = ClassRegistry::init('Animal');  $Example = ClassRegistry::init('Example'); 
		
		
		$result = $model->find('all', array('conditions'=>array('Example.description LIKE'=> '%'.$id.'%')));
		
		return  $result;
		
        
    }}