<?php

class Application_Model_DbTable_ReferenciaHasCaracteristica extends Zend_Db_Table_Abstract
{

    protected $_name = 'referencia_has_caracteristica';
	protected $_primary =  array('fk_referencia','fk_caracteristica');
	
	protected $_referenceMap = array(
 		"referencia" => array(
			"columns" => array("fk_referencia"),
			"refTableClass" => "Application_Model_DbTable_Referencia",
			
			"refColumns" => array("id_referencia")
		),"caracteristica" => array(
			"columns" => array("fk_caracteristica"),
			"refTableClass" => "Application_Model_DbTable_Caracteristica",
			
			"refColumns" => array("id_caracteristica")
		)

	);

	public function getCaracteristica ($id)
    {
        $id = (int) $id;
        $row = $this->fetchRow('id_caracteristica = ' . $id);
        if (! $row) {
            throw new Exception("Não foi possivel encontrar a linha $id");
        }
        return $row->toArray();
    }
	public function getCaracteristicas(){	 
    	 $select =$this->_db->select()
             ->from(array('p' => 'caracteristica'))
        		->order('p.nome asc');
              
       $result = $this->getAdapter()->fetchAll($select);
       return $result;                
    }
    



    public function addReferenciaHasCaracteristica($fk_referencia,$fk_caracteristica)
    {
        $data = array('fk_referencia' => $fk_referencia,'fk_caracteristica' => $fk_caracteristica);
        return $this->insert($data);
    }
   
    public function deleteReferenciaHasCaracteristica ($fk_referencia,$fk_caracteristica)
    {
        $this->delete('fk_caracteristica ='.(int) $fk_caracteristica.' and fk_referencia =' . (int) $fk_referencia);
    }
	


}

