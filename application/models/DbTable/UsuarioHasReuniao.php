<?php

class Application_Model_DbTable_ReferenciaHasReferenciaHasCaracteristica extends Zend_Db_Table_Abstract
{

    protected $_name = 'caracteristica';
	protected $_primary = 'id_caracteristica';
	
	
	protected $_referenceMap = array(
 		"referencia" => array(
			"columns" => array("fk_referencia"),
			"refTableClass" => "Application_Model_DbTable_Referencia",
			
			"refColumns" => array("id_referencia")
		),
		"caracteristica" => array(
			"columns" => array("fk_caracteristica"),
			"refTableClass" => "Application_Model_DbTable_ReferenciaHasCaracteristica",
			
			"refColumns" => array("id_caracteristica")
		)

	);

	public function getReferenciaHasCaracteristica ($fk_caracteristica,$fk_referencia)
    {
        $id = (int) $id;
        $row = $this->fetchRow('fk_referencia = ' . (int)$fk_referencia.' and fk_caracteristica = ' . (int)$fk_caracteristica);
        if (! $row) {
            throw new Exception("Não foi possivel encontrar a linha $id");
        }
        return $row->toArray();
    }
	public function getReferenciaHasCaracteristicas(){	 
    	 $select =$this->_db->select()
             ->from(array('p' => 'referencia_has_caracteristica'));
              
       $result = $this->getAdapter()->fetchAll($select);
       return $result;                
    }
    



    public function addReferenciaHasCaracteristica($fk_caracteristica,$fk_referencia)
    {
        $data = array('fk_caracteristica' => (int)$fk_caracteristica,'$fk_referencia' => (int)$fk_referencia);
        return $this->insert($data);
    }
    public function deleteReferenciaHasCaracteristica ($fk_caracteristica,$fk_referencia)
    {
        $this->delete('fk_referencia = ' . (int)$fk_referencia.' and fk_caracteristica = ' . (int)$fk_caracteristica);
    }
	


}

