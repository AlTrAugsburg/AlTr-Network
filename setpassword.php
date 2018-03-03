<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AlTr Networks Set new password</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha256-eZrrJcwDc/3uDhsdt61sL2oOBY362qM3lon1gyExkL0=" crossorigin="anonymous" />
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
  <!-- Bulma Version 0.6.0 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.6.0/css/bulma.min.css" integrity="sha256-HEtF7HLJZSC3Le1HcsWbz1hDYFPZCqDhZa9QsCgVUdw=" crossorigin="anonymous" />
  <link rel="stylesheet" type="text/css" href="../css/login.css">
</head>
<body>
  <?php
    //Schauen ob Daten dabei
    if(!isset($_GET["account"])&&$_GET["code"]){
      //Zur Startseite schicken, falls keine verhanden
      echo "<script type=\"text/javascript\">
              window.location = \"index.html\";
            </script>";
      die();
    }
    if(isset($_GET["send"])){
      /*Verbindung zur 1.Datenbank herstellen*/
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
      //ID herausfinden
      $res = mysqli_query($con, "SELECT login.ID FROM login WHERE BINARY login.ben = '". $_GET["account"] ."'");
      //Schauen ob Ergebnis
      if(empty($res)){
        //Account exestiert nicht
        echo "<script type=\"text/javascript\">
                alert(\"Account does not exist!\");
                window.location = \"index.html\";
              </script>";
        die();
      }
      if(mysqli_num_rows($res)==0){
        //Account exestiert nicht
        echo "<script type=\"text/javascript\">
                alert(\"Account does not exist!\");
                window.location = \"index.html\";
              </script>";
        die();
      }
      if(mysqli_num_rows($res)!=1){
        //Mehr als 1 Ergebnis
        echo "<script type=\"text/javascript\">
                alert(\"Error with database. Please try again or contact the support.\");
                window.location = \"setpassword.php?account=". $_GET["account"] ."&code=". $_GET["code"] ."\";
              </script>";
        die();
      }
      //Das eine Ergebnis auslesen
      while ($dsatz = mysqli_fetch_assoc($res)) {
        //Die BenutzerID rauslesen
        $ID = intval($dsatz["ID"]);
      }
      //Neues Passwort setzen
      if(!mysqli_query($con, "UPDATE login SET pass = MD5( '". $_POST["password"] ."' ) WHERE login.ID = ". $ID)){
        //Fehlermeldung
        echo "<script type=\"text/javascript\">
                alert(\"Error while setting new password. Please try again or contact the support.\");
                window.location = \"setpassword.php?account=". $_GET["account"] ."&code=". $_GET["code"] ."\";
              </script>";
        die();
      }
      //Account aktivieren
      if(!mysqli_query($con, "UPDATE login SET checked = '1' WHERE login.ID = (SELECT login.ID WHERE BINARY login.ben = '". $_GET["account"] ."');")){
        //Fehler :(
        echo"<script type=\"text/javascript\">
                alert(\"Error with checking account. Please try again. If this message comes again, contact the support.\");
                window.location = \"setpassword.php?account=". $_GET["account"] ."&code=". $_GET["code"] ."\";
              </script>";
        die();
      }
      //Eintrag bei Aktivierungscodes löschen
      if(!mysqli_query($con, "DELETE FROM ActivationCodes WHERE BINARY ActivationCodes.username = '". $_GET["account"] ."'")){
        echo"<script type=\"text/javascript\">
                alert(\"Error with deleting the code. Please try again. If this message comes again, contact the support.\");
                window.location = \"setpassword.php?account=". $_GET["account"] ."&code=". $_GET["code"] ."\";
              </script>";
        die();
      }
      else {
        //Fertig. Meldung rausgeben und zum Login wechseln
        echo "<script type=\"text/javascript\">
                alert(\"New password successfully set.\");
                window.location = \"login.php\";
              </script>";
        die();
      }
    }
  ?>
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
          <h3 class="title has-text-grey">Set new password</h3>
          <p class="subtitle has-text-grey">Please enter your new password</p>
          <div class="box">
            <figure class="avatar">
              <img src="assets/logo_klein.png">
            </figure>
            <?php
            //Daten weiterreichen
            echo "<form action=\"?account=". $_GET["account"] ."&code=". $_GET["code"] ."&send\" method=\"post\" >";
            ?>
              <div class="field">
                <div class="control">
                  <div class="content" id="checkpasstext" style="display:none">
                    <strong>Check your password!</strong>
                  </div>
                  <input class="input is-large" name="password" type="password" placeholder="Your new Password (UpperCase, LowerCase, Number/SpecialChar and min 8 Chars)" pattern="(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" autofocus="" required>
                </div>
              </div>

              <div class="field">
                <div class="control">
                  <input class="input is-large" type="password" id="pass" placeholder="Confirm your Password" required>
                  <div class="content" id="checkpasstext" style="display:none">
                    <strong>Check your confirmed password!</strong>
                  </div>
                </div>
              </div>
              <button class="button is-block is-info is-large" id="passbes" onclick="return checkpass()" type ="submit">Sign Up</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
  <script async type="text/javascript" src="../js/bulma.js"></script>
</body>
</html>
