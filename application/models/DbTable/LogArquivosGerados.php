<?php
set_time_limit(0);
error_reporting(E_ALL & ~(E_STRICT|E_NOTICE));
class Application_Model_DbTable_LogArquivosGerados extends Zend_Db_Table_Abstract
{

        protected $_name = 'log_arquivos_gerados';
	protected $_primary = 'id_log_arquivos_gerados';
	protected $urlCompleta='http://www.tm1.com.br/dotz/upload/';
	protected $identificacaoParceiro="TM1DISTRIBUIDORA";
	protected $caminhoPastaFtp = BASE_PATH;
	protected $pastaFtp ='/uploadXml/';
	protected $ftp_server = ""; // Nome ou IP do Servidor
	protected $login      = ""; // UsuÃ¡rio
	protected $senha      = "";   // Senha
	protected $diretorioEntrada  = "/ENTRADA/"; // DiretÃ³rio onde deverÃ¡ acessar (default)
	protected $diretorioEntradaLocal  = "ENTRADA"; // DiretÃ³rio onde deverÃ¡ acessar (default)
	protected $diretorioSaida  = "/SAIDA/"; // DiretÃ³rio onde deverÃ¡ acessar (default)
	protected $diretorioSaidaLocal  = "SAIDA"; // DiretÃ³rio onde deverÃ¡ acessar (default)
	protected $conn_id;
	protected $login_result;
	//protected $identificacaoParceiro;
	protected $_referenceMap = array(
 		"arquivo" => array(
			"columns" => array("fk_arquivo"),
			"refTableClass" => "Application_Model_DbTable_Arquivo",
			"refColumns" => array("id_arquivo"),
		)

	);
	//public function Application_Model_DbTable_LogArquivosGerados(){
		//$this->identificacaoParceiro = "TemperoMidia";
	//}
	function upload($fonte,$destino) {
		$modo=FTP_ASCII;
		$this->criaConexao();
		if ($this->conn_id == "") 
		
		throw new Exception("Você deve conectar primeiro"."<br>","6");
		if(!ftp_put($this->conn_id,$this->diretorioEntrada.$destino,$fonte,$modo)){
			throw new Exception("Erro ao enviar arquivo $destino "."<br>","7");
		}
	}
	public function buscaArquivosPastaEntrada(){
		if ($this->conn_id == "") 
			$this->criaConexao();
		$lista = ftp_nlist($this->conn_id, $this->diretorioSaida);
		Zend_Registry::get('logger')->log($lista, Zend_Log::INFO);
		$listaArquivos=array();
		foreach($lista as $file){
			$nomeArquivo=(str_replace($this->diretorioSaida,"",$file));
			Zend_Registry::get('logger')->log("nomeArquivo= $nomeArquivo", Zend_Log::INFO);
			if (!file_exists($this->diretorioSaidaLocal."/$nomeArquivo")) {
    					$listaArquivos[]=$nomeArquivo;
    					Zend_Registry::get('logger')->log("Upload Arquivo", Zend_Log::INFO);
    					Zend_Registry::get('logger')->log("Saida ftp".$this->diretorioSaida.$nomeArquivo, Zend_Log::INFO);
    					Zend_Registry::get('logger')->log("Entrada local".$this->diretorioSaida.$nomeArquivo, Zend_Log::INFO);
    					
    					
    					
    					$recebe = ftp_get($this->conn_id, $this->diretorioSaidaLocal."/".$nomeArquivo,$this->diretorioSaida.$nomeArquivo, FTP_BINARY); // Retorno: true / false
    					Zend_Registry::get('logger')->log("Fim Upload Arquivo".$recebe, Zend_Log::INFO);
			}
			
		}
		
		return $listaArquivos;
	}
	public function criaConexao(){
		// efetua a conexÃ£o
		
			
		
		$this->conn_id = ftp_connect($this->ftp_server);
		// caso ocorra algum erro de conexao
		if(!$this->conn_id ){
			//echo "nao foi possivel conectar ao servidor de ftp dp site ".$ftp;
			throw new Exception("Nao foi possivel conectar ao servidor de ftp $this->ftp_server"."<br>","4");
			exit;
		}else{
			// faz a autenticaÃ§Ã£o do usuario - nessa parte sera necessÃ¡rio informar o login e senha
			$this->login_result = ftp_login($this->conn_id, $this->login, $this->senha);
			if(!$this->login_result){
				//echo "erro ao efetuar login";
				throw new Exception("Erro ao efetuar login no ftp da dotz"."<br>","5");
				exit;
			}else{
				
				
			}
		}
		
	}
	public function getLogArquivosGerados($id)
    {
        $id = (int) $id;
        $row = $this->fetchRow('id_log_arquivos_gerados = ' . $id);
        if (! $row) {
            throw new Exception("Não foi possivel encontrar a linha $id");
        }
        return $row->toArray();
    }
    public function getRetornarSequencial($layout){
		$data_atual = date("Y-m-d"); 
		$select =$this->_db->select()->from(array('log' => 'log_arquivos_gerados'),array('datacriacao' => 'datacriacao','quantidadeArquivosDia' => 'COUNT(log.layout)'))
		->where("  log.layout  ='$layout' and log.datacriacao ='$data_atual' " )
		->group("log.datacriacao");
		
	Zend_Registry::get('logger')->log($select->__toString(), Zend_Log::INFO);
	$result = $this->getAdapter()->fetchAll($select);
	Zend_Registry::get('logger')->log($result, Zend_Log::INFO);	
	if(count($result)>0){
		return $result[0]["quantidadeArquivosDia"];
	}
	return 0;
	}
	public function getListaArquivos(){
		
		
		$select =$this->_db->select()
		->from(array('l' => 'log_arquivos_gerados'),array("*",'datacriacaoFormat' => new Zend_Db_Expr("DATE_FORMAT(l.datacriacao,'%d/%m/%Y')")))
		->joinInner(array('a' => 'arquivo'),('l.fk_arquivo =a.id_arquivo'));
		/*->joinInner(array('a' => 'arquivo'),('a.id_arquivo =pha.fk_arquivo'),array('nome as nomeArquivo'))
		 ->joinInner(array('p' => 'projeto'),('p.id_projeto =pha.fk_projeto'),array('nome as nomeProjeto'))
		->where('  fk_projeto = ' . $fk_projeto);*/
		
		$result = $this->getAdapter()->fetchAll($select);
		return $result;
	}
	public function addLogArquivosGerados ($layout,$enviadorecebido,$nomeArquivo,$sequencial)
    {
    	
   
    	$data_atual = date("Y-m-d 00:00:00"); 
    	$arquivo = new Application_Model_DbTable_Arquivo();
    	Zend_Registry::get('logger')->log("nome do arquivo =".$nomeArquivo, Zend_Log::INFO);	
       	$fk_arquivo=$arquivo->addArquivo($nomeArquivo, "xml");	
        $data = array('sequencial' => $sequencial,'layout' => $layout, 'datacriacao' => $data_atual, 'enviadorecebido' => $enviadorecebido,'fk_arquivo'=>$fk_arquivo);
        return $this->insert($data);
    }
    public function gerarLayout950X($referencias){
    	$layout= "950X";
    	
			Zend_Registry::get('logger')->log("entrou layout 950x", Zend_Log::INFO);
			$produto = new Application_Model_DbTable_Produto();
                        $listaProdutos=$produto->getListProdutosNovosXml($referencias);
                        
			if(count($listaProdutos)<=0){
				throw new Exception("Não existe novos produtos para enviar para DOTZ");
			}
			#versao do encoding xml
			$dom = new DOMDocument("1.0", "ISO-8859-1");
				
			#retirar os espacos em branco
			$dom->preserveWhiteSpace = false;
				
			#gerar o codigo
			$dom->formatOutput = true;
				
			#criando o nÃ³ principal (root)
			$CATALOGO = $dom->createElement("CATALOGO");
				
			#nÃ³ filho (contato)
			$id_produto=0;
			$PRODUTOS = $dom->createElement("PRODUTOS");
			foreach($listaProdutos as $value){
					$PRODUTO = $dom->createElement("PRODUTO");
					$PRODUTOID = $dom->createElement("PRODUTOID", $value["id_produto"]);
					$NOMEPRODUTO = $dom->createElement("NOMEPRODUTO", $value["nome"]);
                                        $FORNECEDOR = $dom->createElement("FORNECEDOR", $value["fk_fornecedor"]);
					$DESCRICAO = $dom->createElement("DESCRICAO", $value["descricao"]);
					$PALAVRASCHAVE = $dom->createElement("PALAVRASCHAVE");	
					$palavrachave=explode(",",$value["palavrachave"]);
					foreach ($palavrachave as $value2){
							$PALAVRA= $dom->createElement("PALAVRA",$value2);
							$PALAVRASCHAVE->appendChild($PALAVRA);
					}
					$URL= $dom->createElement("URL", $value["url"]);
					$PRODUTO->appendChild($PRODUTOID);
					$PRODUTO->appendChild($NOMEPRODUTO);
                                        $PRODUTO->appendChild($FORNECEDOR);
					$PRODUTO->appendChild($DESCRICAO);
					$PRODUTO->appendChild($URL);
					$PRODUTO->appendChild($PALAVRASCHAVE);
					
					$REFERENCIAS = $dom->createElement("REFERENCIAS");
					Zend_Registry::get('logger')->log($value["referencias"], Zend_Log::INFO);
                                        
                                        $r=new Application_Model_DbTable_Referencia();
				    foreach ($value["referencias"] as $value3){
                                                $r->updateReferenciaInseridoDotz ($value3["id_referencia"]);//atualiza referencia
				    		$REFERENCIA = $dom->createElement("REFERENCIA");
							$PRODUTOIDREFERENCIA = $dom->createElement("PRODUTOIDREFERENCIA", $value3["id_referencia"]);
							$ATIVO = $dom->createElement("ATIVO", $value3["ativo"]);
							$DISPONIVEL = $dom->createElement("DISPONIVEL", $value3["disponivel"]);
							$PRECODE = $dom->createElement("PRECODE", $value3["precode"]);
							$PRECOPOR = $dom->createElement("PRECOPOR", $value3["precopor"]);
							$FRETEMEDIO = $dom->createElement("FRETEMEDIO", $value3["fretemedio"]);
							$SALDO = $dom->createElement("SALDO", $value3["saldo"]);
							$CODIGOEAN = $dom->createElement("CODIGOEAN", $value3["codigoean"]);
							$CARACTERISTICAS = $dom->createElement("CARACTERISTICAS");
							$i=0;
							foreach ($value3["caracteristicas"]["nomeCaracteristica"] as $values4){
							$CARACTERISTICA = $dom->createElement("CARACTERISTICA");
							$NOME = $dom->createElement("NOME", $values4);
							$VALOR = $dom->createElement("VALOR", $value3["caracteristicas"]["valorCaracteristica"][$i]);
							$CARACTERISTICA->appendChild($NOME);
							$CARACTERISTICA->appendChild($VALOR);
							$CARACTERISTICAS->appendChild($CARACTERISTICA);
							$i++;
							}
							
							$IMAGENS = $dom->createElement("IMAGENS");
							
							$URLIMAGEM = $dom->createElement("URLIMAGEM", $this->urlCompleta.$value3["nomeArquivo"]);
							$IMAGENS->appendChild($URLIMAGEM);
							
							
							$REFERENCIA->appendChild($PRODUTOIDREFERENCIA);
							$REFERENCIA->appendChild($ATIVO);
							$REFERENCIA->appendChild($DISPONIVEL);
							$REFERENCIA->appendChild($PRECODE);
							$REFERENCIA->appendChild($PRECOPOR);
							$REFERENCIA->appendChild($FRETEMEDIO);
							$REFERENCIA->appendChild($SALDO);
							$REFERENCIA->appendChild($CODIGOEAN);
							$REFERENCIA->appendChild($CARACTERISTICAS);
							$REFERENCIA->appendChild($IMAGENS);
							
							$REFERENCIAS->appendChild($REFERENCIA);
							
							$REFERENCIA= $dom->createElement("REFERENCIA");
					}
					$PRODUTO->appendChild($REFERENCIAS);
					$LOJAS = $dom->createElement("LOJAS");
					$LOJA = $dom->createElement("LOJA");
					$ID = $dom->createElement("ID", "1");
					$NOME = $dom->createElement("NOME", $this->identificacaoParceiro);
					$LOJA->appendChild($ID);
					$LOJA->appendChild($NOME);
					$LOJAS->appendChild($LOJA);
					$PRODUTO->appendChild($LOJAS);
					
					$DEPARTAMENTOS = $dom->createElement("DEPARTAMENTOS");
					$ID = $dom->createElement("ID", "1");
					$NOME = $dom->createElement("NOME", "1");
					$IDPAI = $dom->createElement("IDPAI", "1");
					$NOMEPAI = $dom->createElement("NOMEPAI", "1");
					$DEPARTAMENTOS->appendChild($ID);
					$DEPARTAMENTOS->appendChild($NOME);
					$DEPARTAMENTOS->appendChild($IDPAI);
					$DEPARTAMENTOS->appendChild($NOMEPAI);
					$PRODUTO->appendChild($DEPARTAMENTOS);
			
					$PRODUTOS->appendChild($PRODUTO);
					
		
					
					$CATALOGO->appendChild($PRODUTOS);			
			}
			
			#adiciona o nÃ³ contato em (root) agenda
			$dom->appendChild($CATALOGO);
			
			 $this->_db->beginTransaction();
			try {
		   		//Salva arquivo na pasta local e dotz
				$this->validaCriaUploadDotzArquivo($dom,$layout);
				//$r->updateReferenciaInseridoDotz();
		    	$this->_db->commit();
		  
		 		
		} catch (Exception $e) {
		    $this->_db->rollBack();
		   throw new Exception($e->getMessage());
		  
		}

}
/*
 * Metodo salva arquivo na pasta loca e dotz
 * Valida nome arquivo
 * Adciona no historico de documentos
 * 
 */
public function validaCriaUploadDotzArquivo($dom,$layout){
	
	try {
	$dataAux = date("Ymd");
	$sequencial=$this->getRetornarSequencial($layout);
	
	if($sequencial<1){
		$sequencial=0;
	}
	$sequencial++;
	$nomeArquivo=$dataAux."_".$this->identificacaoParceiro."_".$layout."_".$sequencial.".XML";
	$this->validarNomeArquivoXML($nomeArquivo);
	
	$retornoAddArquivo=$this->addLogArquivosGerados($layout,"0",$nomeArquivo,$sequencial);
	# Para salvar o arquivo, descomente a linha
	
	$salvouLocal=$dom->save($this->diretorioEntradaLocal."/$nomeArquivo");
	if(!$salvouLocal){
		throw new Exception("Erro ao salvar arquivo na pasta ".$this->diretorioEntradaLocal."/$nomeArquivo");
	}
	//valida XSD
	$this->validarXSD($dom,"$layout.xsd");
	
	Zend_Registry::get('logger')->log("Salvou local=".$salvouLocal, Zend_Log::INFO);
	$this->upload($this->diretorioEntradaLocal."/$nomeArquivo",$nomeArquivo);
	
	
	
	} catch (Exception $e) {
		throw new Exception($e->getMessage());
	}
	
}

public function validarNomeArquivoXML($nomeArquivo){
	$er = '/(^[0-9]{8}\_TM1DISTRIBUIDORA\_(950X|955X|860X|861X|865X|870X|880X|710X|950x|955x|860x|861x|865x|870x|880x|710x)\_([0-9]+)\.(xml|XML))/';
	if(! preg_match($er, $nomeArquivo)) {
		Zend_Registry::get('logger')->log("Arquivo fora do padrÃ£o AAAAMMDD_IdentificaÃ§Ã£o do Parceiro_Tipo de Registro_Sequencial.EXTENSÃƒO", Zend_Log::INFO);
		throw new Exception("Arquivo fora do padrão AAAAMMDD_Identificação do Parceiro_Tipo de Registro_Sequencial.EXTENSÃƒO");
	}
}
/*
 * <?xml version="1.0" encoding="iso-8859-1"?>
<CATALOGO>
	<PRODUTOS>
		<PRODUTO>
			<PRODUTOID>84403</PRODUTOID>
			<REFERENCIAS>
				<REFERENCIA>
					<PRODUTOIDREFERENCIA>363361</PRODUTOIDREFERENCIA>
					<ATIVO>1</ATIVO>
					<PRECODE>82.42</PRECODE>
					<PRECOPOR>65.92</PRECOPOR>
					<FRETEMEDIO>10.00</FRETEMEDIO>
					<DISPONIVEL>1</DISPONIVEL>
					<SALDO>72</SALDO>
				</REFERENCIA>
			</REFERENCIAS>
			<LOJAS>
				<LOJA>
					<ID>500</ID>
					<NOME>Loja Sony</NOME>
				</LOJA>
			</LOJAS>
		</PRODUTO>
	</PRODUTOS>
</CATALOGO>

 * passa por paramentro a lista de ids com a referencia ao produto
 */
public function atualizarLayout950X($referencias){
            
		$this->_db->beginTransaction();

			$layout="950X";

			Zend_Registry::get('logger')->log("entrou layout gerarLayout955X", Zend_Log::INFO);
			$produto = new Application_Model_DbTable_Produto();
                        
			$listaProdutos=$produto->getProdutosAtualizadosXml($referencias);

			#versao do encoding xml
			$dom = new DOMDocument("1.0", "ISO-8859-1");


			#retirar os espacos em branco
			$dom->preserveWhiteSpace = false;


			#gerar o codigo
			$dom->formatOutput = true;


			#criando o nÃ³ principal (root)
			$CATALOGO = $dom->createElement("CATALOGO");
			
			#nÃ³ filho (contato)
                        $id_produto=0;
			$PRODUTOS = $dom->createElement("PRODUTOS");                        
			Zend_Registry::get('logger')->log("antes busca Novos produtos", Zend_Log::INFO);
			Zend_Registry::get('logger')->log($listaProdutos, Zend_Log::INFO);
		
			$r=new Application_Model_DbTable_Referencia();
			
			foreach($listaProdutos as $value)     {
			
				//$referenciaProduto=$produto->getProdutosNovosIdreferencia($value);
			
				$PRODUTO = $dom->createElement("PRODUTO");
				$PRODUTOID = $dom->createElement("PRODUTOID", $value["id_produto"]);
				
                                $NOMEPRODUTO = $dom->createElement("NOMEPRODUTO", $value["nome"]);
                                $FORNECEDOR = $dom->createElement("FORNECEDOR", $value["fk_fornecedor"]);
                                $DESCRICAO = $dom->createElement("DESCRICAO", $value["descricao"]);
                                $PALAVRASCHAVE = $dom->createElement("PALAVRASCHAVE");	
                                $palavrachave=explode(",",$value["palavrachave"]);
                                foreach ($palavrachave as $value2){
                                                $PALAVRA= $dom->createElement("PALAVRA",$value2);
                                                $PALAVRASCHAVE->appendChild($PALAVRA);
                                }
                                $URL= $dom->createElement("URL", $value["url"]);
                                $PRODUTO->appendChild($PRODUTOID);
                                $PRODUTO->appendChild($NOMEPRODUTO);
                                $PRODUTO->appendChild($FORNECEDOR);
                                $PRODUTO->appendChild($DESCRICAO);
                                $PRODUTO->appendChild($URL);
                                $PRODUTO->appendChild($PALAVRASCHAVE);
                               
				$REFERENCIAS = $dom->createElement("REFERENCIAS");
				
				foreach ($value["referencias"] as $value3){
							$r->updateReferenciaAtualizadoDotz ($value3["id_referencia"]);//atualiza referencia 
							$REFERENCIA = $dom->createElement("REFERENCIA");
							$PRODUTOIDREFERENCIA = $dom->createElement("PRODUTOIDREFERENCIA", $value3["id_referencia"]);
							$ATIVO = $dom->createElement("ATIVO", $value3["ativo"]);
							$DISPONIVEL = $dom->createElement("DISPONIVEL", $value3["disponivel"]);
							$PRECODE = $dom->createElement("PRECODE", $value3["precode"]);
							$PRECOPOR = $dom->createElement("PRECOPOR", $value3["precopor"]);
							$FRETEMEDIO = $dom->createElement("FRETEMEDIO", $value3["fretemedio"]);
							$SALDO = $dom->createElement("SALDO", $value3["saldo"]);
						
							$CODIGOEAN = $dom->createElement("CODIGOEAN", $value3["codigoean"]);
							$CARACTERISTICAS = $dom->createElement("CARACTERISTICAS");
							$i=0;
							foreach ($value3["caracteristicas"]["nomeCaracteristica"] as $values4){
							$CARACTERISTICA = $dom->createElement("CARACTERISTICA");
							$NOME = $dom->createElement("NOME", $values4);
							$VALOR = $dom->createElement("VALOR", $value3["caracteristicas"]["valorCaracteristica"][$i]);
							$CARACTERISTICA->appendChild($NOME);
							$CARACTERISTICA->appendChild($VALOR);
							$CARACTERISTICAS->appendChild($CARACTERISTICA);
							$i++;
							}
							
							$IMAGENS = $dom->createElement("IMAGENS");
							
							$URLIMAGEM = $dom->createElement("URLIMAGEM", $this->urlCompleta.$value3["nomeArquivo"]);
							$IMAGENS->appendChild($URLIMAGEM);

					$REFERENCIA->appendChild($PRODUTOIDREFERENCIA);
					$REFERENCIA->appendChild($ATIVO);
					$REFERENCIA->appendChild($DISPONIVEL);
					$REFERENCIA->appendChild($PRECODE);
					$REFERENCIA->appendChild($PRECOPOR);
					$REFERENCIA->appendChild($FRETEMEDIO);
					$REFERENCIA->appendChild($SALDO);
					
					$REFERENCIA->appendChild($CODIGOEAN);
					$REFERENCIA->appendChild($CARACTERISTICAS);
					$REFERENCIA->appendChild($IMAGENS);	




					$REFERENCIAS->appendChild($REFERENCIA);
						
					


					}
					$PRODUTO->appendChild($REFERENCIAS);
					$LOJAS = $dom->createElement("LOJAS");
					$LOJA = $dom->createElement("LOJA");
					$ID = $dom->createElement("ID", "1");
					$NOME = $dom->createElement("NOME", $this->identificacaoParceiro);
					$LOJA->appendChild($ID);
					$LOJA->appendChild($NOME);
					$LOJAS->appendChild($LOJA);
					$PRODUTO->appendChild($LOJAS);
					
					$DEPARTAMENTOS = $dom->createElement("DEPARTAMENTOS");
					$ID = $dom->createElement("ID", "1");
					$NOME = $dom->createElement("NOME", "1");
					$IDPAI = $dom->createElement("IDPAI", "1");
					$NOMEPAI = $dom->createElement("NOMEPAI", "1");
					$DEPARTAMENTOS->appendChild($ID);
					$DEPARTAMENTOS->appendChild($NOME);
					$DEPARTAMENTOS->appendChild($IDPAI);
					$DEPARTAMENTOS->appendChild($NOMEPAI);
					$PRODUTO->appendChild($DEPARTAMENTOS);
								
					$PRODUTOS->appendChild($PRODUTO);
					
                                        


					$CATALOGO->appendChild($PRODUTOS);

			}
			
			

			$dom->appendChild($CATALOGO);
				
			

			try {
				//Salva arquivo na pasta local e dotz
				$dom->saveXML();
				$this->validaCriaUploadDotzArquivo($dom,$layout);
				$r=new Application_Model_DbTable_Referencia();
				//$r->updateReferenciaInseridoDotz();
				$this->_db->commit();
				
				//$this->_db->rollBack();
				Zend_Registry::get('logger')->log("Atualizado com sucesso com sucesso", Zend_Log::INFO);


			} catch (Exception $e) {
				$this->_db->rollBack();
				throw new Exception($e->getMessage());
			
			}
			
					



	}
/*
Layout 870X - â€œRastreamento de Entregaâ€�
Exemplo do XML

<?xml version="1.0" encoding="iso-8859-1"?>
<RASTREAMENTO>
  <ARQUIVO>
    <VERSAO>1.0</VERSAO>
  </ARQUIVO>
  <PEDIDOS>
    <PEDIDO>
      <IDPEDIDODOTZ>84403</IDPEDIDODOTZ>
      <IDPEDIDOFORN>84403a</IDPEDIDOFORN>
      <ITENS>
        <ITEM>
          <IDITEMDOTZ>84404</IDITEMDOTZ>
          <IDITEMFORN>84404a</IDITEMFORN>
          <FRETE>10.00</FRETE>
          <PRECO>82.42</PRECO>
          <PESO>82.42</PESO>
          <NRRASTREIO></NRRASTREIO>
          <NFCOBRANCA>
            <NUMERONF>123</NUMERONF>
            <NUMEROLINHA>2</NUMEROLINHA>
          </NFCOBRANCA>
          <OCORRENCIAS>
            <OCORRENCIA>
              <OCORRENCIAID>5000</OCORRENCIAID>
              <DESCRICAO>Entregue</DESCRICAO>
              <DATAHORA>2011-01-01 00:00:00</DATAHORA>
              <FINAL>1</FINAL>
              <OBSERVACAO></OBSERVACAO>
            </OCORRENCIA>
          </OCORRENCIAS>
        </ITEM>
      </ITENS>
    </PEDIDO>
  </PEDIDOS>
</RASTREAMENTO>

*/
public function gerarLayout870X($fk_item){
	
	$this->criaConexao();
	$layout="870X";
			//Zend_Registry::get('logger')->log("gerarLayout870X", Zend_Log::INFO);
			$item= new Application_Model_DbTable_Item();
			$listaItem=$item->getItem($fk_item);
			$itemHasOcorrencia= new Application_Model_DbTable_ItemHasOcorrencia();
			$listaOcorrencias=$itemHasOcorrencia->getItemHasOcorrencias($fk_item);
			//Zend_Registry::get('logger')->log($listaOcorrencias, Zend_Log::INFO);
			//Zend_Registry::get('logger')->log($listaItem, Zend_Log::INFO);
			$produto = new Application_Model_DbTable_Produto();
			$listaProdutos=$produto->getProdutosNovosXml();

			#versao do encoding xml
			$dom = new DOMDocument("1.0", "ISO-8859-1");

			#retirar os espacos em branco
			$dom->preserveWhiteSpace = false;

			#gerar o codigo
			$dom->formatOutput = true;

			#criando o nÃ³ principal (root)
			$RASTREAMENTO = $dom->createElement("RASTREAMENTO");
			
			$ARQUIVO= $dom->createElement("ARQUIVO");
			$VERSAO= $dom->createElement("VERSAO","1.0");
			$ARQUIVO->appendChild($VERSAO);
			
			$RASTREAMENTO->appendChild($ARQUIVO);
			
			$PEDIDOS= $dom->createElement("PEDIDOS");
			$RASTREAMENTO->appendChild($PEDIDOS);
			$PEDIDO= $dom->createElement("PEDIDO");
			$PEDIDOS->appendChild($PEDIDO);
			print_r($listaItem);
			
			
			
			$pedido= new Application_Model_DbTable_Pedido();
    		$listaItensPedido=$pedido->getItemPedido($listaItem["fk_pedido"]);
    		print_r($listaItensPedido);
			$IDPEDIDODOTZ= $dom->createElement("IDPEDIDODOTZ",$listaItensPedido[0]["id_pedido_dotz"]);
    		$IDPEDIDOFORN= $dom->createElement("IDPEDIDOFORN",$listaItensPedido[0]["fk_pedido"]);
    		
			//$IDPEDIDODOTZ= $dom->createElement("IDPEDIDODOTZ",$listaItem["id_pedido_dotz"]);
			//$IDPEDIDOFORN= $dom->createElement("IDPEDIDOFORN",$listaItem["fk_pedido"]);
			
			$PEDIDO->appendChild($IDPEDIDODOTZ);
			$PEDIDO->appendChild($IDPEDIDOFORN);
			
			
			$ITENS= $dom->createElement("ITENS");
			$PEDIDO->appendChild($ITENS);
			$ITEM= $dom->createElement("ITEM");
			$ITENS->appendChild($ITEM);
			
			$IDITEMDOTZ= $dom->createElement("IDITEMDOTZ",$listaItem["itemid"]);
			$ITEM->appendChild($IDITEMDOTZ);
			
			$IDITEMFORN= $dom->createElement("IDITEMFORN",$listaItem["id_item"]);
			$ITEM->appendChild($IDITEMFORN);
			
			$FRETE= $dom->createElement("FRETE",$listaItem["frete"]);
			$ITEM->appendChild($FRETE);
			
			$PRECO= $dom->createElement("PRECO",$listaItem["preco"]);
			$ITEM->appendChild($PRECO);
			
			$PESO= $dom->createElement("PESO",$listaItem["peso"]);
			$ITEM->appendChild($PESO);
			
			$NRRASTREIO= $dom->createElement("NRRASTREIO",$listaItem["nr_rastreio"]);
			$ITEM->appendChild($NRRASTREIO);
			
			$NFCOBRANCA= $dom->createElement("NFCOBRANCA");
			
			$NUMERONF= $dom->createElement("NUMERONF",$listaItem["numero_nf"]);
			$NFCOBRANCA->appendChild($NUMERONF);
			
			$NUMEROLINHA= $dom->createElement("NUMEROLINHA",$listaItem["numero_linha_nf"]);
			$NFCOBRANCA->appendChild($NUMEROLINHA);
			
			$ITEM->appendChild($NFCOBRANCA);
			
			$OCORRENCIAS= $dom->createElement("OCORRENCIAS");
			$ITEM->appendChild($OCORRENCIAS);
			if(is_array($listaOcorrencias)){
			foreach ($listaOcorrencias as $value){
				Zend_Registry::get('logger')->log("DescriÃ§Ã£o=".$value["descricao"], Zend_Log::INFO);
				$OCORRENCIA= $dom->createElement("OCORRENCIA");
				$OCORRENCIAID= $dom->createElement("OCORRENCIAID",$value["id_ocorrencia"]);
				//$DESCRICAO= $dom->createElement("DESCRICAO",htmlentities($value["descricao"]));
				$DESCRICAO= $dom->createElement("DESCRICAO",$value["descricao"]);
				$OBSERVACAO= $dom->createElement("OBSERVACAO",$value["observacao"]);
				$DATAHORA= $dom->createElement("DATAHORA",$value["datahora"]);
				$FINAL= $dom->createElement("FINAL",$value["final"]);
				
				$OCORRENCIA->appendChild($OCORRENCIAID);
				$OCORRENCIA->appendChild($DESCRICAO);
				$OCORRENCIA->appendChild($DATAHORA);
				$OCORRENCIA->appendChild($FINAL);
				$OCORRENCIA->appendChild($OBSERVACAO);
				
				$OCORRENCIAS->appendChild($OCORRENCIA);
				
			}
			}
			
			$dom->appendChild($RASTREAMENTO);
			
			$this->_db->beginTransaction();
			try {
				//Salva arquivo na pasta local e dotz
				$this->validaCriaUploadDotzArquivo($dom,$layout);
				//Atualiza Dotz
				$item= new Application_Model_DbTable_Item();
				$item->atualizaItemDotz($fk_item, 1);
				//$r=new Application_Model_DbTable_Referencia();
				//$r->updateReferenciaInseridoDotz();
				$this->_db->commit();
				//$this->_db->rollBack();
				Zend_Registry::get('logger')->log("Atualizado com sucesso com sucesso", Zend_Log::INFO);
			} catch (Exception $e) {
				$this->_db->rollBack();
				throw new Exception($e->getMessage());
					
			}
			
			
			

		  
    }
    /*
     Layout 880X - â€œConciliaÃ§Ã£o de Nota Fiscalâ€�
    Exemplo do XML
    
  <?xml version="1.0" encoding="iso-8859-1"?>
<RASTREAMENTO>
  <ARQUIVO>
    <VERSAO>1.0</VERSAO>
  </ARQUIVO>
  <PEDIDOS>
    <PEDIDO>
      <IDPEDIDODOTZ>84403</IDPEDIDODOTZ>
      <IDPEDIDOFORN>84403a</IDPEDIDOFORN>
      <ITENS>
        <ITEM>
          <IDITEMDOTZ>84404</IDITEMDOTZ>
          <IDITEMFORN>84404a</IDITEMFORN>
          <FRETE>10.00</FRETE>
          <PRECO>82.42</PRECO>
          <NRRASTREIO></NRRASTREIO>
          <NFCOBRANCA>
            <NUMERONF>123</NUMERONF>
            <NUMEROLINHA>2</NUMEROLINHA>
          </NFCOBRANCA>
        </ITEM>
      </ITENS>
    </PEDIDO>
  </PEDIDOS>
</RASTREAMENTO>

    
    */
    public function gerarLayout880X($fk_pedido){
    
    	$this->criaConexao();
    	$layout="880X";
    	$pedido= new Application_Model_DbTable_Pedido();
    	$listaItensPedido=$pedido->getItemPedido($fk_pedido);
    	$item= new Application_Model_DbTable_Item();
    	//Zend_Registry::get('logger')->log("gerarLayout870X", Zend_Log::INFO);
    	
    	#versao do encoding xml
    	$dom = new DOMDocument("1.0", "ISO-8859-1");
    	
    	#retirar os espacos em branco
    	$dom->preserveWhiteSpace = false;
    	
    	#gerar o codigo
    	$dom->formatOutput = true;
    	
    	#criando o nÃ³ principal (root)
    	$RASTREAMENTO = $dom->createElement("RASTREAMENTO");
    	
    	$ARQUIVO= $dom->createElement("ARQUIVO");
    	$VERSAO= $dom->createElement("VERSAO","1.0");
    			$ARQUIVO->appendChild($VERSAO);
    	
    	$RASTREAMENTO->appendChild($ARQUIVO);
    	
    	$PEDIDOS= $dom->createElement("PEDIDOS");
    	$RASTREAMENTO->appendChild($PEDIDOS);
    	$PEDIDO= $dom->createElement("PEDIDO");
    	$PEDIDOS->appendChild($PEDIDO);
    	
    	$IDPEDIDODOTZ= $dom->createElement("IDPEDIDODOTZ",$listaItensPedido[0]["id_pedido_dotz"]);
    	$IDPEDIDOFORN= $dom->createElement("IDPEDIDOFORN",$listaItensPedido[0]["fk_pedido"]);
    	$PEDIDO->appendChild($IDPEDIDODOTZ);
    	$PEDIDO->appendChild($IDPEDIDOFORN);
    	
    	
    	$ITENS= $dom->createElement("ITENS");
    	$PEDIDO->appendChild($ITENS);
    	
    	foreach ($listaItensPedido as $value){
    		$listaItem=$item->getItem($value["id_item"]);
    		$itemHasOcorrencia= new Application_Model_DbTable_ItemHasOcorrencia();
    		$listaOcorrencias=$itemHasOcorrencia->getItemHasOcorrencias($fk_item);
    		//Zend_Registry::get('logger')->log($listaOcorrencias, Zend_Log::INFO);
    		//Zend_Registry::get('logger')->log($listaItem, Zend_Log::INFO);
    		$produto = new Application_Model_DbTable_Produto();
    		$listaProdutos=$produto->getProdutosNovosXml();
    		
    		 
    		$ITEM= $dom->createElement("ITEM");
    		$ITENS->appendChild($ITEM);
    		
    		$IDITEMDOTZ= $dom->createElement("IDITEMDOTZ",$listaItem["itemid"]);
    		$ITEM->appendChild($IDITEMDOTZ);
    		
    		$IDITEMFORN= $dom->createElement("IDITEMFORN",$listaItem["id_item"]);
    		$ITEM->appendChild($IDITEMFORN);
    		
    		$FRETE= $dom->createElement("FRETE",$listaItem["frete"]);
    		$ITEM->appendChild($FRETE);
    		
    		$PRECO= $dom->createElement("PRECO",$listaItem["preco"]);
    		$ITEM->appendChild($PRECO);
    		
    		$PESO= $dom->createElement("PESO",$listaItem["peso"]);
    		$ITEM->appendChild($PESO);
    		
    		$NRRASTREIO= $dom->createElement("NRRASTREIO",$listaItem["nr_rastreio"]);
    		$ITEM->appendChild($NRRASTREIO);
    		
    		$NFCOBRANCA= $dom->createElement("NFCOBRANCA");
    		
    		$NUMERONF= $dom->createElement("NUMERONF",$listaItem["numero_nf"]);
    		$NFCOBRANCA->appendChild($NUMERONF);
    		
    		$NUMEROLINHA= $dom->createElement("NUMEROLINHA",$listaItem["numero_linha_nf"]);
    		$NFCOBRANCA->appendChild($NUMEROLINHA);
    		
    		$ITEM->appendChild($NFCOBRANCA);
    	}
    	
    	
    		
    	
    	$dom->appendChild($RASTREAMENTO);
    		
    	$this->_db->beginTransaction();
    	try {
    		//Salva arquivo na pasta local e dotz
    		$this->validaCriaUploadDotzArquivo($dom,$layout);
    		//nota_fiscal
    		//Atualiza Dotz
    		$pedido= new Application_Model_DbTable_Pedido();
    		$pedido->atualizaNotaFiscal($fk_pedido);
    		//$item->atualizaItemDotz($fk_item, 1);
    		//$r=new Application_Model_DbTable_Referencia();
    		//$r->updateReferenciaInseridoDotz();
    		$this->_db->commit();
    		//$this->_db->rollBack();
    		Zend_Registry::get('logger')->log("Atualizado com sucesso com sucesso", Zend_Log::INFO);
    	} catch (Exception $e) {
    		$this->_db->rollBack();
    		throw new Exception($e->getMessage());
    			
    	}
    		
    		
    		
    
    
    }
    public function gerarConfirmacaoRecebimento($nomeArquivo,$listaPedido){
    	
		
    		$layout="865X";

			#versao do encoding xml
			$dom = new DOMDocument("1.0", "ISO-8859-1");
				
			#retirar os espacos em branco
			$dom->preserveWhiteSpace = false;
				
			#gerar o codigo
			$dom->formatOutput = true;
				
			#criando o nÃ³ principal (root)
			$RASTREAMENTO = $dom->createElement("RASTREAMENTO");
			$ARQUIVO= $dom->createElement("ARQUIVO");
			$VERSAO= $dom->createElement("VERSAO","1.0");
			$ARQUIVO->appendChild($VERSAO);
			$RASTREAMENTO->appendChild($ARQUIVO);
			$LOTE= $dom->createElement("LOTE");
			$NOMEARQUIVO= $dom->createElement("NOMEARQUIVO",$nomeArquivo);
			$LOTE->appendChild($NOMEARQUIVO);
			$RASTREAMENTO->appendChild($LOTE);
			
			$PEDIDOS= $dom->createElement("PEDIDOS");
			
			//$listaPedido = is_array($listaPedido) ? $listaPedido : array($listaPedido);
			//if (is_array($listaPedido)){
			$listaPedido=(array)$listaPedido;
			//Zend_Registry::get('logger')->log("listaPedido", Zend_Log::INFO);
			//Zend_Registry::get('logger')->log($listaPedido, Zend_Log::INFO);
			//$listaPedido = array_values($listaPedido);
			foreach ($listaPedido as $value){
				
				$IDPEDIDODOTZ= $dom->createElement("IDPEDIDODOTZ",$value["IDPEDIDODOTZ"]);
				$IDPEDIDOFORN= $dom->createElement("IDPEDIDOFORN",$value["IDPEDIDOFORN"]);
				$CODIGO= $dom->createElement("CODIGO",$value["CODIGO"]);
				$OBSERVACAO= $dom->createElement("OBSERVACAO",$value["OBSERVACAO"]);
				$PEDIDO= $dom->createElement("PEDIDO");
				$PEDIDO->appendChild($IDPEDIDODOTZ);
				$PEDIDO->appendChild($IDPEDIDOFORN);
				$PEDIDO->appendChild($CODIGO);
				$PEDIDO->appendChild($OBSERVACAO);
				$PEDIDOS->appendChild($PEDIDO);
			}
			//}
			
			
			$RASTREAMENTO->appendChild($PEDIDOS);
			
			#adiciona o nÃ³ contato em (root) agenda
			$dom->appendChild($RASTREAMENTO);	
			
			
			
			Zend_Registry::get('logger')->log("antes valida cria upl rastreamento", Zend_Log::INFO);
			
			
			try {
				//Salva arquivo na pasta local e dotz
				$this->validaCriaUploadDotzArquivo($dom,$layout);
				Zend_Registry::get('logger')->log("validaCriaUploadDotzArquivovalidaCriaUploadDotzArquivovalidaCriaUploadDotzArquivo", Zend_Log::INFO);
				//$r=new Application_Model_DbTable_Referencia();
				//$r->updateReferenciaInseridoDotz();
				//$this->_db->commit();
				
			} catch (Exception $e) {
				//$this->_db->rollBack();
				Zend_Registry::get('logger')->log("Erro ao gerar gerarConfirmacaoRecebimento", Zend_Log::INFO);
				throw new Exception($e->getMessage());
					
			}
			Zend_Registry::get('logger')->log("FIM gerarConfirmacaoRecebimento", Zend_Log::INFO);
			
			
		   		
		
    }
    public function validarXSD($xml,$xsd){
		libxml_use_internal_errors(true);
		/* Cria um novo objeto da classe DomDocument */
		//$objDom = new DomDocument();
		//$objRetorno=array();
		//$objRetorno["erro"]=0;
		//$objRetorno["mensagem"]="";
	//	$objDom->loadXML($xml->asXML());
		//$objDom->saveXML();
		/* Tenta validar os dados utilizando o arquivo XSD */
		if (!$xml->schemaValidate("xml_xsd/".$xsd)) {
		
		    /**
		     * Se nÃ£o foi possÃ­vel validar, vocÃª pode capturar
		     * todos os erros em um array
		     */
		    $arrayAllErrors = libxml_get_errors();
		   
		    /**
		     * Cada elemento do array $arrayAllErrors
		     * serÃ¡ um objeto do tipo LibXmlError
		     */
		     Zend_Registry::get('logger')->log($arrayAllErrors, Zend_Log::INFO);
		     $objRetorno["mensagem"]="Estrutura do XML inválida<br>";
		     foreach ($arrayAllErrors as $value){
		     	//Zend_Registry::get('logger')->log($value->message, Zend_Log::INFO);
		     	$objRetorno["mensagem"] = $objRetorno["mensagem"]."<br>".$value->message." Linha= ".$value->line;
				$objRetorno["erro"]=1;
		     }
		     throw new Exception($objRetorno["mensagem"] );
		     //return $objRetorno;
		   // print_r($arrayAllErrors);
		   
		} else {
		
		    /* XML validado! */
		    Zend_Registry::get('logger')->log("XML obedece ás regras definidas no arquivo XSD!", Zend_Log::INFO);
		    $objRetorno["mensagem"] ="XML obedece ás regras definidas no arquivo XSD!";
			$objRetorno["erro"]=0;
			
			return $objRetorno;
		   // echo "XML obedece Ã s regras definidas no arquivo XSD!";
		   
		}
    }
    public function buscaPedidos(){
    	
    	$layout="860X";
    	try {
    		$listaArquivosSaidaFTPDotz=$this->buscaArquivosPastaEntrada();
    		$path = $this->diretorioSaidaLocal."/";
    		$diretorio = dir($path);
    		$mensagemErro=array();
    		$possuiErro=0;
    		$possuiAlgumProcessado=0;
    		$possuiAlgumProcessadoErro=0;
    		$objArquivo= new Application_Model_DbTable_Arquivo();
    		Zend_Registry::get('logger')->log("Lista de Arquivos do diretório ".$path, Zend_Log::INFO);
    		Zend_Registry::get('logger')->log($listaArquivosSaidaFTPDotz, Zend_Log::INFO);
    		//Varre novos arquivos novos arquivos
    		if(count($listaArquivosSaidaFTPDotz)>0){
    		foreach ($listaArquivosSaidaFTPDotz as $value){
    			try {
    				$nomeArquivo=$value;
    				$procurar = "860X";
    				
    				if(strpos($nomeArquivo, $procurar)!==false){
    					$layout= "860X";
    				}else{
    					$layout= "861X";
    				}
    					
    				$xml = simplexml_load_file($this->diretorioSaidaLocal."/$value", 'SimpleXMLElement', LIBXML_NOCDATA);
    				if (!$objArquivo->existeArquivo($nomeArquivo)) {
    					$this->validarNomeArquivoXML($nomeArquivo);
    					$objDom = new DomDocument();
    					$objDom->loadXML($xml->asXML());
    					$objDom->saveXML();
    					$this->validarXSD($objDom,"$layout.xsd");
    					Zend_Registry::get('logger')->log("NÃ£o possui arquivo processado no banco de dados", Zend_Log::INFO);
    					$mensagemAux=$this->addPedido($xml,$nomeArquivo);
    					$mensagemErro[]="$nomeArquivo  ".$mensagemAux;
    					Zend_Registry::get('logger')->log("Adicionado pedido com sucesso", Zend_Log::INFO);
    				}else{
    					Zend_Registry::get('logger')->log("Existe arquivo processado no banco de dados", Zend_Log::INFO);
    						
    				}
    			
    				//$mensagemErro[]="$nomeArquivo  ".$e->getMessage();
    				//$mensagemErro=$mensagemErro."<br>";
    			
    			} catch (Exception $e) {
    				
    				/*$possuiErro=1;
    				if($e->getCode()=="3")
    					$possuiAlgumProcessado++;
    				else
    					$possuiAlgumProcessadoErro++;
    				*/
    				$mensagemErro[]="$nomeArquivo  ".$e->getMessage();
    				$arquivo = new Application_Model_DbTable_Arquivo();
    					
    				//$fk_arquivo=$arquivo->addArquivo($nomeArquivo, "xml");
    			
    			}
    		}
    	}else{
    		return "Não existe novos pedidos";
    	}
    		//while($nomeArquivo = $diretorio -> read()){
    		$mensagem="";
    		foreach ($mensagemErro as $value){
    			$mensagem=$mensagem."<br>".$value;
    		}
    		return $mensagem;
    		//throw new Exception ( $mensagem,$possuiErro);
    		
    	}
    	 catch (Exception $e) {
    		/*$possuiErro=1;
    		if($e->getCode()=="3")
    			$possuiAlgumProcessado++;
    		else
    			$possuiAlgumProcessadoErro++;
    	
    		$mensagemErro[]="$nomeArquivo  ".$e->getMessage();
    		$arquivo = new Application_Model_DbTable_Arquivo();
    			
    		$fk_arquivo=$arquivo->addArquivo($nomeArquivo, "xml");*/
    		//Zend_Registry::get('logger')->log($e->getMessage(), Zend_Log::INFO);
    		//Zend_Registry::get('logger')->log("Exception addpedido", Zend_Log::INFO);
    	}
    	/*
    	$layout="860X";
    	Zend_Registry::get('logger')->log("Busca Arquivos Servidor", Zend_Log::INFO);
    	
    	$listaArquivosSaidaFTPDotz=$this->buscaArquivosPastaEntrada();
    	Zend_Registry::get('logger')->log($listaArquivosSaidaFTPDotz, Zend_Log::INFO);
    	$path = $this->diretorioSaidaLocal."/"; 
    	$diretorio = dir($path); 
    	$mensagemErro=array();
    	$possuiErro=0;
    	$possuiAlgumProcessado=0;
    	$possuiAlgumProcessadoErro=0;
    	$objArquivo= new Application_Model_DbTable_Arquivo();
    	Zend_Registry::get('logger')->log("Lista de Arquivos do diretÃ³rio ".$path, Zend_Log::INFO);
    	while($nomeArquivo = $diretorio -> read()){ 
    		// retira "./" e "../" para que retorne apenas pastas e arquivos
    		Zend_Registry::get('logger')->log("while".$nomeArquivo, Zend_Log::INFO);
    		if ($nomeArquivo!="." && $nomeArquivo!=".."){
    			Zend_Registry::get('logger')->log("Entrou if", Zend_Log::INFO);
    			$xml = simplexml_load_file($this->diretorioSaidaLocal."/$nomeArquivo", 'SimpleXMLElement', LIBXML_NOCDATA);
    			try {
    				Zend_Registry::get('logger')->log($this->diretorioSaidaLocal."/$nomeArquivo", Zend_Log::INFO);
    				
    				if (!$objArquivo->existeArquivo($nomeArquivo)) {
    					$this->validarNomeArquivoXML($nomeArquivo);
    					$objDom = new DomDocument();
    					$objDom->loadXML($xml->asXML());
    					$objDom->saveXML();
    					$this->validarXSD($objDom,"$layout.xsd");
    					Zend_Registry::get('logger')->log("NÃ£o possui arquivo processado no banco de dados", Zend_Log::INFO);
    					$this->addPedido($objDom,$nomeArquivo);
    				}else{
    					Zend_Registry::get('logger')->log("Existe arquivo processado no banco de dados", Zend_Log::INFO);
    					
    				}
    				
    				//$mensagemErro[]="$nomeArquivo  ".$e->getMessage();
    				//$mensagemErro=$mensagemErro."<br>";
    				
    			} catch (Exception $e) {
    				$possuiErro=1;
    				if($e->getCode()=="3")
    					$possuiAlgumProcessado++;
    				else 
    					$possuiAlgumProcessadoErro++;
    				
    				$mensagemErro[]="$nomeArquivo  ".$e->getMessage();
    				$arquivo = new Application_Model_DbTable_Arquivo();
    					
    				$fk_arquivo=$arquivo->addArquivo($nomeArquivo, "xml");
    				//Zend_Registry::get('logger')->log($e->getMessage(), Zend_Log::INFO);
    				//Zend_Registry::get('logger')->log("Exception addpedido", Zend_Log::INFO);
    			}
    			//break;
    			//Zend_Registry::get('logger')->log($arquivo, Zend_Log::INFO);
    			
    		}
    	} 
    	$diretorio -> close();
    	Zend_Registry::get('logger')->log($mensagemErro, Zend_Log::INFO);
    	Zend_Registry::get('logger')->log("Mensagem de erro final depois que processou todos", Zend_Log::INFO);
    	Zend_Registry::get('logger')->log("possuiAlgumProcessado $possuiAlgumProcessado", Zend_Log::INFO);
    	Zend_Registry::get('logger')->log("possuiErro $possuiErro", Zend_Log::INFO);
    	if(!count($mensagemErro)){
    		if ($possuiAlgumProcessado>0){
    			if(!$possuiAlgumProcessadoErro==0){
    				$possuiErro=3;
    			}else{
    				$possuiErro=0;
    			}
    			
    		}
    		$mensagem="";
    		foreach ($mensagemErro as $value){
    			$mensagem=$mensagem."<br>".$value;
    		}
    		throw new Exception ( $mensagem,$possuiErro);
    	}*/
    	
    }
    public function addPedido($xml,$nomeArquivo){
    	$layout="860X";
    	$erroProcessamento=0;
    	$listaPedido=array();
    	$pedido=array();
    	$itens=array();
    	$auxPedido=array();
    	$destinatario= array();
    	$bdPedido= new Application_Model_DbTable_Pedido();
	    $bdItem= new Application_Model_DbTable_Item();
	    $bdDestinatario= new Application_Model_DbTable_Destinatario();
	    $mensagemRetorno="";
	    Zend_Registry::get('logger')->log($xml, Zend_Log::INFO);
	    Zend_Registry::get('logger')->log("Antes laÃ§o varrer pedido", Zend_Log::INFO);
		foreach ( $xml->PEDIDOS->PEDIDO as $p ) { // faz o loop nas tag item
			Zend_Registry::get('logger')->log("Entrou laÃ§o varrer pedido", Zend_Log::INFO);
			Zend_Registry::get('logger')->log($p, Zend_Log::INFO);
				try {
				$this->_db->beginTransaction ();
				$auxPedido ["IDPEDIDODOTZ"] = strval ($p->PEDIDOID );
				$auxPedido ["IDPEDIDOFORN"] = "";
				$auxPedido ["CODIGO"] = "1";
				$auxPedido ["OBSERVACAO"] = "Erro ao processar pedido de troca";
				//Zend_Registry::get('logger')->log($p, Zend_Log::INFO);
				$fk_pedido = $bdPedido->addPedido ( $p->PEDIDOID, $p->DATACRIACAO, $p->OBSERVACAO, $p->CANALPEDIDO, $p->TIPOCLIENTE, $p->PRIORIDADE );
				//Zend_Registry::get('logger')->log("addPedido", Zend_Log::INFO);
				foreach ( $p->ITENS->ITEM as $item ) { // faz o loop nas tag item
					
					$bdItem->addItem ( strval ( $item->PRODUTOIDDOTZ ), strval ( $item->PRECO ), strval ( $item->FRETE ), strval ( $item->QTDE ), strval ( $item->NOMEPRODUTO ), strval ( $item->ITEMID ), $fk_pedido, strval ( $item->PRODUTOIDFORN ) );
					//Zend_Registry::get('logger')->log("addItem", Zend_Log::INFO);
				}
				
				foreach ( $p->DESTINATARIO as $d ) { // faz o loop nas tag item
					
					$documento= strval ( $d->DOCUMENTO );
					$tipopessoa= strval ( $d->TIPOPESSOA );
					$nome= strval ( $d->NOME );
					$email = strval ( $d->EMAIL );
					$rua= strval ( $d->RUA );
					$numero = strval ( $d->NUMERO );
					$compl = strval ( $d->COMPL );
					$bairro= strval ( $d->BAIRRO );
					$cidade = strval ( $d->CIDADE );
					$estado= strval ( $d->UF );
					$cep = strval ( $d->CEP );
					$ddd = strval ( $d->DDD );
					$telefone = strval ( $d->TELEFONE );
					$pontoreferencia = strval ( $d->PONTOREFERENCIA );
					$codigoidnt = strval ( $d->CODIGOIDENT );

					$bdDestinatario->addDestinatario($documento, $tipopessoa, $nome, $email, $rua, $numero, $compl, $bairro, $cidade, $estado, $cep, $ddd, $telefone, $pontoreferencia, $codigoidnt,$fk_pedido);
					//Zend_Registry::get('logger')->log("addDestinatario", Zend_Log::INFO);
				}
				$auxPedido ["IDPEDIDOFORN"] = $fk_pedido;
				$auxPedido ["OBSERVACAO"] = "Pedido de troca processado com sucesso";
				$auxPedido ["CODIGO"] = "0";
				
				$listaPedido[]=$auxPedido;
				//$this->_db->rollBack ();
				$sequencial=$this->getRetornarSequencial($layout);
				
				if($sequencial<1){
					$sequencial=0;
				}
				$sequencial++;
				$retornoAddArquivo=$this->addLogArquivosGerados($layout,"0",$nomeArquivo,$sequencial);
				$this->_db->commit();
				//Zend_Registry::get('logger')->log("antes mensagemRetor", Zend_Log::INFO);
				$mensagemRetorno=$mensagemRetorno."<br>Pedido de troca processado com sucesso Número PEDIDO=$fk_pedido Número PEDIDO DOTZ=".strval ($p->PEDIDOID );
				//$this->_db->rollBack ();
				Zend_Registry::get('logger')->log("mensagemRetorno=$mensagemRetorno", Zend_Log::INFO);
				} catch ( Exception $e ) {
					$erroProcessamento=1;
					$auxPedido ["OBSERVACAO"] =$e->getMessage();
					$listaPedido[]=$auxPedido;
					$mensagemRetorno=$mensagemRetorno."<br>Erro ao processar pedido NÃºmero PEDIDO DOTZ=".strval ($p->PEDIDOID )." ".$e->getMessage();
					$this->_db->rollBack ();
					//Zend_Registry::get('logger')->log("ExceÃ§Ã£o".$e->getMessage(), Zend_Log::INFO);
					//throw new Exception ( $e->getMessage () );
				}
				
			}
			
    	
    	$this->gerarConfirmacaoRecebimento($nomeArquivo,$listaPedido);//
    	Zend_Registry::get('logger')->log("Adicionado com sucesso erroProcessamento =$erroProcessamento", Zend_Log::INFO);
    	Zend_Registry::get('logger')->log($listaPedido, Zend_Log::INFO);
    	if($erroProcessamento==1){
    		Zend_Registry::get('logger')->log("Erro no processamento de receber pedido".$mensagemRetorno, Zend_Log::INFO);
    		throw new Exception ( $mensagemRetorno,1);
    	}else{
    		Zend_Registry::get('logger')->log("Processado com pedido com sucesso".$mensagemRetorno, Zend_Log::INFO);
    		//throw new Exception ( $mensagemRetorno,3);
    		return $mensagemRetorno;
    	}
    	
   		} //fim do foreach


    
    public function deleteLogArquivosGerados ($id)
    {
        $this->delete('id_log_arquivos_gerados =' . (int) $id);
    }


}

