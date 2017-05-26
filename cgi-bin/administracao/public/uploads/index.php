<!DOCTYPE html>
<html>
    <head>
        <title>www.rafaelwendel.com - Upload de arquivos com barra de progresso</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script src="jquery.js" type="text/javascript"></script>
        <script src="jquery.form.js" type="text/javascript"></script>
        <script src="upload.js" type="text/javascript"></script>
    </head>
    <body>
        <h1><a href="http://www.rafaelwendel.com" target="blank">www.rafaelwendel.com</a> - Upload de arquivos com barra de progresso</h1>
        <form name="formUpload" id="formUpload" method="post">
          
            <label>Selecione o arquivo: <input type="file" name="arquivo" id="arquivo" size="45" /></label>
            <br />
            <progress value="0" max="100"></progress><span id="porcentagem">0%</span>
            <br />
            <input type="button" id="btnEnviar" value="Enviar Arquivo" />
        </form>
        <div id="resposta">
            
        </div>
    </body>
</html>

