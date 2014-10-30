<?php

class Application_Model_DbTable_Destinatario extends Zend_Db_Table_Abstract
{

    protected $_name = 'destinatario';
	protected $_primary = 'id_destinatario';
	
	protected $_dependentTables = array("Application_Model_DbTable_Destinatario");

	public function getDestinatario ($id)
    {
        $id = (int) $id;
        $row = $this->fetchRow('id_destinatario = ' . $id);
        if (! $row) {
            throw new Exception("Não foi possivel encontrar a linha $id");
        }
        return $row->toArray();
    }
    public function addDestinatario($documento,$tipopessoa,$nome,$email,$rua,$numero,$compl,$bairro,$cidade,$estado,$cep,$ddd,$telefone,$pontoreferencia,$codigoidnt,$fk_pedido){
 		$data = array('documento' => $documento,'tipopessoa' => $tipopessoa,'nome' => $nome,'email' => $email,'rua' => $rua,'numero' => $numero,'compl' => $compl,
 				'bairro' => $bairro,'cidade' => $cidade,'uf' => $estado,'cep' => $cep,'ddd' => $ddd,'telefone' => $telefone,'pontoreferencia' => $pontoreferencia,'codigoident' => $codigoidnt,'fk_pedido' =>$fk_pedido);
        return $this->insert($data);
 	}
    public function updateDestinatario ($id_destinatario,$id,$id_destinatario_dotz,$datacriacao,$observacao,$canaldestinatario,$tipocliente,$prioridade,$fk_pedido){
    
        $data = array('id_destinatario'=>$id,'documento' => $documento,'tipopessoa' => $tipopessoa,'nome' => $nome,'email' => $email,'rua' => $rua,'numero' => $numero,'compl' => $compl,
 				'bairro' => $bairro,'cidade' => $cidade,'estado' => $estado,'cep' => $cep,'ddd' => $ddd,'telefone' => $telefone,'pontoreferencia' => $pontoreferencia,'codigoidnt' => $codigoidnt,'fk_pedido' =>$fk_pedido);
         
       return $this->update($data, 'id_destinatario = ' . (int) $id);
    }
   
    
    public function deleteDestinatario ($id)
    {
        $this->delete('id_destinatario =' . (int) $id);
    }
	


}

