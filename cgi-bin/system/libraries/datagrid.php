<?php

/*
        $grid = new dataGrid();
        $grid->set_controle(base_url('index.php/ativacao_mensal/buscar'));
        $grid->coluna('di_id')->titulo('Número');
        $grid->coluna('di_nome')->titulo('Nome');
        $grid->coluna('di_usuario')->titulo('Algusto');
        $grid->coluna('di_sexo')->titulo('Sexo');
        $grid->coluna('di_rg')->titulo('Número DOC.');
        $grid->coluna('di_data_nascimento')->titulo('Data nascimento');
        $grid->totalRegistro(20);
        $grid->data($this->db->get('distribuidores', 20,$grid->paginaAtual())->result());
        $grid->grid();
        exit;

 */

class dataGrid {

    private $db;
    private $html;
    private $coluna = array();
    private $titulo = array();
    private $data;
    private $nomeParametroPagina = 'pagina';
    private $base_url = '';
    private $numeroRegistro = 10;
    private $filtro;
    private $cor_ceta = '#aaa';
    private $intervaloRegistro = 10;
    private $ordenacao = '';
    private $tableGrid;
    private $controle='';

    public function __construct() {
        $this->db = & get_instance()->db;
        if (function_exists('base_url')) {
            $this->base_url = base_url();
        }
        $this->tableGrid = rand(10000, 99999);
    }

    public function coluna($newColuna = "") {
        $this->coluna = array_merge($this->coluna, array($newColuna));
        return $this;
    }

    //Titulo do registro
    public function titulo($newTitulo = '') {
        $this->titulo = array_merge($this->titulo, array($newTitulo));
        return $this;
    }

    //Verificar depois o tipo de filtro.
    public function tipoFiltro($newTipo = 'text') {
        $this->filtro = array_merge($this->filtro, array($this->coluna => $newTipo));
        return $this;
    }

    //Coloca o nome od paramento da paginação
    public function nomeParamentoPaginacao($nomeParam = 'pagina') {
        $this->nomeParametroPagina = $nomeParam;
        return $this;
    }

    //seta o total de registros por página.
    public function totalRegistro($num = 0) {
        $this->numeroRegistro = $num;
        $this->intervaloRegistro = $num;
        return $this;
    }
    public function set_controle($newControle=''){
        $this->controle=$newControle;
        return $this;
    }

    /**
     * setando a base URL, porque pode ser usado em qualquer outro 
     * tipo de sistema não fica engessado.
     */
    public function set_url_base($url = '#') {
        $this->base_url = $url;
        return $this;
    }

    /**
     * Verifica a página atual.
     */
    public function paginaAtual() {
        $paginaAtual = 0;
        if (isset($_REQUEST[$this->nomeParametroPagina])) {
            //Não pode ficar negativo
            if ($_REQUEST[$this->nomeParametroPagina] < 0) {
                return 0;
            }

            $paginaAtual = $_REQUEST[$this->nomeParametroPagina];
        }
        return $paginaAtual;
    }

    /**
     * Retorna a póxima página
     */
    public function proximaPagina() {
        return $this->paginaAtual() + $this->numeroRegistro;
    }

    /**
     * retorna a página anterior 
     */
    public function paginaAnterior() {
        if ($this->paginaAtual() <= 0) {
            return 0;
        }

        if ($this->paginaAtual() - $this->numeroRegistro < 0) {
            return 0;
        }

        return $this->paginaAtual() - $this->numeroRegistro;
    }

    /**
     * calor da cor tem que ser hexadecimal
     * @param type $cor
     */
    public function set_corSetas($cor = '') {
        $this->cor_ceta = $cor;
    }

    /**
     * Coloca a informação do grid.
     * os dados do banco de dados.
     */
    public function data($datas = array()) {

        if (count($datas) > 0) {

            foreach ($datas as $data) {
                $this->data[] = $this->filter_data($data);
            }
        }
    }

    public function grid() {
        $this->css();
        $this->html = '<table id="grid-' . $this->tableGrid . '" class="table table-bordered">';
        $this->header();
        $this->body();
        $this->footer();
        $this->html.='</table>';

        echo $this->html;
    }

    /**
     * Cria o cabeçalho do data grid
     */
    protected function header() {
        $this->html.="<tr><thead>";
        foreach ($this->coluna as $key => $coluna) {
            $this->html.="<th><strong>"
                    . "{$this->titulo[$key]}"
                    . "<div class='seta-cima'></div><br/>"
                    . " <a  class='seta-baixo' href='" . $this->createOrdernacaoAsc($coluna) . "'></a>"
                    . "</strong></th>";
        }
        $this->html.="</thead></tr>";

        $this->html.="<tr><thead>";
        foreach ($this->coluna as $key => $coluna) {
            $this->html.="<th><input type='text' id='col-{$coluna}' name='{$coluna}' class='search-input'/></th>";
            $this->html.=$this->buscaCampoAjax($coluna,'');
        }
        $this->html.="</thead></tr>";

        return $this;
    }

    /**
     * roda pé do data grid.
     */
    protected function footer() {
        $this->html.='<tr><thead>';
        $this->html.="<th colspan='" . count($this->coluna) . "'>";
        //Paginação 
        $this->pagination();

        $this->html.="</th>";
        $this->html.='</thead></tr>';
    }

