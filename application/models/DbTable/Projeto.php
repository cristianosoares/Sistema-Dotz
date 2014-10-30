<?php

class Application_Model_DbTable_Projeto extends Zend_Db_Table_Abstract
{

    protected $_name = 'projeto';
	protected $_primary = 'id_projeto';
	
	/*protected $_referenceMap = array(
 		"empresa" => array(
			"columns" => array("fk_empresa"),
			"refTableClass" => "Application_Model_DbTable_Empresa",
			
			"refColumns" => array("id_empresa")
		)

	);*/
	protected $_dependentTables = array("Application_Model_DbTable_RiscoProjeto");

	public function getProjeto ($id)
    {
        $id = (int) $id;
        $row = $this->fetchRow('id_projeto = ' . $id);
        if (! $row) {
            throw new Exception("Não foi possivel encontrar a linha $id");
        }
        return $row->toArray();
    }
	public function getProjetos(){	 
    	 $select =$this->_db->select()
             ->from(array('p' => 'projeto'))
           ->where('exclusao <>1')
               ->order('p.nome asc');
              
       $result = $this->getAdapter()->fetchAll($select);
       return $result;                
    }
    
    public function addProjeto($nome,$fatura,$total_custo,$oibi,$fk_empresa)
    {
        
        
        
  
        $this->_db->beginTransaction();
   		try {
		    	$data = array('nome' => $nome,'fatura' => $fatura,'total_custo' => $total_custo,'oibi' => $oibi,'fk_empresa' => $fk_empresa);
        		$id_projeto=$this->insert($data);
        		$projetoHasAtividade= new Application_Model_DbTable_ProjetoHasAtividade(); 
        		 //Zend_Registry::get('logger')->log("id_projeto=".$id_projeto, Zend_Log::INFO); 
		        for($i=0;$i<11;$i++){
		        	//Zend_Registry::get('logger')->log("fk_atividade="+$i+1, Zend_Log::INFO); 
		        	  $projetoHasAtividade->addAtividadeInicio( $id_projeto, $i+1);
		        }  
		    	 $this->_db->commit();
		 
		} catch (Exception $e) {
		    
		     $this->_db->rollBack();
		    echo $e->getMessage();
		}
        
        
    }
    public function updateProjeto ($id,$nome,$fatura,$total_custo,$oibi,$fk_empresa)
    {
        $data = array('id_projeto'=>$id,'nome' => $nome,'fatura' => $fatura,'total_custo' => $total_custo,'oibi' => $oibi,'fk_empresa' => $fk_empresa);
         
       $this->update($data, 'id_projeto = ' . (int) $id);
    }
    public function deleteProjeto ($id)
    {
        //$this->delete('id_projeto =' . (int) $id);
         $data = array('exclusao'=>1);
         Zend_Registry::get('logger')->log("Excluir projeto altera exclusao".$id, Zend_Log::INFO); 
         $this->update($data, 'id_projeto = ' . (int) $id);
    }
	


}

