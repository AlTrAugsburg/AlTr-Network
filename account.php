<!DOCTYPE html>
<html class="has-navbar-fixed-bottom">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="../css/bulma.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha256-eZrrJcwDc/3uDhsdt61sL2oOBY362qM3lon1gyExkL0=" crossorigin="anonymous" />
  <link rel="stylesheet" type="text/css" href="../css/inbox.css">
  <title>AlTr Networks Settings</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha256-eZrrJcwDc/3uDhsdt61sL2oOBY362qM3lon1gyExkL0=" crossorigin="anonymous" />
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
  <!-- Bulma Version 0.6.0 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.6.0/css/bulma.min.css" integrity="sha256-HEtF7HLJZSC3Le1HcsWbz1hDYFPZCqDhZa9QsCgVUdw=" crossorigin="anonymous" />
  <link rel="stylesheet" type="text/css" href="../css/inbox.css">
</head>
<body>
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
    if (isset($_GET["save"])) {
      if(!isset($_POST["email"])||!isset($_POST["pass"])){
        die ("<script>window.location.href = \"account.php\";</script>");
      }
      //E-Mail aus DB holen
      $res = mysqli_query($con, "SELECT login.email FROM login WHERE BINARY login.ben LIKE '%". $_SESSION["altrnet"] ."'");
      if(empty($res)){
        echo "<script>alert(\"Error with database. Please contact the support.\"); window.location.href = \"account.php\";</script>";
        die ();
      }
      if(mysqli_num_rows($res)==0){
        echo "<script>alert(\"Error with database. Please contact the support.\"); window.location.href = \"account.php\";</script>";
        die ();
      }
      while ($dsatz = mysqli_fetch_assoc($res)) {
        $mailDB = $dsatz["email"];
      }
      unset($res);
      //Schauen ob E-Mail oder Passwort verändert wurden(Obwohl vorher schon durch JS ausgeschlossen(Sicher ist sicher))
      if($mailDB == $_POST["email"] && $_POST["pass"] == "Password1"){
        //Fallls keine Änderung zurück zur normalen account setting Seite
        echo "<script>window.location.href = \"account.php\";</script>";
        die ();
      }
      else {
        //Passwort holen
        $res = mysqli_query($con, "SELECT login.pass FROM login WHERE BINARY login.ben LIKE '%". $_SESSION["altrnet"] ."'");
        if(empty($res)){
          echo "<script>alert(\"Error with database. Please contact the support.\"); window.location.href = \"account.php\";</script>";
          die ();
        }
        if(mysqli_num_rows($res)==0){
          echo "<script>alert(\"Error with database. Please contact the support.\"); window.location.href = \"account.php\";</script>";
          die ();
        }
        while ($dsatz = mysqli_fetch_assoc($res)) {
          $passDB = $dsatz["pass"];
        }
        unset($res);
        //Schauen ob Passwortänderung vorgenommen wurde
        if($mailDB == $_POST["email"] && $passDB == $_POST["pass"]){
          //Falls nicht zurück zur normalen Seite
          echo "<script>window.location.href = \"account.php\";</script>";
          die ();
        }
        else {
          //ID für später herausfinden, damit leichter neue Daten eingetragen werden können
          $res = mysqli_query($con, "SELECT login.ID FROM login WHERE BINARY login.ben = '". $_SESSION["altrnet"] ."'");
          //Schauen ob Ergebnis
          if(empty($res)){
            //Fehler DB
            echo "<script type=\"text/javascript\">
                    alert(\"Error with database. Please contact the support.\");
                    window.location = \"index.html\";
                  </script>";
            die();
          }
          if(mysqli_num_rows($res)==0){
            //Fehler DB
            echo "<script type=\"text/javascript\">
                    alert(\"Error with database. Please contact the support.\");
                    window.location = \"index.html\";
                  </script>";
            die();
          }
          if(mysqli_num_rows($res)!=1){
            //Fehler DB
            echo "<script type=\"text/javascript\">
                    alert(\"Error with database. Please contact the support.\");
                    window.location = \"index.html\";
                  </script>";
            die();
          }
          //Das eine Ergebnis auslesen
          while ($dsatz = mysqli_fetch_assoc($res)) {
            //Die BenutzerID rauslesen
            $ID = intval($dsatz["ID"]);
          }
          unset($res);
          //Überprüfen ob E-Mail geändert wurde
          if($mailDB != $_POST["email"]){
            //Schauen ob E-Mailadresse schon verwendet wurde
            $res = mysqli_query($con, "SELECT login.email FROM login WHERE BINARY login.email = '". $_POST["email"] ."'");
            //Schauen ob Ergebnis
            if(!empty($res)){
              if(mysqli_num_rows($res)!=0){
                //E-Mailadresse schon verwendet also zurückschicken
                echo "<script type=\"text/javascript\">
                        alert(\"E-Mailadress already used. Please use another one.\");
                        window.location = \"account.php\";
                      </script>";
                die();
              }
            }
            unset($res);
            //Geändert -> neue E-Mail eintragen
            if(!mysqli_query($con, "UPDATE login SET login.email = '". $_POST["email"] ."' WHERE login.ID ='". $ID ."'")){
              //Es ist was schiefgelaufen also Fehlermeldung
              echo "<script type=\"text/javascript\">
                      alert(\"Error with inserting new E-Mail. Please contct the support.\");
                      window.location = \"index.html\";
                    </script>";
              die();
            }
            else {
              //Account deaktievieren
              if(!mysqli_query($con, "UPDATE login SET checked = '0' WHERE login.ID = '". $ID ."'")){
                echo "<script type=\"text/javascript\">
                        alert(\"Error with deactivation. Please contact the support.\");
                        window.location = \"index.html\";
                      </script>";
                die();
              }
              //Zufälligen Code generieren
              $aCharacters = str_split("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890");
              for ($sRandomString = '', $i = 0; $i < 50; $i++){
                $sRandomString .= $aCharacters[array_rand($aCharacters)];
              }
              //$beCode == Bestätigungscode
              $beCode = $sRandomString;
              //beCode eintragen
              if(!mysqli_query($con, "INSERT INTO ActivationCodes (username, activationCode) VALUES ('". $_SESSION["altrnet"] ."', '". $beCode ."');")){
                //Fehlerwarnung
                echo "<script type=\"text/javascript\">
                        alert(\"Error with ActivationCode. Please contact the support.\");
                        window.location = \"index.php\";
                      </script>";
                die();
              }
              // Bestätigungs-E-Mail senden
              //Empfängersetzen
              $empfaenger  = $_POST["email"];

              // Betreff
              $betreff = "Confirm your new E-Mailadress for your AlTr Network account";

              // Nachricht
              $nachricht = "
              <html>
              <head>
                <title>Confirm your new E-Mailadress for your AlTr Network account</title>
              </head>
              <body>
                <img src=\"http://altr.hol.es/assets/logo_lang.png\" alt=\"This graphic can not be shown.\"><br>
                Dear user,<br><br>
                You changed your E-Mailadress. Please <a href=\"http://yourdomain.com/activate.php?account=". rawurlencode($_POST["ben"]) ."&code=". $beCode ."&mailChanged\">click</a> here to confirm your new E-Mailadress and to activate your account. If the link doesn't work, please use the URL below.<br><br>
                http://yourdomain.com/activate.php?account=". rawurlencode($_POST["ben"]) ."&code=". $beCode ."&mailChanged<br><br>
                Sincerely<br><br>
                AlTr Networks
              </body>
              </html>
              ";

              //'Content-type'-Header wird gesetzt, da HTML E-Mail
              $header  = 'MIME-Version: 1.0' . "\r\n";
              $header .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

              // zusätzliche Header
              $header .= "To: ". $_POST["ben"] ." <". $_POST["email"] .">" . "\r\n";
              $header .= "From: AlTr Networks <no-reply@yourdomain.com>" . "\r\n";

              // verschicke die E-Mail
              mail($empfaenger, $betreff, $nachricht, $header);
              //Merken, dass neue E-Mailadresse gesetzt wurde
              $newMail = true;
            }
          }
          if($passDB != $_POST["pass"] || $_POST["pass"] != "Password1"){
            //Passwort geändert -> neues Passwort eintragen
            if(!mysqli_query($con, "UPDATE login SET login.pass = MD5('". $_POST["pass"] ."') WHERE login.ID ='". $ID ."'")){
              //Es ist was schiefgelaufen also Fehlermeldung
              echo "<script type=\"text/javascript\">
                      alert(\"Error with inserting new E-Mail. Please contct the support.\");
                      window.location = \"index.html\";
                    </script>";
              die();
            }
            else {
              //Merken, dass neues Passwort gesetzt wurde
              $newPass = true;
            }
          }
          //Schauen ob neue E-Mailadresse UND neues Passwort
          if(isset($newMail)&&isset($newPass)){
            session_destroy();
            echo "<script type=\"text/javascript\">
                    alert(\"New E-Mailadress and new password set. Your account will be deactivated until you confirmed your new E-Mailadress. Confirmationlink was sent to your new E-Mailadress.\");
                    window.location = \"index.html\";
                  </script>";
            die();
          }
          //Schauen ob NUR neue E-Mailadresse
          if(isset($newMail)&&!isset($newPass)){
            session_destroy();
            echo "<script type=\"text/javascript\">
                    alert(\"New E-Mailadress set. Your account will be deactivated until you confirmed your new E-Mailadress. Confirmationlink was sent to your new E-Mailadress.\");
                    window.location = \"index.html\";
                  </script>";
            die();
          }
          //Schauen ob NUR neues Passwort
          if(!isset($newMail)&&isset($newPass)){
            echo "<script type=\"text/javascript\">
                    alert(\"New password set.\");
                    window.location = \"account.php\";
                  </script>";
            die();
          }
          //Feritg(Hoffentlich :)) )
        }
      }
    }
  ?>
  <div class="navbar is-info is-fixed-bottom is-hidden-tablet">
    <center>
    <a class="navbar-itemp" href="inboxn.php">
      <img src="assets/inbox.png" height="50" width="50">
    </a>
    <img src="assets/leer.png" height="50" width="50">
    <a class="nacbar-item">
      <img src="assets/settingsa.png" height="50" width="50">
    </a>
    <img src="assets/leer.png" height="50" width="50">
    <a class="nacbar-item" style="text-align: right;" href="logout.php">
      <img src="assets/logout.png" height="50" width="50">
    </a></center>
  </div>
  <div class="navbar is-info">
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
            <div class="navbar-item is-active">
              <?php echo "Hello&nbsp;<strong>". $_SESSION["altrnet"] ."</strong>" ?>
            </div>
            <hr class="navbar-divider">
            <a class="navbar-item is-hidden-mobile" href="inboxn.php">
              Inbox
            </a>
            <a class="navbar-item is-active is-hidden-tablet" href="inboxn.php">
              Inbox
            </a>
            <a class="navbar-item  is-active">
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
    <div class="column is-2 aside hero"></div>
    <div class="column is-8 aside is-fullheight">
      <br>
      <div class="content">
        <h3>&nbsp;<strong><u>Account settings</u></strong></h3>
      </div>
      <div class="">
        <form action="account.php?save" method="post">
          <div class="field">
            <div class="columns">
              <div class="column is-1">
                <strong>&nbsp;Name:</strong>
              </div>
              <div class="column is-5">
                <?php echo "<input class=\"input is-normal\" value=\"". $_SESSION["altrnet"] ."\" disabled>"; ?>
              </div>
            </div>
          </div>
          <div class="field">
            <div class="columns">
              <div class="column is-1">
                <strong>&nbsp;E-mail:</strong>
              </div>
              <div class="column is-5">
                <?php
                  //E-Mailadresse abrufen
                  $res = mysqli_query($con, "SELECT login.email FROM login WHERE BINARY login.ben LIKE '%". $_SESSION["altrnet"] ."'");
                  if(empty($res)){
                    die ("<script>
                            alert(\"Error with E-Mailadress. Please contact support.\");
                            window.location.href = \"login.php\";
                          </script>");
                  }
                  if(mysqli_num_rows($res)==0){
                    die ("<script>
                            alert(\"Error with E-Mailadress. Please contact support.\");
                            window.location.href = \"login.php\";
                          </script>");
                  }
                  while ($dsatz = mysqli_fetch_assoc($res)) {
                    $mail = $dsatz["email"];
                  }
                  //E-Mailadresse anzeigen
                  echo"<input class=\"input is-normal\" id=\"email\" name=\"email\" type=\"email\" value=\"". $mail ."\" required>";
                ?>
              </div>
            </div>
          </div>
          <div class="field">
            <div class="columns">
              <div class="column is-1">
                <strong>&nbsp;Password:</strong>
              </div>
              <div class="column is-5">
                <?php echo "<input class=\"input is-normal\" name=\"pass\" id=\"pass\" type=\"password\" pattern=\"(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$\"  value=\"Password1\" required>"; ?>
              </div>
            </div>
          </div>
          <div class="field" id="confirmPassword" style="display:none">
            <div class="columns">
              <div class="column is-1">
                <strong>&nbsp;Confirm it:</strong>
              </div>
              <div class="column is-5">
                <?php echo "<input class=\"input is-normal\" id=\"confirmPass\" type=\"password\" pattern=\"(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$\"  value=\"\" >"; ?>
              </div>
            </div>
          </div>
          <br>
          <?php
            echo"<script type=\"text/javascript\">
                  function checkchanges(){
                    if(document.forms[0].email.value==\"\"||document.forms[0].pass.value==\"\"){
                      if (document.forms[0].email.value==\"\"){
                        document.getElementById('email').className = \"input is-danger\";
                      }
                      if (document.forms[0].pass.value==\"\"){
                        document.getElementById('pass').className = \"input is-danger\";
                      }
                      return false;
                    }
                    else {
                      if(document.forms[0].pass.value!=\"Password1\"){
                        if(document.getElementById('confirmPassword').style.display == \"none\"){
                          document.getElementById('confirmPassword').style.display = \"inline\";
                          return false;
                        }
                        else{
                          if(document.forms[0].pass.value==document.forms[0].confirmPass.value){
                            alert(\"Password will be changed.\");
                            return true;
                          }
                          else {
                            alert(\"The confirmed password is wrong.\");
                            document.getElementById('confirmPass').className = \"input is-danger\";
                            return false;
                          }
                        }
                      }
                      else{
                        if(document.forms[0].email.value!=\"". $mail ."\"){
                          alert(\"E-Mail will be changed.\");
                          return true;
                        }
                        else{
                          return false;
                        }
                      }
                    }
                  }
                </script>";
          ?>
          <button class="button is-block is-info is-normal" onclick="return checkchanges()" type ="submit">Save changes</button>
        </form>
      </div>
    </div>
    <div class="column is-2 hero is-fullheight is-light"></div>
  </div>

</body>
</html>
