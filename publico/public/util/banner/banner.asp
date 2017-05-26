

        <link href="banner/style.css" rel="stylesheet" type="text/css" />

        <link href="banner/DDSlider.css" rel="stylesheet" type="text/css" />

        

        <script type="text/javascript" src="banner/js/jquery.easing.1.3.js"></script>

        <script type="text/javascript" src="banner/js/jquery.DDSlider.min.js"></script>

        <script type="text/javascript">
        $(document).ready(function() {

            

            $('#yourSliderId').DDSlider({

				

				nextSlide: '.slider_arrow_right',

				prevSlide: '.slider_arrow_left',

				selector: '.slider_selector'
			});
        });

        </script>


        <script type="text/javascript">



  var _gaq = _gaq || [];

  _gaq.push(['_setAccount', 'UA-16586676-1']);

  _gaq.push(['_setDomainName', 'none']);

  _gaq.push(['_setAllowLinker', true]);

  _gaq.push(['_trackPageview']);



  (function() {

    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;

    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';

    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);

  })();



</script>



          <ul id="yourSliderId">

   <%
				Set ban = Server.CreateObject("adodb.recordset")

                sqlBan = "SELECT * FROM banners WHERE direcao = 2"
                ban.Open sqlBan,conexao,3
				while NOT ban.EOF
				%>
                <li title="squareOutMoving"><a href="<%=ban("link")%>"><img src="<%=ban("imagem")%>" width="100%" height="420px" alt="img" /></a></li>

     <%
	ban.movenext
	wend
	%>
  
  </ul>

   <div id="container">
    <ul class="slider_selector"></ul>
    </div>

          

