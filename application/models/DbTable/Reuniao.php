<?php

class Application_Model_DbTable_Reuniao extends Zend_Db_Table_Abstract
{

    protected $_name = 'reuniao';
	protected $_primary = 'id_reuniao';
	protected $_dependentTables = array('Application_Model_DbTable_UsuarioHasReuniao');

	/*public function getReuniao ($id)
    {
        $id = (int) $id;
        $row = $this->fetchRow('id_reuniao = ' . $id);
        if (! $row) {
            throw new Exception("Não foi possivel encontrar a linha $id");
        }
        return $row->toArray();
    }*/
	public function getReuniosAdmin(){	 
    	 $select =$this->_db->select()
               ->from(array('r' => 'reuniao'),array("*",'date' => new Zend_Db_Expr("DATE_FORMAT(r.date,'%d/%m')")))
              ->order('r.date desc');
              
              
       $result = $this->getAdapter()->fetchAll($select);
       return $result;                
    }
	 public function getReuniaoCombo ()
    {
       $listaReuniao = new Application_Model_DbTable_Reuniao();
       return $listaReuniao->getAdapter()->fetchPairs( $listaReuniao->select()->from( 'reuniao', array('id_reuniao', 'nome') )->order('nome'));
    }
    public function getReunioes()
    {
     
       $select =$this->_db->select()
             ->from(array('e' => 'reuniao'));
  	   $result = $this->getAdapter()->fetchAll($select);
       return $result;
       
       
    //  return $reuniao->getAdapter()->fetchPairs( $reuniao->select()->from( 'reuniao', array('id_reuniao', 'nome') )->order('nome'));
      // return $reuniao->getAdapter()->fetchPairs( $reuniao->select()->from( 'reuniao', array('id_reuniao', 'nome') )->where('id_reuniao <>1')->order('nome'));
    }
	public function getReuniao ($id)
    {
        $id = (int) $id;
       // $row = $this->fetchRow("nome",null,'id = ' . $id);
        $select= $select =$this->_db->select()
             ->from(array('r' => 'reuniao'),array("*",'date' => new Zend_Db_Expr("DATE_FORMAT(r.date,'%d/%m/%Y')")))->where('id_reuniao ='.$id);
             $row = $this->getAdapter()->fetchRow($select);
        if (! $row) {
            throw new Exception("N��o foi possivel encontrar a linha $id");
        }
        
       
       // $row["fk_usuario"]=$usuarioHasReuniao->getParticipanteReuniaoCombo ($id);
        
        return $row;
       // return $row->toArray();
    }
  
    
    public function addReuniao($nome,$date,$comentarios)
    {
        $data = array('nome' => $nome,'date' => $date,'comentarios' => $comentarios);
        return $this->insert($data);
    }
    public function updateReuniao ($id,$nome,$date,$comentarios)
    {
        $data = array('id_reuniao'=>$id,'nome' => $nome,'date' => $date,'comentarios' => $comentarios);
         
       $this->update($data, 'id_reuniao = ' . (int) $id);
    }
    public function deleteReuniao ($id)
    {
    	$reuniao=$this->getReuniao ($id);
    	$usuarioReuniao = new Application_Model_DbTable_UsuarioHasReuniao();
    	$usuarioReuniao->deleteReuniao ($id);
        $this->delete('id_reuniao =' . (int) $id);
    }
	


}

