<?php

class Application_Model_DbTable_Ocorrencia extends Zend_Db_Table_Abstract
{

    protected $_name = 'ocorrencia';
	protected $_primary = 'id_ocorrencia';
	
	protected $_dependentTables = array("Application_Model_DbTable_ItemHasOcorrencia");

	public function getOcorrencia ($id)
    {
        $id = (int) $id;
        $row = $this->fetchRow('id_ocorrencia = ' . $id);
        if (! $row) {
            throw new Exception("Não foi possivel encontrar a linha $id");
        }
        return $row->toArray();
    }
    public function getOcorrenciaCombo ()
    {
    	$ocorrencia= new Application_Model_DbTable_Ocorrencia();
    	return $ocorrencia->getAdapter()->fetchPairs( $ocorrencia->select()->from( 'ocorrencia', array('id_ocorrencia', 'descricao') )->order('descricao'));
    }
    public function getListaOcorrencia(){
    
    
    	$select =$this->_db->select()
    	->from(array('o' => 'ocorrencia'));
    	
    	/*->joinInner(array('a' => 'arquivo'),('a.id_arquivo =pha.fk_arquivo'),array('nome as nomeArquivo'))
    	 ->joinInner(array('p' => 'projeto'),('p.id_projeto =pha.fk_projeto'),array('nome as nomeProjeto'))
    	->where('  fk_projeto = ' . $fk_projeto);*/
    
    	$result = $this->getAdapter()->fetchAll($select);
    	return $result;
    }
	public function addOcorrencia($descricao){
		
 		$data = array('descricao' =>$descricao );
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

