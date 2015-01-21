<?php

class Application_Model_DbTable_Item extends Zend_Db_Table_Abstract
{

    protected $_name = 'item';
	protected $_primary = 'id_item';
	
	protected $_dependentTables = array("Application_Model_DbTable_Referencia","Application_Model_DbTable_Item");

	public function getItem ($id)
    {
        $id = (int) $id;
        $row = $this->fetchRow('id_item = ' . $id);
        if (! $row) {
            throw new Exception("Não foi possivel encontrar a linha $id");
        }
        return $row->toArray();
    }
   
	public function addItem($produtoiddotz,$preco,$frete,$qtde,$nomeproduto,$itemid,$fk_pedido,$fk_referencia){
		
 		$data = array('produtoiddotz' => $produtoiddotz,'preco' => $preco,'frete' => $frete,'qtde' => $qtde,'nomeproduto' => $nomeproduto,'itemid' => $itemid,'fk_pedido' => $fk_pedido,'fk_referencia' => $fk_referencia);
        return $this->insert($data);
 	}
    public function updateItem ($id,$produtoiddotz,$preco,$frete,$qtde,$nomeproduto,$itemid,$fk_pedido,$fk_referencia)
    {
        $data = array('id_item'=>$id,'produtoiddotz' => $produtoiddotz,'preco' => $preco,'frete' => $frete,'qtde' => $qtde,'nomeproduto' => $nomeproduto,'itemid' => $itemid,'fk_pedido' => $fk_pedido,'fk_referencia' => $fk_referencia);
         
       return $this->update($data, 'id_item = ' . (int) $id);
    }
    public function atualizaItemDotz($id,$item_atualizado_dotz){
    	$data = array('id_item'=>$id,'item_atualizado_dotz' => $item_atualizado_dotz);
    	return $this->update($data, 'id_item = ' . (int) $id);
    }
    public function updateItemReferencia ($id,$nr_rastreio,$u_chave,$numero_nf,$numero_linha_nf,$peso)
    {
    	$data = array('id_item'=>$id,'nr_rastreio' => $nr_rastreio,'numero_nf' => $numero_nf,'u_chave' => $u_chave,'numero_linha_nf' => $numero_linha_nf,'peso' => $peso,'item_enviado_dotz'=>1);
    	 
    	return $this->update($data, 'id_item = ' . (int) $id);
    }
    
    public function deleteItem ($id)
    {
        $this->delete('id_item =' . (int) $id);
    }
	


}

