<?php

class Application_Model_DbTable_Atividade extends Zend_Db_Table_Abstract
{

    protected $_name = 'atividade';
	protected $_primary = 'id_atividade';
	
	protected $_referenceMap = array(
 		"empresa" => array(
			"columns" => array("fk_fase"),
			"refTableClass" => "Application_Model_DbTable_Fase",
			
			"refColumns" => array("id_fase")
		)

	);
	protected $_dependentTables = array("Application_Model_DbTable_ProjetoHasAtividade");

	public function getAtividade ($id)
    {
        $id = (int) $id;
        $row = $this->fetchRow('id_atividade = ' . $id);
        if (! $row) {
            throw new Exception("Não foi possivel encontrar a linha $id");
        }
        return $row->toArray();
    }
	public function getAtividades(){	 
    	 $select =$this->_db->select()
             ->from(array('a' => 'atividade'))
        		->order('a.nome asc');
              
       $result = $this->getAdapter()->fetchAll($select);
       return $result;                
    }
    



    public function addAtividade($nome,$fk_fase,$descricao)
    {
        $data = array('nome' => $nome,'fk_fase' => $fk_fase,'descricao' => $descricao);
        $this->insert($data);
    }
    public function updateAtividade ($id,$nome,$fk_fase,$descricao)
    {
        $data = array('id_atividade'=>$id,'nome' => $nome,'fk_fase' => $fk_fase,'descricao' => $descricao);
         
       $this->update($data, 'id_atividade = ' . (int) $id);
    }
    public function deleteAtividade ($id)
    {
        $this->delete('id_atividade =' . (int) $id);
    }
	


}

