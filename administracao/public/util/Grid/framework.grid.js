
function grid(url,title,width,height){
	
	/*
	*Testa se foi imformado o width e height
	*/
	width = width==undefined?'700':width;
	height = height==undefined?'450':height;
	title = title==undefined?'MEU TITULO':title;

	/*
	  *Gerar um ID para DIV GRID
	*/	 
	 var id_grid = 'grid_id_'+$('.my-grid').size();
	 
	 /*
	  *Criar e configurar DIV GRID
	*/
	 var div = $("<div>");
	 div.addClass('my-grid');
	 div.attr('id',id_grid);

	 /*
	  *Criar e configurar HEADER
	*/	 
	 var head = $('<div>');
	 head.addClass('my-grid-title');
	 head.html(title);

	 /*
	  *Criar e configurar BOTÃO FECHAR
	*/		 
	 var botao_html = "<div class='my-grid-close' onclick=\"fechar_grid('#"+id_grid+"')\">X</a>";
	 
	 /*
	  *Criar e configurar CONTENTE
	*/		 
	 var div_content = $('<div>');
	 div_content.addClass('my-grid-content');
	 div_content.css('height',height);
	 div_content.css('width',width);
	 
	 

	 
	 div_content.html("<div class='grid-loading'>Carregando...</div>");
	 /*
	  *Atribui content a div e mostra ela na tela
	*/		  
	 div.html(div_content); 
	 $("body").append(div);
	 
	 var LeftPosition = $(div).css('width') ? (parseInt($(div).css('width'))-width)/2 : 0;
	 var TopPosition = $(div).css('height') ? (parseInt($(div).css('height'))-height)/2 : 0;
	 
	 div_content.css('top',TopPosition);
	 div_content.css('left',LeftPosition);
	 
	 $.ajax({
		 url:url,
		 type:'GET',
		 dataType:'html',
		 success:function(dataHtml){
			 div_content.html(head);
			 div_content.append(botao_html);
			 div_content.append("<div class='my-grid-content-conteudo' style='height:"+(height-50)+"px;'>"+dataHtml+"</div>");
			 
			 
			 },
		error:function(){
			div_content.html(head);
			div_content.append(botao_html);
			div_content.append('<div class="grid-error">Desculpa ocorreu um erro ao carregar.</div>');
			}	 
		 });
	 
	 
	}
	
	
function fechar_grid(_id){
	$(_id).remove();
	}	
