<?php

class Application_Model_DbTable_Pedido extends Zend_Db_Table_Abstract
{

    protected $_name = 'pedido';
	protected $_primary = 'id_pedido';
	
	protected $_dependentTables = array("Application_Model_DbTable_Destinatario");

	public function getPedido ($id)
    {
        $id = (int) $id;
        $row = $this->fetchRow('id_pedido = ' . $id);
        if (! $row) {
            throw new Exception("Não foi possivel encontrar a linha $id");
        }
        return $row->toArray();
    }
    public function getDestinatario ($id_pedido)
    {
    
    	
    	$select =$this->_db->select()
    	->from(array('d' => 'destinatario'))->where("d.fk_pedido = '$id_pedido' ");;
    	$result = $this->getAdapter()->fetchRow($select);
    	return $result;
    }    public  function  getRomaneioEletronico($mes,$ano){    	$select =$this->_db->select()->from(array('p' => 'pedido'))    	->joinInner(array('i' => 'item'),('p.id_pedido =i.fk_pedido'),array("*",'vlrpedido' => new Zend_Db_Expr("i.frete+ i.preco")))    	->joinInner(array('iho' => 'item_has_ocorrencia'),('iho.fk_item =i.id_item'))    	->joinInner(array('r' => 'referencia'),('r.id_referencia =i.fk_referencia'))    	->joinInner(array('pr' => 'produto'),('pr.id_produto =r.fk_produto'))    	->where("iho.final  = '1' and MONTH(iho.datahora) ='$mes' and YEAR(iho.datahora)='$ano' ");    	    	$result = $this->getAdapter()->fetchAll($select);    	return $result;    	    }
	public function getListaPedido(){		$select =$this->_db->select()
    	->from(array('p' => 'pedido'),array("*",'datacriacao' => new Zend_Db_Expr("DATE_FORMAT(p.datacriacao,'%d/%m/%Y')")))
    	->joinLeft(array('d' => 'destinatario'),('p.id_pedido =d.fk_pedido'));		
    	$result = $this->getAdapter()->fetchAll($select);
    	return $result;
    }
    public function atualizaNotaFiscal($id){
    	$data = array('id_pedido'=>$id,'nota_fiscal' => '1');
    	return $this->update($data, 'id_pedido = ' . (int) $id);
    }
	public function addPedido($id_pedido_dotz,$datacriacao,$observacao,$canalpedido,$tipocliente,$prioridade){
 		$data = array('id_pedido_dotz' => $id_pedido_dotz,'datacriacao' => $datacriacao,'observacao' => $observacao,'canalpedido' => $canalpedido,'prioridade' => $prioridade,'tipocliente'=>$tipocliente);
        return $this->insert($data);
 	}
    public function updatePedido ($id,$id_pedido_dotz,$datacriacao,$observacao,$canalpedido,$tipocliente,$prioridade){
    
        $data = array('id_pedido'=>$id,'id_pedido_dotz' => $id_pedido_dotz,'datacriacao' => $datacriacao,'observacao' => $observacao,'canalpedido' => $canalpedido,'prioridade' => $prioridade);
         
       return $this->update($data, 'id_pedido = ' . (int) $id);
    }
   	
    
    public function deletePedido ($id)
    {
        $this->delete('id_pedido =' . (int) $id);
    }
    public function getItemPedido($id_pedido){
    	$select =$this->_db->select()
    	->from(array('p' => 'pedido'))
    	->joinInner(array('i' => 'item'),('p.id_pedido =i.fk_pedido'))
    	->joinInner(array('r' => 'referencia'),('r.id_referencia =i.fk_referencia'))
    	->joinInner(array('pr' => 'produto'),('pr.id_produto =r.fk_produto'))
    	->joinInner(array('a' => 'arquivo'),('r.fk_arquivo =a.id_arquivo'),array('nome as nomeArquivo'))
    	->where("p.id_pedido = '$id_pedido' ");
    	/*->joinInner(array('a' => 'arquivo'),('a.id_arquivo =pha.fk_arquivo'),array('nome as nomeArquivo'))
    	 ->joinInner(array('p' => 'projeto'),('p.id_projeto =pha.fk_projeto'),array('nome as nomeProjeto'))
    	->where('  fk_projeto = ' . $fk_projeto);*/
    	Zend_Registry::get('logger')->log($select->__toString(), Zend_Log::INFO);
    	$result = $this->getAdapter()->fetchAll($select);
    	$i=0;
    	foreach ($result as $value){
    		$itemHasOcorrencia= new Application_Model_DbTable_ItemHasOcorrencia();
    		$possuiItemFinal=$itemHasOcorrencia->possuiItemFinal($value["id_item"]);
    		if($value["item_atualizado_dotz"]=="0"){
    			$possuiItemFinal=false;
    		}
    		$result[$i]["itemEntregue"]=$possuiItemFinal;
    		$i++;
    	}
    	return $result;
    }
    public function pedidoFinalizado($id_pedido){
    	$select =$this->_db->select()
    	->from(array('p' => 'pedido'))
    	->joinInner(array('i' => 'item'),('p.id_pedido =i.fk_pedido'))
    	->joinInner(array('r' => 'referencia'),('r.id_referencia =i.fk_referencia'))
    	->joinInner(array('pr' => 'produto'),('pr.id_produto =r.fk_produto'))
    	->joinInner(array('a' => 'arquivo'),('r.fk_arquivo =a.id_arquivo'),array('nome as nomeArquivo'))
    	->where("p.id_pedido = '$id_pedido' ");
    	
    	$result = $this->getAdapter()->fetchAll($select);
    	$i=0;
    	$pedidoFinalizado=0;
    	foreach ($result as $value){
    		$itemHasOcorrencia= new Application_Model_DbTable_ItemHasOcorrencia();
    		$possuiItemFinal=$itemHasOcorrencia->possuiItemFinal($value["id_item"]);
    		if($value["item_atualizado_dotz"]=="0"){
    			$possuiItemFinal=false;
    		}
    		if($possuiItemFinal){
    			$pedidoFinalizado++;
    		}
    		
    		$i++;
    	}
    	if($pedidoFinalizado==$i)
    		return true;
    	else 
    		return false;
    	
    }
    public function getRastreamento($id_pedido,$id_item){
    	$select =$this->_db->select()
    	->from(array('p' => 'pedido'))
    	->joinInner(array('i' => 'item'),('p.id_pedido =i.fk_pedido'))
    	
    	->where("p.id_pedido = '$id_pedido' and i.id_item ='$id_item'");
    	/*->joinInner(array('a' => 'arquivo'),('a.id_arquivo =pha.fk_arquivo'),array('nome as nomeArquivo'))
    	 ->joinInner(array('p' => 'projeto'),('p.id_projeto =pha.fk_projeto'),array('nome as nomeProjeto'))
    	->where('  fk_projeto = ' . $fk_projeto);*/
    
    	$result = $this->getAdapter()->fetchRow($select);
    	return $result;
    }
	


}

