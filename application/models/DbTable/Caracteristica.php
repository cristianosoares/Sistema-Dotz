<?php

class Application_Model_DbTable_Caracteristica extends Zend_Db_Table_Abstract
{

    protected $_name = 'caracteristica';
	protected $_primary = 'id_caracteristica';
	
	protected $_referenceMap = array(
 		"referencia" => array(
			"columns" => array("fk_referencia"),
			"refTableClass" => "Application_Model_DbTable_Referencia",
			
			"refColumns" => array("id_referencia")
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
    



    public function addCaracteristica($nome,$valor,$fk_referencia)
    {
        $data = array('nome' => $nome,'valor' => $valor,'fk_referencia' => $fk_referencia);
        return $this->insert($data);
    }
    public function updateCaracteristica ($id,$nome,$valor)
    {
        $data = array('id_caracteristica'=>$id,'nome' => $nome,'valor' => $valor);
         
       return $this->update($data, 'id_caracteristica = ' . (int) $id);
    }
    public function deleteCaracteristica ($id)
    {
        $this->delete('id_caracteristica =' . (int) $id);
    }
	public function deleteCaracteristicaPorReferencia ($id)
    {
        $this->delete('fk_referencia =' . (int) $id);
    }


}

