function grid(url){

    
	 $.ajax({
		 url:url,
		 type:'GET',
		 dataType:'html',
		 success:function(dataHtml){
			  $("#conteudo-grid").html(dataHtml);
			 },
		error:function(){
			alert('Desculpa ocorreu um erro ao carregar.');
			}	 
		 });
	 
	 
	}
	
	
function fechar_grid(_id){
	$(_id).remove();
	}	
