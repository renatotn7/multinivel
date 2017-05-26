<?php

set_time_limit(0);
$localizar = array('Â£','â‚¬', 'Ãƒ', 'Ã±', 'Ã‰', 'Ãµ', 'Ã“', 'Ã¢', 'Ãª', 'Ã§Ã£', 'Ãº', 'Ã£', 'Âº', 'Ã©', 'Ã¡', 'Ã*', 'Ã³', 'Ã§', 'Ã«', 'Ã¬', 'Ã¹', 'Ã´', 'Ã‡', 'Åž', 'Ã');
$substituir = array('£','€', 'Ã', 'ñ', 'É', 'õ', 'Ó', 'â', 'ê', 'çã', 'ú', 'ã', 'º', 'é', 'á', 'í', 'ó', 'ç', 'ë', 'ì', 'ù', 'ô', 'Ç', 'ª', 'á');
$procurar_tipo_tabela = array('varchar', 'char', 'text', 'enum', 'string', 'blob');
$ignorar_tabela = array('historico_acesso', 'auditoria_geral');
$especifica_tabela = array('moedas');


echo "<table><tr>";
echo "<td>Localizar</td>";
echo "<td>Substituir</td>";
echo "</tr>";

foreach ($localizar as $key => $localizar_value) {
    echo '<tr>';
    echo "<td>{$localizar_value}</td>";
    echo "<td>{$substituir[$key]}</td>";
    echo '</tr>';
}
echo "</table>";

echo "Localizando e substituindo...\n\n\n";

$c = @mysql_connect('localhost', 'soffce_shopgold', 'v4QILtIEtO3453') or die($msg[0]);
@mysql_select_db("soffce_Nossa Empresa", $c) or die($msg[1]);

// get all tables
//$tables = @mysql_list_tables("portalSafe");
$tables = "SELECT table_name FROM information_schema.tables
 WHERE table_schema = 'soffce_Nossa Empresa'
 "
// "and table_name='{$_REQUEST['table']}' "
        . " order  by table_name desc ;";

$execTables = @mysql_query($tables, $c);

while ($t = @mysql_fetch_array($execTables, MYSQL_NUM)) {

    echo "<h2>RODANDO PARA TABELA: " . $t[0] . "</h2><br>";
    $tabela = $t[0];
    $select = "SELECT * FROM $t[0];";
    $execSelect = @mysql_query($select, $c);

    //Vai rodar na tabela especifica
    if (count($especifica_tabela) > 0) {
        if (!in_array($t[0], $especifica_tabela)) {
            continue;
        }
    }

    $registro_afetado = 0;
    while ($r = @mysql_fetch_array($execSelect, MYSQL_NUM)) {
        // search and destroy
        for ($i = 0; $i < count($r); $i++) {
            $meta = @mysql_fetch_field($execSelect, $i);
            //Verifica se o tipo da tabela é o solicitado.

            if (!in_array($meta->type, $procurar_tipo_tabela)) {
                continue;
            }

            if (in_array($meta->name, $ignorar_tabela)) {
                continue;
            }

            //Realizando a substituição.
            $o = str_replace($localizar, $substituir, $r[$i]);
            if ($r[$i] == $o) {
                continue;
            }

            $tName = $meta->name;
            $update = "UPDATE {$tabela} SET $tName='$o' WHERE {$tName}='{$r[$i]}';";
            $execUpdate = @mysql_query($update, $c);

            $registro_afetado++;
            echo "<br>->$r[$i]<br>->$o<br>";
        }
    }
    echo "Registro Afetados:" . $registro_afetado . "<br/>";
}
?>