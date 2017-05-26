<?php

$imagem_padrao = "data:image/png;base64,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";
$pr_id = $this->input->get('pr_id');
$imagens = $this->db->where('img_id_poduto', $pr_id)
        ->get('imagem_produto')
        ->result();

if (count($imagens) > 0) {
    foreach ($imagens as $imagen) {
    
        $imagem = base_url("/public/imagem/uploads-produtos/thumbs/{$imagen->img_nome}");
        
        if (!@file_get_contents($imagem)) {
            $imagem = $imagem_padrao;
        }
        echo "<li><img rel='{$imagen->img_id}' class='viewProduto' style='cursor: pointer;' data-src='holder.js/160x120' alt='160x120' src='" .$imagem . "' /></li>";
    }
}
