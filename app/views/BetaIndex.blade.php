
<!doctype html>
<html >
<style type="text/css">
  
</style>
<head>
    <meta charset="UTF-8">
    {{ HTML::script('assets/js/lib/jquery-1.8.3.min.js') }}
    {{ HTML::script('assets/js/lib/bootstrap.min.js') }}
    {{ HTML::style('assets/css/lib/bootstrap.min.css') }}

    <style type="text/css">
      /*   @font-face {
            font-family: 'cursive';
            src: url('assets/css/segoepr.ttf');
        }
      */
        header{
          background-color: #FACAD6;
          margin: 0;
          padding: 0;
          position: fixed;
          text-align: center;
          top: 0;
          width: 100%;
        }
        footer{
          background-color: #FACAD6;
          text-align: center;
          width: 100%;
          font-size: 18px;
        }
        a{
          font-size: 18px;
        }
        a:hover{
          text-decoration: none;
          cursor: pointer;
        }
        header ul li {
          display: inline-block;
          padding: 15px 30px;
        }
        ul{
          margin: 0px;
          padding: 0;
          list-style: none;
        }

        #home{
          padding-top: 150px;
          color: rgba(0, 31, 255, 0.87);
          font-size: 18px;
          text-align: center;
          width: 100%;
          height: 750px;
          background-image: url(assets/img/back.jpg);
        }
        #logo{
          font-size: 30px;
        }

        #inputEmail{
          padding-top: 100px;
        }

        #howItWork > div{
          padding-top: 100px;
        }

        #contactUs{
          padding-top: 20px;
        }

        #map{
          width:100%;
          height:495px;
          background-image: url(assets/img/map.jpg);
        }
        body{
          font-family: cursive;
        }
    </style>

    <script type="text/javascript">
      $(function(){
          $("#subscribe").click(function(){
            // $.post("/mail.php",{email:"49882194@qq.com"},function(data){
            //   console.log(data);
            // }); 
            $.ajax({
              type: "POST",
              url: "PushMailController/addSubscriber",
              data: "email="+$("input[name='email']").val(),
              success: function(msg){
                console.log(msg);
              }
            });
          });
      });
    </script>
    
</head>
<body>
  <header>
    <div>
        <ul>
          <li><a href="#home">Home</a></li>
          <li><a href="#howItWork">How it work</a></li>
          <li><a href="#contactUs">Contact us</a></li>
        </ul>
    </div>
  </header>

  <div id="home">
    <div>
        <div>
          <span id="logo">Coozilla</span><br>
          <br>
          Coozilla is a new Internet platform.<br>
          It can help you find out about the programmer's job.<br>
          And you can also release associated with programmers Recruitment Information<br>
        </div>
        
        <div id="inputEmail">  
          Register Your Interest <br>
          <br>
          <div class="row">
              <div class="col-xs-4"></div>
              <div class="col-xs-3">
                <input type="email" name="email" class="form-control" placeholder="Enter your email" size='40'>
              </div>  
              <a class="btn btn-danger" id="subscribe" style="float:left">Subscribe</a>
          </div>
        </div>
    </div>
  </div>

  <div id="howItWork">
     <div>
        <center><span style="font-size:50px;">How it work</span></center>
        <div class="row" style="padding-top:170px;">
          <div class="col-xs-1"></div>
          <div style="font-size:30px" class="col-xs-5">
            Post a job with your account.<br>
            Then we according to the site registered users,<br>
            Automatic matching suitable for programmers<br>
            Finally, send email to inform you<br>
          </div>

          <div style="font-size:30px" class="col-xs-5">
            Register for an account, fill out your resume.<br>
            Then we according to the site registered users,<br>
            Automatic matching suitable for programmers<br>
            Finally, send email to inform you<br>
          </div>
        </div>
     </div>
  </div>

  <br>
  <div id="contactUs">
    <br><br>
    <div class="row" id="map">
      <div class="col-xs-1"></div>
      <div class="col-xs-5">
        
      </div>
      <div class="col-xs-4" style="font-size:16px;color:rgba(0, 31, 255, 0.87)">
        Address:Haidian district of Beijing shangdi information road 15 financial trade building, 1103<br>
        Mail:business@trht.com.cn<br>
        Telephone:+86-010-62988619<br>
      </div>
    </div>
  </div>
  <br>

  <footer>
    <br>
    <div>
       Copyright @coozilla 2013 All rights reserved.<br>
       <a href="">About us</a>
    </div>
  </footer>

</body>
</html>