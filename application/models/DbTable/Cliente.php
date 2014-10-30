<?php

class Application_Model_DbTable_Cliente extends Zend_Db_Table_Abstract
{

    protected $_name = 'cliente';
	protected $_primary = 'id_cliente';
	
	protected $_referenceMap = array(
 		"empresa" => array(
			"columns" => array("fk_empresa"),
			"refTableClass" => "Application_Model_DbTable_Empresa",
			
			"refColumns" => array("id_empresa")
		)

	);
	//protected $_dependentTables = array("Application_Model_DbTable_Projeto");

	public function getCliente ($id)
    {
        $id = (int) $id;
        $row = $this->fetchRow('id_cliente = ' . $id);
        if (! $row) {
            throw new Exception("Não foi possivel encontrar a linha $id");
        }
        return $row->toArray();
    }
	public function getClientes(){	 
    	 $select =$this->_db->select()
             ->from(array('c' => 'cliente'))
             ->joinInner(array('e' => 'empresa'),('c.fk_empresa =e.id_empresa'),array('nome as nomeEmpresa'))
               ->order('c.nome asc');
              
       $result = $this->getAdapter()->fetchAll($select);
       return $result;                
    }
   /* public function getClientes()
    {
     
       $select =$this->_db->select()
             ->from(array('e' => 'cliente'));
  	   $result = $this->getAdapter()->fetchAll($select);
       return $result;
       
       
    //  return $cliente->getAdapter()->fetchPairs( $cliente->select()->from( 'cliente', array('id_cliente', 'nome') )->order('nome'));
      // return $cliente->getAdapter()->fetchPairs( $cliente->select()->from( 'cliente', array('id_cliente', 'nome') )->where('id_cliente <>1')->order('nome'));
    }*/
    
    public function addCliente($nome,$fk_empresa)
    {
        $data = array('nome' => $nome,'fk_empresa' => $fk_empresa);
        $this->insert($data);
    }
    public function updateCliente ($id,$nome,$fk_empresa)
    {
        $data = array('id_cliente'=>$id,'nome' => $nome,'fk_empresa' => $fk_empresa);
         
       $this->update($data, 'id_cliente = ' . (int) $id);
    }
    public function deleteCliente ($id)
    {
        $this->delete('id_cliente =' . (int) $id);
    }
	


}

