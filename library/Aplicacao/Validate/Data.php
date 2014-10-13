<?php



class Aplicacao_Validate_Data extends Zend_Validate_Abstract
{
     
    const INVALID = 'cpfInvalid';
     
    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::INVALID   => " A data '%value%' tem que ser maior que a data atual",
    );
     
    public function isValid($value) {
 
	 $front      = Zend_Controller_Front::getInstance()->getRequest();
    
    $action     = $front->action;
    if($action=="edit-evento"){
    	return true;
    }
         //date_default_timezone_set( 'America/Sao_Paulo' );
         Zend_Registry::get('logger')->log($value, Zend_Log::INFO);
         $date = new Zend_Date();
         $data = new Zend_Date($date->toString('dd/MM/YYYY '));
         $data2 = new Zend_Date($value);
         
         $comparacao=$data -> isLater( $data2 );
         $comparacao2= $data->isEarlier($data2);
         $comparacao3=$data->equals($data2);
         Zend_Registry::get('logger')->log($comparacao, Zend_Log::INFO);
          Zend_Registry::get('logger')->log($comparacao2, Zend_Log::INFO);
          
         Zend_Registry::get('logger')->log($comparacao3, Zend_Log::INFO);
          
         if($comparacao3 || $comparacao2){
         	 Zend_Registry::get('logger')->log("data igual ou maior", Zend_Log::INFO);
         }else{
         	$this->_setValue($value);
         		Zend_Registry::get('logger')->log("data menor", Zend_Log::INFO);
         		$this->_error(self::INVALID);
         		return false;
         		
         	}
         	
      
     
         
       // $comparacao= $data->compare($date2);
        
        Zend_Registry::get('logger')->log($comparacao, Zend_Log::INFO);
         Zend_Registry::get('logger')->log($comparacao2, Zend_Log::INFO);
        
        return true;
    }
}
?>