    /**
     * Cria o compor do grid.
     * os conteúdo da tabela
     */
    protected function body() {
      
        $this->html.='<tbody class="grid-body">';
        foreach ($this->data as $data) {
            $this->html.='<tr>';
            //Colunas do data grid
            foreach (array_keys($data) as $key) {
                $this->html.="<td>{$data[$key]}</td>";
            }
            $this->html.='</tr>';
        }
        $this->html.='</tbody>';
      
    }

    /**
     * Paginação do grid
     */
    protected function pagination() {
        $indice = 0;
        $this->html.="";
        $this->html.='<div class="pagination pagination-centered">';
        $this->html.='<ul>';
        $this->html.="<li><a href='{$this->base_url}?{$this->nomeParametroPagina}={$this->paginaAnterior()}'>«</a></li>";

        if (count($this->data) > $this->numeroRegistro) {
            $dividendo = count($this->data) / $this->numeroRegistro;
            $total_r = count($this->data) / $dividendo;
        } else {
            $total_r = count($this->data);
        }

        if ($this->paginaAtual() > ($this->intervaloRegistro - 1)) {
            $indice = $this->paginaAtual();
        }

        for ($i = 1; $i < $total_r; $i++) {
            $this->html.="<li class='" . ($this->paginaAtual() == $i ? 'active' : '') . "' ><a href='{$this->base_url}?{$this->nomeParametroPagina}=" . ($i + $indice) . "'>" . ($i + $indice) . "</a></li>";
        }

        $this->html.="<li><a href='{$this->base_url}?{$this->nomeParametroPagina}={$this->proximaPagina()}'>»</a></li>";
        $this->html.='</ul>';
        $this->html.="<br><i>Total:" . count($this->data) . "</i>";
        $this->html.='</div>';
    }

    /**
     * Oredenação das colunas.
     */
    protected function createOrdernacaoAsc($campo = '') {
        //Logica 
        $orderAsc = true;
        $url = $this->urlRequest();
        $ssl = $_SERVER['SERVER_PORT'] == 80 ? 'http://' : 'https://';

        return $ssl . $url['path'] . '?' . http_build_query(array_filter($_REQUEST)) . '&' . $campo . '[ordAsc]=' . $orderAsc;
    }

    protected function urlRequest() {

        $url = parse_url($_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);
        $path = $url['path'];
        $return = array();

//         verifica se a queri exite
        if (isset($url['query'])) {

            $urlExploded = explode("&", $url['query']);
            $return = array();

            foreach ($urlExploded as $param) {
                $explodedPar = explode("=", $param);
                $return[$explodedPar[0]] = $explodedPar[1];
            }
        }
        return array('path' => $path, 'query' => array_filter($return));
    }

    /**
     *  converte objeto em arrays
     * @param type $object
     * @return type
     */
    protected function objectToArray($object) {
        $arr = array();
        for ($i = 0; $i < count($object); $i++) {
            $arr[] = get_object_vars($object[$i]);
        }
        return $arr;
    }

    protected function filter_data($data = array()) {
        $dados_arr = array();
        $data = get_object_vars($data);
        foreach ($this->coluna as $key => $coluna) {
            if (array_key_exists($coluna, $data)) {
                $dados_arr[$coluna] = $data[$coluna];
            }
        }
        return $dados_arr;
    }

    /*
     * Estilo padrão de cor 
     */

    protected function css() {
        echo $css = "<style type='text/css'>
              .search-input{
                 width:95%;
                }
                .seta-cima {
               width: 0;
               height: 0;
               float: right;
               cursor:pointer;
               margin-top: -2PX;
               border-left: 10px solid transparent;
               border-right: 10px solid transparent;
               border-bottom: 10px solid #aaa;
               }
               .seta-direita {
               width: 0;
               height: 0;
               border-top: 10px solid transparent;
               border-bottom: 10px solid transparent;
               border-left: 10px solid {$this->cor_ceta};
               }
               .seta-baixo {
               width: 0;
               height: 0;
               float: right;
               cursor:pointer;
               margin-top: -9PX;
               border-left: 10px solid transparent;
               border-right: 10px solid transparent;
               border-top: 10px solid {$this->cor_ceta};
               }
               .seta-cima:hover{
                border-bottom: 10px solid #FFFDFD;
               }
               .seta-baixo:hover {
                border-top: 10px solid #FFFDFD;
               }
              .seta-esquerda {
               width: 0;
               height: 0;
               border-top: 10px solid transparent;
               border-bottom: 10px solid transparent;
               border-right:10px solid {$this->cor_ceta};
               }</style>";
        return $this;
    }

    protected function buscaCampoAjax($nomeCampo = '', $controle = '') {
        $ajax = "<script> $('#col-{$nomeCampo}').keydown(function(){"
                . "if($(this).val().length > 3 ){"
                . "         $.ajax({"
                . "          url:'{$this->controle}',"
                . "          data:{" . $nomeCampo . ":$('#{$nomeCampo}').val()},"
                . "          success:function(data){"
                . "          $('#grid-{$this->tableGrid}').find('.grid-body').html(data);"
                . "         }"
                . "     });"
                . " }"
                . "}); </script>";
                
        return $ajax;
    }

}
