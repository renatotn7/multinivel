<%

If Session("usuario_admin") <> "logado"  or Session("id_usuario") = "" Then

response.redirect "../admin/login.asp"

End If

%>

<%



Response.Expires=0

Response.Buffer = TRUE

Response.Clear

'Response.BinaryWrite(Request.BinaryRead(Request.TotalBytes))

byteCount = Request.TotalBytes

'Response.BinaryWrite(Request.BinaryRead(varByteCount))



RequestBin = Request.BinaryRead(byteCount)

Dim UploadRequest

Set UploadRequest = CreateObject("Scripting.Dictionary")



'chama a função para baixar o arquivo

BuildUploadRequest  RequestBin



email = UploadRequest.Item("email").Item("Value")



contentType = UploadRequest.Item("blob").Item("ContentType")

filepathname = UploadRequest.Item("blob").Item("FileName")

filename =Right(filepathname,Len(filepathname)-InstrRev(filepathname,"\"))

value = UploadRequest.Item("blob").Item("Value")



ArquivoNome = Value



'AQUI É O SERVEROBJECT PARA TRATAR ARQUIVOS TEXTO!!!!!

 Set ScriptObject = Server.CreateObject("Scripting.FileSystemObject")



'Cria e preenche o arquivo com os dados enviados

 pathEnd = Len(Server.mappath(Request.ServerVariables("PATH_INFO")))-14

 dim strLogPath 

 dim pos

 

 pos = instrRev(Request.ServerVariables("PATH_INFO"),"/",-1)

 

 strLogPath = left(Request.ServerVariables("PATH_INFO"),pos)

 dim strpath

 strPath = Request.ServerVariables("SERVER_NAME")  & strLogpath

 Set MyFile = ScriptObject.CreateTextFile(Server.MapPath(FileName))

 

 For i = 1 to LenB(value)

	 MyFile.Write chr(AscB(MidB(value,i,1)))

 Next

 

 MyFile.Close





%>

      <!--#include file="upload.asp"--> </font>

<%



response.redirect "/admin/geral.asp?link=Alterarlogo&mensagem=A logo foi alterada com sucesso!"

%>