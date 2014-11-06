<?php

class Application_Model_DbTable_Fornecedor extends Zend_Db_Table_Abstract
{

    protected $_name = 'fornecedor';
	protected $_primary = 'idFornecedor';
	
	protected $_dependentTables = array("Application_Model_DbTable_Produto");

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
		
            $data = array('codFornecedor' =>$codFornecedor, 'nomFornecedor'=>$nomFornecedor );
            return $this->insert($data);
 	}
    public function updateFornecedor ($id,$codFornecedor,$nomFornecedor )
    {
        $data = array('idFornecedor'=>$id,'codFornecedor' =>$codFornecedor,'nomFornecedor' =>$nomFornecedor );
         
       return $this->update($data, 'idFornecedor = ' . (int) $id);
    }
   
    
    public function deleteFornecedor ($id)
    {
        $this->delete('idFornecedor =' . (int) $id);
    }
    
    public function getFornecedores ()
    {
       $fornecedor = new Application_Model_DbTable_Fornecedor();
       return $fornecedor->getAdapter()->fetchPairs( $fornecedor->select()->from( 'fornecedor', array('nomFornecedor', 'nomFornecedor') )->order('nomFornecedor'));
    }
	


}

