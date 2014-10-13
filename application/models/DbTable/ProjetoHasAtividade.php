<?php

class Application_Model_DbTable_ProjetoHasAtividade extends Zend_Db_Table_Abstract
{

    protected $_name = 'projeto_has_atividade';
	protected $_primary =  array("fk_projeto","fk_atividade");
	protected $_referenceMap = array(
 		"atividade" => array(
			"columns" => array("fk_atividade"),
			"refTableClass" => "Application_Model_DbTable_Atividade",
			
			"refColumns" => array("id_atividade")
		),
		"projeto" => array(
			"columns" => array("fk_projeto"),
			"refTableClass" => "Application_Model_DbTable_Projeto",
			
			"refColumns" => array("id_projeto")
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
	public function getProjetoHasAtividade2 ($fk_projeto,$fk_atividade){	 
    	     $select =$this->_db->select()
             ->from(array('pha' => 'projeto_has_atividade'),array("*",'data_inicio' => new Zend_Db_Expr("DATE_FORMAT(pha.data_inicio,'%d/%m/%Y')"),'data_fim' => new Zend_Db_Expr("DATE_FORMAT(pha.data_fim,'%d/%m/%Y')"),'eta' => new Zend_Db_Expr("DATE_FORMAT(pha.eta,'%d/%m/%Y')")))
             ->joinInner(array('a' => 'atividade'),('a.id_atividade =pha.fk_atividade'),array('nome as nome_atividade','descricao'))
			 ->joinInner(array('f' => 'fase'),('f.id_fase =a.fk_fase'),array('nome as nome_fase'))
            ->where(' fk_atividade='.$fk_atividade.' and fk_projeto = ' . $fk_projeto);
              
       $result = $this->getAdapter()->fetchRow($select);
       return $result;                           
	}



    public function addAtividade($fk_projeto,$fk_atividade,$data_inicio,$eta,$data_fim,$comentario)
    {
        $data = array('fk_projeto' => $fk_projeto,'fk_atividade' => $fk_atividade,'data_inicio' => $data_inicio,'eta' => $eta,'data_fim' => $data_fim,'comentario' => $comentario);
        $this->insert($data);
    }
	public function addAtividadeInicio($fk_projeto,$fk_atividade){
        $data = array('fk_projeto' => $fk_projeto,'fk_atividade' => $fk_atividade);
        $this->insert($data);
    }
    public function updateAtividade ($fk_projeto,$fk_atividade,$data_inicio,$eta,$data_fim,$comentario)
    {
        $data = array('fk_projeto' => $fk_projeto,'fk_atividade' => $fk_atividade,'data_inicio' => $data_inicio,'eta' => $eta,'data_fim' => $data_fim,'comentario' => $comentario);
         
       $this->update($data, 'fk_atividade= '.(int) $fk_atividade.' and fk_projeto = ' . (int) $fk_projeto);
    }
    public function deleteFase ($fk_projeto,$fk_atividade)
    {
        $this->delete('fk_atividade= '.(int) $fk_atividade.' and fk_projeto = ' . (int) $fk_projeto);
    }
	


}

