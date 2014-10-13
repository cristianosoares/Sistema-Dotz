<?php

class Application_Model_DbTable_RiscoProjeto extends Zend_Db_Table_Abstract
{

    protected $_name = 'risco_projeto';
	protected $_primary = 'id_risco_projeto';
	
	protected $_referenceMap = array(
 		"empresa" => array(
			"columns" => array("fk_projeto"),
			"refTableClass" => "Application_Model_DbTable_Projeto",
			
			"refColumns" => array("id_projeto")
		),
		"usuario" => array(
			"columns" => array("opened_by"),
			"refTableClass" => "Application_Model_DbTable_Usuario",
			
			"refColumns" => array("id_usuario")
		),
		"usuario" => array(
			"columns" => array("closed_by"),
			"refTableClass" => "Application_Model_DbTable_Usuario",
			
			"refColumns" => array("id_usuario")
		)

	);
	//protected $_dependentTables = array("Application_Model_DbTable_Projeto");

	public function getRiscoProjeto ($id)
    {
     
       
        $id = (int) $id;
        $sql = $this->_db->select()
             ->from(array('rp' => 'risco_projeto'),array("*",'closed_on' => new Zend_Db_Expr("DATE_FORMAT(rp.closed_on,'%d/%m/%Y')"),'opened_on' => new Zend_Db_Expr("DATE_FORMAT(rp.opened_on,'%d/%m/%Y')"),'month' => new Zend_Db_Expr("DATE_FORMAT(rp.opened_on,'%b')"),'month2' => new Zend_Db_Expr("DATE_FORMAT(rp.closed_on,'%b')")))
        		 ->joinLeft(array('p' => 'projeto'),('p.id_projeto =rp.fk_projeto'))
        		 ->where('p.exclusao <>1 and id_risco_projeto='.$id);
        Zend_Registry::get('logger')->log($sql, Zend_Log::INFO);
         $row = $this->getAdapter()->fetchRow($sql);
       
        
        
        if (! $row) {
            throw new Exception("Não foi possivel encontrar a linha $id");
        }
        return $row;
    }
	public function getRiscoProjetos(){	 
    	 $select =$this->_db->select()
             ->from(array('rp' => 'risco_projeto'))
           ->joinLeft(array('p' => 'projeto'),('p.id_projeto =rp.fk_projeto'))
           ->where("p.exclusao <>1")
               ->order('rp.id_risco_projeto desc');
              
       $result = $this->getAdapter()->fetchAll($select);
       return $result;  
                     
    }
//    SELECT YEAR(closed_on), MONTH(closed_on), COUNT(id_risco_projeto) from risco_projeto
//where closed_on !='0000-00-00 00:00:00'
//GROUP BY YEAR(closed_on), MONTH(closed_on)
    public function getRiscoProjetosGraficoClosedAdmin($fk_projeto){	 
    	 $select =$this->_db->select()
             ->from(array('rp' => 'risco_projeto'),array('quantidade' => new Zend_Db_Expr('COUNT( rp.id_risco_projeto )') ,'mes' => new Zend_Db_Expr('MONTH(rp.closed_on)'), 'ano' => new Zend_Db_Expr('YEAR(rp.closed_on)')))
            ->where("rp.closed_on !='0000-00-00 00:00:00' and fk_projeto=".$fk_projeto)
            ->group(array('ano' , 'mes'))
           ->order('rp.id_risco_projeto desc');
              
       $result = $this->getAdapter()->fetchAll($select);
       return $result;  

       
       
              
    }
    public function getRiscoProjetosGraficoOpenedAdmin($fk_projeto){	 
    	 $select =$this->_db->select()
             ->from(array('rp' => 'risco_projeto'),array('quantidade' => new Zend_Db_Expr('COUNT( rp.id_risco_projeto )') ,'mes' => new Zend_Db_Expr('MONTH(rp.opened_on)'), 'ano' => new Zend_Db_Expr('YEAR(rp.opened_on)')))
             ->joinLeft(array('p' => 'projeto'),('p.id_projeto =rp.fk_projeto'))
            ->where("p.exclusao <>1 and rp.opened_on !='0000-00-00 00:00:00' and fk_projeto=".$fk_projeto)
            ->group(array('ano' , 'mes'))
           ->order('rp.id_risco_projeto desc');
              
       $result = $this->getAdapter()->fetchAll($select);
       return $result;  

       
       
              
    }
	public function getRiscoProjetosVinculadoAdmin($fk_projeto){	 
    	 $select =$this->_db->select()
             ->from(array('rp' => 'risco_projeto'),array("*",'closed_on' => new Zend_Db_Expr("DATE_FORMAT(rp.closed_on,'%d/%m/%Y')"),'opened_on' => new Zend_Db_Expr("DATE_FORMAT(rp.opened_on,'%d/%m/%Y')"),'month' => new Zend_Db_Expr("DATE_FORMAT(rp.opened_on,'%b')"),'month2' => new Zend_Db_Expr("DATE_FORMAT(rp.closed_on,'%b')")))
           ->joinLeft(array('u' => 'usuario'),('u.id_usuario =rp.opened_by'),array('u.nome as opened_by'))
           ->joinLeft(array('u1' => 'usuario'),('u1.id_usuario =rp.closed_by'),array('u1.nome as closed_by'))
           ->joinLeft(array('p' => 'projeto'),('p.id_projeto =rp.fk_projeto'))
            ->where('p.exclusao <>1 and fk_projeto='.$fk_projeto)
           ->order('rp.id_risco_projeto desc');
              
       $result = $this->getAdapter()->fetchAll($select);
       return $result;  

       
       
              
    }
    public function addRiscoProjeto($fk_projeto,$issue,$agency,$priority,$opened_on,$opened_by,$mitigation_plan,$comments)
    {
        $data = array('fk_projeto' => $fk_projeto,'issue' => $issue,'agency' => $agency,'priority' => $priority,'opened_on' => $opened_on,'opened_by' => $opened_by,'mitigation_plan' => $mitigation_plan,'comments' => $comments);
        $this->insert($data);
    }
    public function updateRiscoProjeto ($id,$issue,$agency,$priority,$opened_on,$opened_by,$closed_on,$closed_by,$mitigation_plan,$comments)
    {
       
       $data = array('issue' => $issue,'agency' => $agency,'priority' => $priority,'opened_on' => $opened_on,'opened_by' => $opened_by,'closed_on' => $closed_on,'closed_by' => $closed_by,'mitigation_plan' => $mitigation_plan,'comments' => $comments); 
       Zend_Registry::get('logger')->log($data, Zend_Log::INFO);
       
       $this->update($data, 'id_risco_projeto = ' . (int) $id);
    }
    
public function updateRiscoProjetoSemFechar ($id,$issue,$agency,$priority,$mitigation_plan,$comments)
    {
       
       $data = array('issue' => $issue,'agency' => $agency,'priority' => $priority,'mitigation_plan' => $mitigation_plan,'comments' => $comments); 
       Zend_Registry::get('logger')->log($data, Zend_Log::INFO);
       
       $this->update($data, 'id_risco_projeto = ' . (int) $id);
    }
	public function updateRiscoProjetoFecharRisco ($id,$issue,$agency,$priority,$closed_on,$closed_by,$mitigation_plan,$comments)
    {
       
       $data = array('issue' => $issue,'agency' => $agency,'priority' => $priority,'closed_on' => $closed_on,'closed_by' => $closed_by,'mitigation_plan' => $mitigation_plan,'comments' => $comments); 
       Zend_Registry::get('logger')->log($data, Zend_Log::INFO);
       
       $this->update($data, 'id_risco_projeto = ' . (int) $id);
    }
	public function updateRiscoProjetoAbrirRisco ($id,$issue,$agency,$priority,$opened_on,$opened_by,$mitigation_plan,$comments)
    {
       
       $data = array('issue' => $issue,'agency' => $agency,'priority' => $priority,'opened_on' => $opened_on,'opened_by' => $opened_by,'mitigation_plan' => $mitigation_plan,'comments' => $comments); 
       Zend_Registry::get('logger')->log($data, Zend_Log::INFO);
       
       $this->update($data, 'id_risco_projeto = ' . (int) $id);
    }
    public function deleteRiscoProjeto ($id)
    {
        $this->delete('id_risco_projeto =' . (int) $id);
    }
	


}

