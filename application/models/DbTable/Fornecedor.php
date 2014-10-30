<?php

class Application_Model_DbTable_Fornecedor extends Zend_Db_Table_Abstract
{

    protected $_name = 'fornecedor';
	protected $_primary = 'idFornecedor';
	
	protected $_dependentTables = array("Application_Model_DbTable_ItemHasFornecedor");

	public function getFornecedor ($id)
    {
        $id = (int) $id;
        $row = $this->fetchRow('idFornecedor = ' . $id);
        if (! $row) {
            throw new Exception("Não foi possivel encontrar a linha $id");
        }
        return $row->toArray();
    }
    public function getFornecedorCombo ()
    {
    	$fornecedor= new Application_Model_DbTable_Fornecedor();
    	return $fornecedor->getAdapter()->fetchPairs( $fornecedor->select()->from( 'fornecedor', array('idFornecedor', 'nomFornecedor') )->order('nomFornecedor'));
    }
    public function getListaFornecedor(){
    
    
    	$select =$this->_db->select()
    	->from(array('f' => 'fornecedor'));
    
    	$result = $this->getAdapter()->fetchAll($select);
    	return $result;
    }
	public function addFornecedor($codFornecedor,$nomFornecedor){
		
 		$data = array('codFornecedor' =>$nomFornecedor );
                $data = array('nomFornecedor' =>$nomFornecedor );
        return $this->insert($data);
 	}
    public function updateOcorrencia ($id,$descricao )
    {
        $data = array('id_ocorrencia'=>$id,'descricao' =>$descricao );
         
       return $this->update($data, 'id_ocorrencia = ' . (int) $id);
    }
   
    
    public function deleteOcorrencia ($id)
    {
        $this->delete('id_ocorrencia =' . (int) $id);
    }
	


}

