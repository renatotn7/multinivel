<?php
/**
*
* Classe para arredondar imagens 
*
**/

class Roud {
  /**
 * Cria cantos arredondados em uma foto
 * @param resource $img Resource de imagem criada pela biblioteca GD
 * @param int $raio Tamanho do raio da circunferencia
 * @param string $cor Cor em notacao hexadecimal #HHHHHH ou "transparente"
 * @return void
 */
function criar_cantos_arredondados($img, $raio = 10, $cor = 'transparente') {

    // Checar parametros
    if (!extension_loaded('gd')) {
        throw new RuntimeException('Biblioteca GD precisa ser carregada', 1);
    }
    if (!is_resource($img)) {
        throw new InvalidArgumentException('Imagem precisa ser um resouce da GD', 1);
    }
    if (!is_numeric($raio)) {
        throw new InvalidArgumentException('Raio precisa ser numerico', 2);
    }
    if (!preg_match('/^#([A-F\d]{2})([A-F\d]{2})([A-F\d]{2})$/i', $cor, $matches) && $cor != 'transparente') {
        throw new InvalidArgumentException('Cor precisa estar no formato #HHHHHH (H indica hexadecimal) ou valer "transparente"', 3);
    }

    // Reservar cor
    if ($matches) {
        $cor = array(
            'r' => hexdec($matches[1]),
            'g' => hexdec($matches[2]),
            'b' => hexdec($matches[3])
        );
        $index_cor = imagecolorallocate($img, $cor['r'], $cor['g'], $cor['b']);
    } else {
        imagealphablending($img, false);
        imagesavealpha($img, true);
        $index_cor = imagecolorallocatealpha($img, 0xFF, 0xFF, 0xFF, 127);
    }

    // Obter tamanho da imagem
    $w = imagesx($img);
    $h = imagesy($img);

    // Caminhar sobre o eixo Y nos diferentes cantos da imagem
    // e imprimir uma linha que vai da extremidade ate o cosseno do angulo percorrido
    $passo = 1 / $raio;
    for ($i = 0; $i <= $raio; $i++) {
        $seno = $passo * $i;
        $cosseno = sqrt(1 - pow($seno, 2));

        // Canto superior esquerdo
        $x1 = 0;
        $y1 = $raio - $i;
        $x2 = $raio - ($cosseno * $raio);
        $y2 = $y1;
        imageline($img, $x1, $y1, $x2, $y2, $index_cor);

        // Canto superior direito
        $x1 = $w - $raio + ($cosseno * $raio);
        $y1 = $raio - $i;
        $x2 = $w;
        $y2 = $y1;
        imageline($img, $x1, $y1, $x2, $y2, $index_cor);

        // Canto inferior esquerdo
        $x1 = 0;
        $y1 = $h - $raio + $i;
        $x2 = $raio - ($cosseno * $raio);
        $y2 = $y1;
        imageline($img, $x1, $y1, $x2, $y2, $index_cor);

        // Canto inferior direito
        $x1 = $w - $raio + ($cosseno * $raio);
        $y1 = $h - $raio + $i;
        $x2 = $w;
        $y2 = $y1;
        imageline($img, $x1, $y1, $x2, $y2, $index_cor);
    }
}
    
} 
?>