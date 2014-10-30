<?php

class Application_Model_DbTable_Fase extends Zend_Db_Table_Abstract
{

    protected $_name = 'fase';
	protected $_primary = 'id_fase';
	protected $_dependentTables = array("Application_Model_DbTable_Atividade");
	

	public function getFase ($id)
    {
        $id = (int) $id;
        $row = $this->fetchRow('id_fase = ' . $id);
        if (! $row) {
            throw new Exception("Não foi possivel encontrar a linha $id");
        }
        return $row->toArray();
    }
	public function getFases(){	 
    	 $select =$this->_db->select()
             ->from(array('c' => 'fase'))
             ->joinInner(array('e' => 'empresa'),('c.fk_empresa =e.id_empresa'),array('nome as nomeEmpresa'))
               ->order('c.nome asc');
              
       $result = $this->getAdapter()->fetchAll($select);
       return $result;                
    }
   /* public function getFases()
    {
     
       $select =$this->_db->select()
             ->from(array('e' => 'fase'));
  	   $result = $this->getAdapter()->fetchAll($select);
       return $result;
       
       
    //  return $fase->getAdapter()->fetchPairs( $fase->select()->from( 'fase', array('id_fase', 'nome') )->order('nome'));
      // return $fase->getAdapter()->fetchPairs( $fase->select()->from( 'fase', array('id_fase', 'nome') )->where('id_fase <>1')->order('nome'));
    }*/
    
    public function addFase($nome,$fk_empresa)
    {
        $data = array('nome' => $nome,'fk_empresa' => $fk_empresa);
        $this->insert($data);
    }
    public function updateFase ($id,$nome,$fk_empresa)
    {
        $data = array('id_fase'=>$id,'nome' => $nome,'fk_empresa' => $fk_empresa);
         
       $this->update($data, 'id_fase = ' . (int) $id);
    }
    public function deleteFase ($id)
    {
        $this->delete('id_fase =' . (int) $id);
    }
	


}

