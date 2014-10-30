<?php

class Application_Model_DbTable_Video extends Zend_Db_Table_Abstract
{

    protected $_name = 'video';
    protected $_referenceMap = array(
 		"evento" => array(
			"columns" => array("fk_evento"),
			"refTableClass" => "Application_Model_DbTable_Evento",
			
			"refColumns" => array("id")
		)

	);
	public function getVideosEvento($fk_evento){	 
    	 $select =$this->_db->select()
             ->from(array('v' => 'video'))
             ->where("fk_evento= $fk_evento")
               ->order(array('id asc'));
              
       $result = $this->getAdapter()->fetchAll($select);
       return $result;                
    }
	public function getVideo ($id)
    {
        $id = (int) $id;
        $row = $this->fetchRow('id = ' . $id);
        if (! $row) {
            throw new Exception("Não foi possível encontrar linha $id");
        }
        return $row->toArray();
    }
    public function addVideo ($nome, $url,$fk_evento)
    {
        $data = array('nome' => $nome, 'url' => $url,'fk_evento' => $fk_evento);
        return $this->insert($data);
      
    }
    public function updateVideo($id,$nome, $url,$fk_evento)
    {
         $data = array('nome' => $nome, 'url' => $url,'fk_evento' => $fk_evento);
        $this->update($data, 'id = ' . (int) $id);
    }
    public function deleteVideo ($id)
    {
        $this->delete('id =' . (int) $id);
    }
}

