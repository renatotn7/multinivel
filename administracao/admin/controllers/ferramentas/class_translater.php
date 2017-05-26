<?php

class class_translater {

    public static function traduzir($str = '', $lang = '') {
        $word = urlencode($str);
        $opcoes_traducao = array();
        if ($lang == 'en') {
            $url = 'https://translate.google.com.br/translate_a/single?client=t&sl=pt&tl=en&hl=pt-BR&dt=bd&dt=ex&dt=ld&dt=md&dt=qc&dt=rw&dt=rm&dt=ss&dt=t&dt=at&dt=sw&ie=UTF-8&oe=UTF-8&prev=btn&ssel=3&tsel=0&q=' . $word;
            $textTraduzido = file_get_contents($url);
        }

        if ($lang == 'es') {
            $url = 'https://translate.google.com.br/translate_a/single?client=t&sl=pt&tl=es&hl=pt-BR&dt=bd&dt=ex&dt=ld&dt=md&dt=qc&dt=rw&dt=rm&dt=ss&dt=t&dt=at&dt=sw&ie=UTF-8&oe=UTF-8&prev=btn&ssel=3&tsel=0&q=' . $word;
            $textTraduzido = file_get_contents($url);
        }
        $textTraduzido = explode('"', $textTraduzido);

        $opcoes_traducao[1] = $textTraduzido[1];
        $opcoes_traducao[2] = $textTraduzido[7];
        return $opcoes_traducao;
    }

}
