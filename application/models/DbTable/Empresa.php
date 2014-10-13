<?php

class Application_Model_DbTable_Empresa extends Zend_Db_Table_Abstract
{

    protected $_name = 'empresa';
	protected $_primary = 'id_empresa';
	
	protected $_dependentTables = array("Application_Model_DbTable_Cliente","Application_Model_DbTable_Usuario");
	

	public function getEmpresa ($id)
    {
        $id = (int) $id;
        $row = $this->fetchRow('id_empresa = ' . $id);
        if (! $row) {
            throw new Exception("Não foi possivel encontrar a linha $id");
        }
        return $row->toArray();
    }
	 public function getEmpresasCombo ()
    {
       $listaEmpresa = new Application_Model_DbTable_Empresa();
       return $listaEmpresa->getAdapter()->fetchPairs( $listaEmpresa->select()->from( 'empresa', array('id_empresa', 'nome') )->order('nome'));
    }
    public function getEmpresas()
    {
     
       $select =$this->_db->select()
             ->from(array('e' => 'empresa'));
  	   $result = $this->getAdapter()->fetchAll($select);
       return $result;
       
       
    //  return $empresa->getAdapter()->fetchPairs( $empresa->select()->from( 'empresa', array('id_empresa', 'nome') )->order('nome'));
      // return $empresa->getAdapter()->fetchPairs( $empresa->select()->from( 'empresa', array('id_empresa', 'nome') )->where('id_empresa <>1')->order('nome'));
    }
    
    public function addEmpresa($nome)
    {
        $data = array('nome' => $nome);
        $this->insert($data);
    }
    public function updateEmpresa ($id,$nome)
    {
        $data = array('id_empresa'=>$id,'nome' => $nome);
         
       $this->update($data, 'id_empresa = ' . (int) $id);
    }
    public function deleteEmpresa ($id)
    {
        $this->delete('id_empresa =' . (int) $id);
    }


}

