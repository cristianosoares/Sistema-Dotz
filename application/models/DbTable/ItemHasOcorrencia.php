<?php

class Application_Model_DbTable_ItemHasOcorrencia extends Zend_Db_Table_Abstract
{

    protected $_name = 'item_has_ocorrencia';
	protected $_primary =  array('id_item_has_ocorrencia');
	
	protected $_referenceMap = array(
 		"ocorrencia" => array(
			"columns" => array("fk_ocorrencia"),
			"refTableClass" => "Application_Model_DbTable_Ocorrencia",
			
			"refColumns" => array("id_ocorrencia")
		),"item" => array(
			"columns" => array("fk_item"),
			"refTableClass" => "Application_Model_DbTable_Item",
			
			"refColumns" => array("id_item")
		)

	);
	
	public function getItemHasOcorrencias($fk_item){
		$select =$this->_db->select()
		->from(array('iho' => 'item_has_ocorrencia'),array("*",'dataOcorrencia' => new Zend_Db_Expr("DATE_FORMAT(iho.datahora,'%d/%m/%Y %H:%i:%s')")))
		->joinInner(array('o' => 'ocorrencia'),('iho.fk_ocorrencia =o.id_ocorrencia'))
		 
		->where("iho.fk_item  = '$fk_item'")->order('iho.id_item_has_ocorrencia asc');;
		
	
		$result = $this->getAdapter()->fetchAll($select);
		return $result;
		
		
	}
	public function getItemHasOcorrencia($id_item_has_ocorrencia){
		$select =$this->_db->select()
		->from(array('iho' => 'item_has_ocorrencia'),array("*",'dataOcorrencia' => new Zend_Db_Expr("DATE_FORMAT(iho.datahora,'%d/%m/%Y %H:%i:%s')")))
		->joinInner(array('o' => 'ocorrencia'),('iho.fk_ocorrencia =o.id_ocorrencia'))
		 
		->where("iho.id_item_has_ocorrencia = '$id_item_has_ocorrencia'");
		
		
	
		$result = $this->getAdapter()->fetchRow($select);
		/*if (! $result) {
            throw new Exception("Não foi possivel encontrar a linha $id");
        }*/
		return $result;
		
		
	}
	public function possuiItemFinal($fk_item)
	{
		$fk_item = (int) $fk_item;
		$row = $this->fetchRow("final='1' and fk_item = ". $fk_item);
		if (! $row) {
			return false;
			//throw new Exception("Não foi possivel encontrar a linha $id");
		}
		return true;
	}
	public function addItemHasOcorrencia($fk_item,$fk_ocorrencia,$observacao,$dataentrega,$final)
    {
    	$item= new Application_Model_DbTable_Item();
    	$item->atualizaItemDotz($fk_item, 0);
    	 
       $data = array('fk_item' => $fk_item,'fk_ocorrencia' => $fk_ocorrencia,'observacao' => $observacao,'final' => $final,'datahora' => $dataentrega);
        return $this->insert($data);
    }
   
    public function deleteItemHasOcorrencia ($id_item_has_ocorrencia)
    {
    	$item= new Application_Model_DbTable_Item();
    	$item->atualizaItemDotz($fk_item, 0);
    	
        $this->delete('id_item_has_ocorrencia ='.(int) $id_item_has_ocorrencia);
    }
	


}

