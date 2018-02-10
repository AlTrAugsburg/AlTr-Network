<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AlTr Networks Sign up</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha256-eZrrJcwDc/3uDhsdt61sL2oOBY362qM3lon1gyExkL0=" crossorigin="anonymous" />
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
  <!-- Bulma Version 0.6.0 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.6.0/css/bulma.min.css" integrity="sha256-HEtF7HLJZSC3Le1HcsWbz1hDYFPZCqDhZa9QsCgVUdw=" crossorigin="anonymous" />
  <link rel="stylesheet" type="text/css" href="../css/login.css">
</head>
<body>
  <?php
    //Verbindung zu Datenbank aufbauen
    $servername = "Server";
    $database = "Database";
    $username = "User";
    $password = "Password";
    // Create connection
    $con = mysqli_connect($servername, $username, $password, $database);
    // Check connection
    if (!$con) {
      die("Connection failed: " . mysqli_connect_error());
    }
    //Schauen ob Daten mitgegeben
    if(isset($_GET["send"])){
      //Schauen ob Daten dabei sind
      if(!isset($_POST["email"])||!isset($_POST["ben"])||!isset($_POST["pass"])||!isset($_POST["passbes"])){
        //Fals nicht Seite ohne ?send aufrufen
        echo "<script type=\"text/javascript\">
                window.location = \"signup.php\";
              </script>";
        exit();
      }else
      $res = "";
      //Schauen ob E-Mail schon benutzt
      $res = mysqli_query($con, "SELECT login.email FROM login WHERE BINARY login.email LIKE '%". $_POST["email"] ."'");
      //Schon verwendet
      if(mysqli_num_rows($res)!=0) {
        echo "<script type=\"text/javascript\">
                window.location = \"signup.php?emailused\";
              </script>";
        exit();
      } else
      //Variable leeren
      $res = "";
      //Schauen ob email auf blacklist
      $res = mysqli_query($con, "SELECT blacklist.email FROM blacklist WHERE BINARY blacklist.email LIKE '%". $_POST["email"] ."'");
      if(mysqli_num_rows($res)!=0){
        echo "<script type=\"text/javascript\">
                window.location = \"signup.php?blacklistemail\";
              </script>";
        exit();
      } else
      //Variable leeren
      unset($res);
      //Schauen ob benutzername in balcklist
      $res = mysqli_query($con, "SELECT blacklist.username FROM blacklist WHERE BINARY blacklist.username LIKE '%". $_POST["ben"] ."'");
      if(mysqli_num_rows($res)!=0){
        echo "<script type=\"text/javascript\">
                window.location = \"signup.php?blacklistben\";
              </script>";
        exit();
      }else
      //Variable leeren
      unset($res);
      //Schauen ob Benutzername vergeben
      $res = mysqli_query($con, "SELECT login.ben FROM login WHERE BINARY login.ben LIKE '%". $_POST["ben"] ."'");
      //Benutzername exestiert Schon
      if(mysqli_num_rows($res)!=0){
        echo "<script type=\"text/javascript\">
                window.location = \"signup.php?benused\";
              </script>";
        exit();
      } else
      unset($res);
      //Alle Tests bestanden :))
      //Nächste ID herausfinden
      $res = mysqli_query($con, "SELECT login.ID FROM login WHERE login.ID = (SELECT MAX(login.ID) FROM login)");
      $num = mysqli_num_rows($res);
      if($num == 1){
        while ($dsatz = mysqli_fetch_assoc($res)) {
          //Neue BenutzerID
          $idben = intval($dsatz["ID"])+1;
        }
      }
      else{
        //Falls es keine Eintragungen gibt BenutzerID = 1
        $idben = 1;
      }
      //In die Tabelle aufnehemenh
      if(mysqli_query($con, "INSERT INTO `u808613999_net`.`login` (`ID`, `email`, `pass`, `ben`, `checked`) VALUES ('". $idben ."', '". $_POST["email"] ."', MD5('". $_POST["pass"] ."'), '". $_POST["ben"] ."', '0');")){
        //Zufälligen Bestätigungscode generieren
        $aCharacters = str_split("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890");
        for ($sRandomString = '', $i = 0; $i < 50; $i++){
          $sRandomString .= $aCharacters[array_rand($aCharacters)];
        }
        //$beCode == Bestätigungscode
        $beCode = $sRandomString;
        //beCode eintragen
        if(!mysqli_query($con, "INSERT INTO ActivationCodes (username, activationCode) VALUES ('". $_POST["ben"] ."', '". $beCode ."');")){
          //Fehlerwarnung
          echo "<script type=\"text/javascript\">
                  alert(\"Error with ActivationCode. Please contact support.\");
                  window.location = \"index.html\";
                </script>";
          exit();
        }
        //Empfängersetzen
        $empfaenger  = $_POST["email"];

        // Betreff
        $betreff = "Activation link for AlTr Network account";

        // Nachricht
        $nachricht = "
        <html>
        <head>
          <title>Activation link for AlTr Network account</title>
        </head>
        <body>
          <img src=\"http://altr.hol.es/assets/logo_lang.png\" alt=\"This graphic can not be shown.\"><br>
          Dear user,<br><br>
          Thanks for sign up an account. Please <a href=\"http://yourdomain.com/activate.php?account=". rawurlencode($_POST["ben"]) ."&code=". $beCode ."\">click</a> here activate your account. If the link doesn't work, please use the URL below.<br><br>
          http://yourdomain.com/activate.php?account=". rawurlencode($_POST["ben"]) ."&code=". $beCode ."<br><br>
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
        $header .= "From:  YourName <no-reply@yourdomain.com>" . "\r\n";

        // verschicke die E-Mail
        mail($empfaenger, $betreff, $nachricht, $header);
        echo "<script type=\"text/javascript\">
                alert(\"Successfully registred! Activation link send to your E-mail address. Please also check your spam folder. If the email does not arrive after 30 minutes, contact the support.\");
                window.location = \"login.php\";
              </script>";
      }
    }
  ?>
  <img src="" alt="">
  <script type="text/javascript">
    //Überprüfen ob Passwortbestätigung mit Passwort übereinstimmt
    function checkpass(){
      if (document.querySelector("#pass").value==document.querySelector("#passbes").value) {
        //Passt, dann durchlassen
        return true;
      }
      else{
        //Falls nicht FETTE Warnung :O
        document.getElementById('pass').className = "input is-danger";
        document.getElementById('passbes').className = "input is-danger";
        document.getElementById('checkpassbestext').style.display = "inline";
        document.getElementById('checkpasstext').style.display = "inline";
        return false;
      }
    }
  </script>
  <section class="hero is-success is-fullheight">
    <div class="hero-body">
      <div class="container has-text-centered">
        <div class="column is-4 is-offset-4">
          <h3 class="title has-text-grey">Sign Up</h3>
          <div class="box">
            <figure class="avatar">
              <img src="assets/logo_klein.png">
            </figure>
            <div class="content">
              <strong>Fill out this form to sign up</strong>
            </div>
            <form action="?send" method="post">
              <!-- E-Mail Feld -->
              <div class="field">
                <div class="control">
                  <input class="input is-large" type="email" name="email" id="email" placeholder="Your Email" pattern=".{6,}" autofocus="" required>
                </div>
              </div>
              <!-- Benutzernamen Feld -->
              <div class="field">
                <div class="control">
                  <input class="input is-large" type="text" name="ben" id="ben" placeholder="Your Username (min. 3 chars)" pattern="^[a-zA-Z][a-zA-Z0-9-_\.]{2,12}$" maxlength="12"  autofocus="" required>
                </div>
              </div>
              <!-- Passwort Feld -->
              <div class="field">
                <div class="control">
                  <input class="input is-large" type="password" name="pass" id="pass" placeholder="Your Password (UpperCase, LowerCase, Number/SpecialChar and min 8 Chars)" pattern="(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" required>
                </div>
                <div class="content" id="checkpasstext" style="display:none">
                  <strong>Check your password!</strong>
                </div>
              </div>
              <!-- Bestätige Passwort Feld -->
              <div class="field">
                <div class="control">
                  <input class="input is-large" type="password" name="passbes" id="passbes" placeholder="Confirm your Password" required>
                </div>
                <div class="content" id="checkpassbestext" style="display:none">
                  <strong>Check your confirmed password!</strong>
                </div>
              </div>
              <center><button class="button is-block is-info is-large" onclick="return checkpass()" type ="submit">Sign Up</button></center>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
  <script async type="text/javascript" src="../js/bulma.js"></script>
</body>
</html>
