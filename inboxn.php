<!DOCTYPE html>
<html class="has-navbar-fixed-bottom">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="../css/bulma.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha256-eZrrJcwDc/3uDhsdt61sL2oOBY362qM3lon1gyExkL0=" crossorigin="anonymous" />
  <link rel="stylesheet" type="text/css" href="../css/inbox.css">
  <title>AlTr Networks Inbox</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha256-eZrrJcwDc/3uDhsdt61sL2oOBY362qM3lon1gyExkL0=" crossorigin="anonymous" />
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
  <!-- Bulma Version 0.6.0 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.6.0/css/bulma.min.css" integrity="sha256-HEtF7HLJZSC3Le1HcsWbz1hDYFPZCqDhZa9QsCgVUdw=" crossorigin="anonymous" />
  <link rel="stylesheet" type="text/css" href="../css/inbox.css">
</head>
<body>
  <div class="navbar is-info is-fixed-bottom is-hidden-tablet">
    <center>
    <a class="navbar-itemp">
      <img src="assets/inboxa.png" height="50" width="50">
    </a>
    <img src="assets/leer.png" height="50" width="50">
    <a class="nacbar-item" href="account.php">
      <img src="assets/settings.png" height="50" width="50">
    </a>
    <img src="assets/leer.png" height="50" width="50">
    <a class="nacbar-item" style="text-align: right;" href="logout.php">
      <img src="assets/logout.png" height="50" width="50">
    </a></center>
  </div>
  <?php
    session_start();
    //Überprüfen ob angemeldet
    if(!isset($_SESSION["altrnet"])){
      echo "<script>window.location.href = \"login.php\";</script>";
    }
    //Verbindung zu Datenbank aufbauen
    $servername = "Server";
    $database = "Database";
    $username = "Username";
    $password = "Password";
    // Create connection
    $con = mysqli_connect($servername, $username, $password, $database);
    // Check connection
    if (!$con) {
      die("Connection failed: " . mysqli_connect_error());
    }
    $ben = $_SESSION["altrnet"];
    //Überprüfen ob Nachricht gesendet werden soll
    if(isset($_GET["send"])){
      $messagesend = rawurlencode($_POST["messagew"]);
      $receveiversend = $_POST["receiverw"];
      $titlesend = $_POST["titlew"];
      $res = mysqli_query($con, "SELECT login.ben FROM login WHERE BINARY login.ben LIKE '%". $receveiversend ."'");
      if(empty($res)){
        echo "<script>window.location.href = \"inboxn.php?receivernexsist=". $receveiversend ."&message=". $messagesend ."&receiver=". $receveiversend ."&title=". rawurlencode($titlesend) ."\";</script>";
        die ();
      }
      if(mysqli_num_rows($res)==0){
        echo "<script>window.location.href = \"inboxn.php?receivernexsist=". $receveiversend ."&message=". $messagesend ."&receiver=". $receveiversend ."&title=". rawurlencode($titlesend) ."\";</script>";
        die ();
      }
      while ($dsatz = mysqli_fetch_assoc($res)) {
        echo $dsatz;
      }
      $num = mysqli_num_rows($res);
      if($num > 1) die ("<script>window.location.href = \"inboxn.php?receivererror&message=". $messagesend ."&receiver=". rawurlencode($receveiversend) ."&title=". rawurlencode($titlesend) ."\";</script>");

      $datesend = date("d").".".date("m").".".date("Y");
      //Nächste ID herausfinden
      $res2 = mysqli_query($con, "SELECT ". $receveiversend .".ID FROM ". $receveiversend ." WHERE ". $receveiversend .".ID = (SELECT MAX(". $receveiversend .".ID) FROM ". $receveiversend .")");
      $num = mysqli_num_rows($res2);
      if($num == 1){
        while ($dsatz = mysqli_fetch_assoc($res2)) {
          $idsend = intval($dsatz["ID"])+1;
        }
      }
      else{
        $idsend = 1;
      }
      if(mysqli_query($con, "INSERT INTO ". $receveiversend ." (`ID`, `title`, `sender`, `text`, `date`) VALUES ('". $idsend ."', '". $titlesend ."', '". $ben ."', '". $messagesend ."', '". $datesend ."');")){
        //Erfolgreiche Eintragung :))
        echo "<script type=\"text/javascript\">
                alert(\"Message send successfully.\");
                window.location = \"inboxn.php\";
              </script>
                ";
      }
      else{
        //Fehler bei Eintragung :(
        echo "<script type=\"text/javascript\">
                alert(\"Message not send. Error in signing. Please try again or contact support.\");
                window.location = \"inboxn.php\";
              </script>
                ";
      }
    }
    if(isset($_GET["receivernexsist"])||isset($_GET["receivererror"])){
      //Fehler anzeigen und alte Nachrichtenwerte einsetzen
      echo "<script type=\"text/javascript\">
              alert(\"Receiver does not exsist or there is an error. Please try again.\");
              window.location = \"inboxn.php?message=". $_GET["message"] ."&receiver=". $_GET["receiver"] ."&title=". $_GET["title"] ."\";
            </script>
              ";
    }
    if(isset($_GET["delete"])&&isset($_GET["site"])){
      //delete==zu löschendes Element; site == Seite wo es ist
      //Schauen welche ID Nachricht hat
      $position = intval($_GET["delete"])+((intval($_GET["site"])*10)-10)-1;
      $resID = mysqli_query($con, "SELECT ". $ben .".ID FROM ". $ben ." ORDER BY ". $ben .".ID DESC LIMIT ". $position ." , 1");
      //Schauen ob es den Eintrag gibt
      if(empty($resID)){
        die ("<script>window.location.href = \"inboxn.php?messagenexsist=". $position ."-". $ben ."&site=". $_GET["site"] ."\";</script>");
      }
      if(mysqli_num_rows($resID)==0){
        die ("<script>window.location.href = \"inboxn.php?messagenexsist=". $position ."-". $ben ."&site=". $_GET["site"] ."\";</script>");
      }
      //Eintrag holen
      while ($dsatz = mysqli_fetch_assoc($resID)) {
        $ID = $dsatz["ID"];
      }
      //Nachricht löschen und schauen ob erfolgreich
      if(mysqli_query($con, "DELETE FROM ". $ben ." WHERE ". $ben .".ID = ". $ID)){
        //Frohe Kunde mitteilen
        echo "<script type=\"text/javascript\">
                alert(\"Message deleted successfully!\");
              </script>";
      }
      else{
        //Fehler mitteilen
        echo "<script type=\"text/javascript\">
                alert(\"Message could not be deleted. Please try again or contact the support.\");
                window.location = \"inboxn.php?site=". $_GET["site"] ."\";
              </script>
                ";
      }
    }
  ?>
  <div class="navbar is-info is-hoverable">
    <div class="container">
      <div class="navbar-brand">
        <a class="navbar-item" href="inbox.php">
          <img src="assets/logow.png">
        </a>
      </div>
      <div class="navbar-end is-hidden-mobile">
        <div class="navbar-item has-dropdown is-hoverable is-info">
          <a class="navbar-link">
            Account
          </a>

          <div class="navbar-dropdown is-info">
            <div class="navbar-item">
              <?php echo "Hello&nbsp;<strong>". $_SESSION["altrnet"] ."</strong>" ?>
            </div>
            <hr class="navbar-divider">
            <a class="navbar-item is-active">
              Inbox
            </a>
            <a class="navbar-item" href="account.php">
              Account&nbsp;Settings
            </a>
            <hr class="navbar-divider">
            <a class="navbar-item" href="logout.php">
              Logout
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="columns">
    <aside class="column is-2 aside hero ">
      <div>
        <div class="compose has-text-centered">
          <a class="button is-danger is-block is-bold" onclick="return writeMessage()">
            <span class="compose">Write</span>
          </a>
        </div>
        <div class="main">
          <a href="#" class="item active"><span class="icon"><i class="fa fa-inbox"></i></span><span class="name">Inbox</span></a>
          <a href="#" class="item"><span class="icon"><i class="fa fa-star"></i></span><span class="name">Starred</span></a>
          <a href="#" class="item"><span class="icon"><i class="fa fa-envelope-o"></i></span><span class="name">Sent Mail</span></a>
        </div>
      </div>
    </aside>
    <div class="column is-5 aside is-fullheight">
      <br>
      <div class="container">
        <div class="action-buttons">
          <!--Seitenzahlen-->
          <div class="control is-grouped pg">
            <div class="title">
              <?php
                $res3 = mysqli_query($con, "SELECT * FROM ".$ben);
                //Schauen ob Ergebnis nicht leer
                if(empty($res3)){
                  //Leer, also Warnung umgehen >:)
                  $num = 0;
                }
                else{
                  //Passt, da nicht leer
                  $num = mysqli_num_rows($res3);
                }
                $seiten=1;
                while ($num > 10){
                  $seiten=$seiten+1;
                  $num=$num-10;
                }
                if(isset($_GET["site"])){
                  $seite = intval($_GET["site"]);
                }
                else {
                  $seite = 1;
                }
                if($seite>$seiten){
                  $seite = $seiten;
                }
                echo $seite." von ". $seiten;
                unset($res3);
               ?>
            </div>
            <?php
              if($seite>1){
                $seitez = $seite-1;
                echo "<a class=\"button is-link\" href=\"?site=". $seitez ."\"><i class=\"fa fa-chevron-left\"></i></a>";
              }
              else {
                echo "<a class=\"button is-link\"><i class=\"fa fa-chevron-left\"></i></a>";
              }
              if($seite<$seiten){
                $seitew = $seite+1;
                echo "<a class=\"button is-link\" href =\"?site=". $seitew ."\"><i class=\"fa fa-chevron-right\"></i></a>";
              }
              else {
                echo "<a class=\"button is-link\"><i class=\"fa fa-chevron-right\"></i></a>";
              }
            ?>
          </div>
          <div class="control is-grouped">
            <a class="button is-medium is-white" href=""><i class="fa fa-refresh"></i></a>
          </div>
          <br><br>
        </div>
      </div>
      <!--Nachrichten-->
      <?php
        $max = $seite*10;
        //Startet dort mit zählen
        $min = $max-10;

        //Variabeln für Javascript mit Platzhaltern
        $sender = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
        $title = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
        $datum = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
        $text = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
        $res4 = mysqli_query($con, "SELECT ". $ben .".title, ". $ben .".sender, ". $ben .".date, ". $ben .".text FROM ". $ben ." ORDER BY ". $ben .".ID DESC LIMIT ". $min .", 10");

        if(!empty($res4)){
          $num = mysqli_num_rows($res4);
          $i = 0;
          while ($dsatz = mysqli_fetch_assoc($res4)) {
            $sender[$i] = $dsatz["sender"];
            $title[$i] = $dsatz["title"];
            $datum[$i] = $dsatz["date"];
            $text[$i] = $dsatz["text"];
            $i = $i+1;
          }
        }

        //JavaScript für später; nachricht ist die Nummer der Nachricht wobei die erste angezeigte Nachricht = 1 ist und so weiter bis 10
        //BEACHTE bei Arrays: STARTEn BEI 0
        echo "
          <script type=\"text/javascript\">
            function openMessage(nachricht){
              if(parseInt(nachricht) < 11 && parseInt(nachricht) > 0){
                switch(parseInt(nachricht)){
                  case 1:
                  document.getElementById('titel').innerHTML = \"". $title[0] ."\";
                  document.getElementById('sender').innerHTML = \"". $sender[0] ."\";
                  document.getElementById('date').innerHTML = \"". $datum[0] ."\";
                  document.getElementById('text').innerHTML = decodeURIComponent(\"". $text[0] ."\");
                  break;
                  case 2:
                  document.getElementById('titel').innerHTML = \"". $title[1] ."\";
                  document.getElementById('sender').innerHTML = \"". $sender[1] ."\";
                  document.getElementById('date').innerHTML = \"". $datum[1] ."\";
                  document.getElementById('text').innerHTML = decodeURIComponent(\"". $text[1] ."\");
                  break;
                  case 3:
                  document.getElementById('titel').innerHTML = \"". $title[2] ."\";
                  document.getElementById('sender').innerHTML = \"". $sender[2] ."\";
                  document.getElementById('date').innerHTML = \"". $datum[2] ."\";
                  document.getElementById('text').innerHTML = decodeURIComponent(\"". $text[2] ."\");
                  break;
                  case 4:
                  document.getElementById('titel').innerHTML = \"". $title[3] ."\";
                  document.getElementById('sender').innerHTML = \"". $sender[3] ."\";
                  document.getElementById('date').innerHTML = \"". $datum[3] ."\";
                  document.getElementById('text').innerHTML = decodeURIComponent(\"". $text[3] ."\");
                  break;
                  case 5:
                  document.getElementById('titel').innerHTML = \"". $title[4] ."\";
                  document.getElementById('sender').innerHTML = \"". $sender[4] ."\";
                  document.getElementById('date').innerHTML = \"". $datum[4] ."\";
                  document.getElementById('text').innerHTML = decodeURIComponent(\"". $text[4] ."\");
                  break;
                  case 6:
                  document.getElementById('titel').innerHTML = \"". $title[5] ."\";
                  document.getElementById('sender').innerHTML = \"". $sender[5] ."\";
                  document.getElementById('date').innerHTML = \"". $datum[5] ."\";
                  document.getElementById('text').innerHTML = decodeURIComponent(\"". $text[5] ."\");
                  break;
                  case 7:
                  document.getElementById('titel').innerHTML = \"". $title[6] ."\";
                  document.getElementById('sender').innerHTML = \"". $sender[6] ."\";
                  document.getElementById('date').innerHTML = \"". $datum[6] ."\";
                  document.getElementById('text').innerHTML = decodeURIComponent(\"". $text[6] ."\");
                  break;
                  case 8:
                  document.getElementById('titel').innerHTML = \"". $title[7] ."\";
                  document.getElementById('sender').innerHTML = \"". $sender[7] ."\";
                  document.getElementById('date').innerHTML = \"". $datum[7] ."\";
                  document.getElementById('text').innerHTML = decodeURIComponent(\"". $text[7] ."\");
                  break;
                  case 9:
                  document.getElementById('titel').innerHTML = \"". $title[8] ."\";
                  document.getElementById('sender').innerHTML = \"". $sender[8] ."\";
                  document.getElementById('date').innerHTML = \"". $datum[8] ."\";
                  document.getElementById('text').innerHTML = decodeURIComponent(\"". $text[8] ."\");
                  break;
                  case 10:
                  document.getElementById('titel').innerHTML = \"". $title[9] ."\";
                  document.getElementById('sender').innerHTML = \"". $sender[9] ."\";
                  document.getElementById('date').innerHTML = \"". $datum[9] ."\";
                  document.getElementById('text').innerHTML = decodeURIComponent(\"". $text[9] ."\");
                  break;
                }
                document.getElementById('writebox').style.display = \"none\";
                document.getElementById('box').style.display = \"inline\";
                var box = document.getElementById('box');
                box.scrollIntoView();
                return true;
              }
              else{
                return false;
              }
            }
            function writeMessage(){
              document.getElementById('box').style.display = \"none\";
              document.getElementById('writebox').style.display = \"inline\";
              var wbox = document.getElementById('writebox');
              wbox.scrollIntoView();
              return true;
            }
            function checkInput(){
              if (document.forms[0].receiverw.value==\"\" || document.forms[0].messagew.value==\"\" || document.forms[0].titlew.value==\"\"){
                if (document.forms[0].receiverw.value==\"\"){
                  document.getElementById('receiverw').className = \"input is-danger\";
                }
                if (document.forms[0].messagew.value==\"\"){
                  document.getElementById('messagew').className = \"textarea is-danger\";
                }
                if (document.forms[0].titlew.value==\"\"){
                  document.getElementById('titlew').className = \"input is-danger\";
                }
                return false;
              }
              else{
                return true;
              }
            }
            function Delete(){
              if(confirm('Are you sure you want to delete the message?')){
                  return true;
              }
              else{
                  return false;
              }
            }
          </script>

        ";
        //Schauen ob es Nachrichten gibt
        $res5 = mysqli_query($con, "SELECT ". $ben .".title, ". $ben .".sender, ". $ben .".date FROM ". $ben ." ORDER BY ". $ben .".ID DESC LIMIT ". $min .", 10");
        if(empty($res5)){
          echo "<div class=\"box\">
                  <div class=\"content\">
                    <p>
                      <strong>Sie haben leider keine Nachrichten</strong>
                    </p>
                  </div>
                </div>";
        }
        else {
          if(mysqli_num_rows($res5)==0){
            echo "<div class=\"box\">
                    <div class=\"content\">
                      <p>
                        <strong>Sie haben leider keine Nachrichten</strong>
                      </p>
                    </div>
                  </div>";
          }
          else{
            $i = 1;
            if(isset($_GET["site"])){
              $seite = intval($_GET["site"]);
            }
            else{
              $seite = 1;
            }
            while ($dsatz = mysqli_fetch_assoc($res5)) {
              echo "<div class=\"box\">
                      <div class=\"content\">
                        <p>
                          <a onclick=\"return openMessage('". $i ."');\">". $dsatz["title"] ."</a>
                          <br><strong>From:</strong>". $dsatz["sender"] ." <strong>Date:</strong>". $dsatz["date"] ."  <a href=\"inboxn.php?delete=". $i ."&site=". $seite ."\" class=\"button is-white is-small\" onclick=\"return Delete('inboxn.php?delete=". $i ."&site=". $seite ."')\"><i class=\"fa fa-trash-o\"></i></a>
                        </p>
                      </div>
                    </div>";
              $i = $i+1;
            }
          }
        }
      ?>
    </div>
    <div class="column is-5 hero is-fullheight is-light">
      <div style="display:none" id="box"><br><!--display:none macht das Element unsichtbar(Magie). Mit display:inline ist es wieder sichtbar-->
        <div class="box">
          <div class="content">
              <h3 id="titel"></h3>
              <strong>From:</strong><i id="sender"></i>  <strong>Date:</strong><i id="date"></i>
              <br><div id="text"></div>
          </div>
        </div>
      </div>
      <?php
        //Schauen ob vorher geschriebenes mitgegeben wurde
        if(isset($_GET["message"])&&isset($_GET["receiver"])&&isset($_GET["title"])){
          echo"<div id=\"writebox\" style=\"display:inline\"><br>
                <div class=\"box\">
                  <form action=\"?send\" method=\"post\">
                      <div class=\"content\"><h3>Message</h3></div>
                      <strong>Receiver:</strong><input class=\"input\" type=\"text\" placeholder=\"Receiver of message\" pattern=\"^[a-zA-Z][a-zA-Z0-9-_]{2,12}$\" name=\"receiverw\" id=\"receiverw\" maxlength=\"12\" value=\"". rawurldecode($_GET["receiver"]) ."\">
                      <br><strong>Title:</strong><input class=\"input\" type=\"text\" placeholder=\"Message title\" name=\"titlew\" id=\"titlew\" maxlength=\"100\" value=\"". rawurldecode($_GET["title"]) ."\">
                      <br><strong>Text:</strong><textarea class=\"textarea\" type=\"text\" placeholder= \"Your message\" rows=\"5\" name=\"messagew\" id=\"messagew\" maxlength=\"1000\" value=\"". rawurldecode($_GET["message"]) ."\">". rawurldecode($_GET["message"]) ."</textarea>
                      <br><button class=\"button is-block is-info\" type = \"submit\" onclick=\"return checkInput()\">Submit</button>
                  </form>
                </div>
              </div>";
        }
        else {
          echo "<div id=\"writebox\" style=\"display:none\"><br>
                  <div class=\"box\">
                    <form action=\"?send\" method=\"post\">
                        <div class=\"content\"><h3>Message</h3></div>
                        <strong>Receiver:</strong><input class=\"input\" type=\"text\" placeholder=\"Receiver of message\" pattern=\"^[a-zA-Z][a-zA-Z0-9-_]{2,12}$\" name=\"receiverw\" id=\"receiverw\" max=\"12\" maxlength=\"12\">
                        <br><strong>Title:</strong><input class=\"input\" type=\"text\" placeholder=\"Message title\" name=\"titlew\" id=\"titlew\" max=\"100\" maxlength=\"100\">
                        <br><strong>Text:</strong><textarea class=\"textarea\" type=\"text\" placeholder=\"Your message\" rows=\"5\" name=\"messagew\" id=\"messagew\" max=\"1000\" maxlength=\"1000\"></textarea>
                        <br><button class=\"button is-block is-info\" type = \"submit\" onclick=\"return checkInput()\">Submit</button>
                    </form>
                  </div>
                </div>";
        }
      ?>
    </div>
  </div>
  <?php
    mysqli_close($con);
   ?>
</body>
</html>
