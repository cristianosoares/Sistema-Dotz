<?php

class Application_Model_DbTable_ProjetoHasArquivo extends Zend_Db_Table_Abstract
{

    protected $_name = 'projeto_has_arquivo';
	protected $_primary =  array("fk_projeto","fk_arquivo");
	protected $_referenceMap = array(
 		"usuario" => array(
			"columns" => array("fk_usuario"),
			"refTableClass" => "Application_Model_DbTable_Usuario",
			
			"refColumns" => array("id_usuario")
		),
		"projeto" => array(
			"columns" => array("fk_projeto"),
			"refTableClass" => "Application_Model_DbTable_Projeto",
			
			"refColumns" => array("id_projeto")
		),
		"arquivo" => array(
			"columns" => array("fk_arquivo"),
			"refTableClass" => "Application_Model_DbTable_Arquivo",
			
			"refColumns" => array("id_arquivo")
		)

	);
	public function getProjetoHasAtividade ($fk_projeto,$fk_atividade)
    {
     $fk_projeto = (int) $fk_projeto;
     $fk_atividade = (int) $fk_atividade;
        $row = $this->fetchRow(' fk_atividade='.$fk_atividade.' and fk_projeto = ' . $fk_projeto);
        if (! $row) {
            throw new Exception("Não foi possivel encontrar a linha $id");
        }
        return $row->toArray();   
    }
	public function getArquivoProjeto ($fk_projeto){	 
    	     $select =$this->_db->select()
             ->from(array('pha' => 'projeto_has_arquivo'),array("*",'dataUpload' => new Zend_Db_Expr("DATE_FORMAT(pha.dataUpload,'%d/%m/%Y %k:%i:%s')")))
             ->joinInner(array('u' => 'usuario'),('u.id_usuario =pha.fk_usuario'),array('nome as nomeUsuario'))
			->joinInner(array('a' => 'arquivo'),('a.id_arquivo =pha.fk_arquivo'),array('nome as nomeArquivo'))
			->joinInner(array('p' => 'projeto'),('p.id_projeto =pha.fk_projeto'),array('nome as nomeProjeto'))
            ->where('  fk_projeto = ' . $fk_projeto);
              
       $result = $this->getAdapter()->fetchAll($select);
       return $result;                           
	}
	public function getArquivos (){	 
    	     $select =$this->_db->select()
             ->from(array('pha' => 'projeto_has_arquivo'),array("*",'dataUpload' => new Zend_Db_Expr("DATE_FORMAT(pha.dataUpload,'%d/%m/%Y %h:%m:%s')")))
             ->joinInner(array('u' => 'usuario'),('u.id_usuario =pha.fk_usuario'),array('nome as nomeUsuario'))
			->joinInner(array('a' => 'arquivo'),('a.id_arquivo =pha.fk_arquivo'),array('nome as nomeArquivo'))
			->joinInner(array('p' => 'projeto'),('p.id_projeto =pha.fk_projeto'),array('nome as nomeProjeto'))
			  ->where('p.exclusao <>1')
            ;
              
       $result = $this->getAdapter()->fetchAll($select);
       return $result;                           
	}



    public function addArquivoProjeto($fk_projeto,$fk_usuario,$fk_arquivo,$dataUpload)
    {
        $data = array('fk_projeto' => $fk_projeto,'fk_usuario' => $fk_usuario,'fk_arquivo' => $fk_arquivo,'dataUpload' => $dataUpload);
        $this->insert($data);
    }
    public function updateArquivoProjeto ($fk_projeto,$fk_usuario,$fk_arquivo,$dataUpload)
    {
        $data = array('fk_projeto' => $fk_projeto,'fk_usuario' => $fk_usuario,'fk_arquivo' => $fk_arquivo,'dataUpload' => $dataUpload);
         
       	$this->update($data, 'fk_projeto= '.(int) $fk_projeto.' and fk_arquivo = ' . (int) $fk_arquivo);
    }
    public function deleteArquivoProjeto ($fk_projeto,$fk_arquivo)
    {
    	
        $this->delete('fk_projeto= '.(int) $fk_projeto.' and fk_arquivo = ' . (int) $fk_arquivo);
        $arquivo = new Application_Model_DbTable_Arquivo();
		$fk_arquivo=$arquivo->deleteArquivo($fk_arquivo);
    }
	


}

