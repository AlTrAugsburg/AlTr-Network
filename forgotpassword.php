<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AlTr Networks Forgot Password</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha256-eZrrJcwDc/3uDhsdt61sL2oOBY362qM3lon1gyExkL0=" crossorigin="anonymous" />
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
  <!-- Bulma Version 0.6.0 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.6.0/css/bulma.min.css" integrity="sha256-HEtF7HLJZSC3Le1HcsWbz1hDYFPZCqDhZa9QsCgVUdw=" crossorigin="anonymous" />
  <link rel="stylesheet" type="text/css" href="../css/login.css">
</head>
<body>
  <section class="hero is-success is-fullheight">
    <div class="hero-body">
      <div class="container has-text-centered">
        <div class="column is-4 is-offset-4">
          <h3 class="title has-text-grey">Forgot Password</h3>
          <p class="subtitle has-text-grey">Please fill out the form.</p>
          <div class="box">
            <figure class="avatar">
              <img src="assets/logo_klein.png">
            </figure>
            <form action="forgotpassword.php?requested" method="post">
              <?php
                //Schauen ob Formular abgeschickt wurde
                if(isset($_GET["requested"])){
                  //Schauen ob die Daten da sind
                  if(!isset($_POST["username"])||!isset($_POST["email"])){
                    //Wenn nicht zurückschicken
                    echo "<script type=\"text/javascript\">
                            window.location = \"http://altr.hol.es/forgotpassword.php\";
                          </script>";
                    die();
                  }
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
                  //Wie beim Sign Up wird die Tabelle ActivationCodes benutzt für den Rücksetzungslink um Platz zu sparen
                  //Account wird bei Passwort zurücksetzung deaktiviert. Anmeldung wird deswegen nach Absenden des Formulars nicht möglich sein
                  $res = mysqli_query($con, "SELECT login.ben FROM login WHERE BINARY login.email='". $_POST["email"] ."'");
                  //Schauen ob leer
                  if(empty($res)){
                    echo"<script type=\"text/javascript\">
                            alert(\"Account doesn't exist.\");
                            window.location = \"login.php\";
                          </script>";
                    die();
                  }
                  if(mysqli_num_rows($res)==0){
                    echo"<script type=\"text/javascript\">
                            alert(\"Account doesn't exist.\");
                            window.location = \"login.php\";
                          </script>";
                    die();
                  }
                  //Schauen ob mehr als 1 Ergebnis
                  if(mysqli_num_rows($res)!=1){
                    echo"<script type=\"text/javascript\">
                            alert(\"Error with database. Try again or contact the support.\");
                            window.location = \"login.php\";
                          </script>";
                    die();
                  }
                  //Ergebnis auslesen
                  while ($dsatz = mysqli_fetch_assoc($res)){
                		$benFromDB = $dsatz["ben"];
                  }
                  //Überprüfen ob Benutzernamen übereinstimmen
                  if($benFromDB!=$_POST["username"]){
                    //Falls nicht Fehlermeldung
                    echo"<script type=\"text/javascript\">
                            alert(\"Wrong E-mail adress/username. Please try again.\");
                            window.location = \"forgotpassword.php\";
                          </script>";
                    die();
                  }
                  //Schauen ob Account schon deaktiviert
                  $res2 = mysqli_query($con, "SELECT login.checked FROM login WHERE BINARY login.email='". $_POST["email"] ."'");
                  //Schauen ob leer
                  if(empty($res2)){
                    echo"<script type=\"text/javascript\">
                            alert(\"Account doesn't exist.\");
                            window.location = \"login.php\";
                          </script>";
                    die();
                  }
                  if(mysqli_num_rows($res2)==0){
                    echo"<script type=\"text/javascript\">
                            alert(\"Account doesn't exist.\");
                            window.location = \"login.php\";
                          </script>";
                    die();
                  }
                  //Schauen ob mehr als 1 Ergebnis
                  if(mysqli_num_rows($res2)!=1){
                    echo"<script type=\"text/javascript\">
                            alert(\"Error with database. Try again or contact the support.\");
                            window.location = \"login.php\";
                          </script>";
                    die();
                  }
                  //Ergebnis auslesen
                  while ($dsatz = mysqli_fetch_assoc($res2)){
                		$checkedAccount = intval($dsatz["checked"]);
                  }
                  //Schauen ob Wert passt
                  if($checkedAccount!=1&&$checkedAccount!=0){
                    echo"<script type=\"text/javascript\">
                            alert(\"Error with database. Try again or contact the support.\");
                            window.location = \"login.php\";
                          </script>";
                    die();
                  }
                  if($checkedAccount==0){
                    echo"<script type=\"text/javascript\">
                            alert(\"Activate your account first to use this feature.\");
                            window.location = \"login.php\";
                          </script>";
                    die();
                  }
                  //Deaktivierung des Accounts und setzen des Aktivierungscodes bzw. Passwortveränderungscodes
                  if(!mysqli_query($con, "UPDATE login SET checked = '0' WHERE login.ID = (SELECT login.ID WHERE BINARY login.ben = '". $_POST["username"] ."');")){
                    echo "<script type=\"text/javascript\">
                            alert(\"Error with deactivation. Please try again or contact the support.\");
                            window.location = \"forgotpassword.php\";
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
                  if(!mysqli_query($con, "INSERT INTO ActivationCodes (username, activationCode) VALUES ('". $_POST["username"] ."', '". $beCode ."');")){
                    //Fehlerwarnung
                    echo "<script type=\"text/javascript\">
                            alert(\"Error with ActivationCode. Please try again or contact the support.\");
                            window.location = \"forgotpassword.php\";
                          </script>";
                    die();
                  }
                  //E-Mail senden
                  //Empfängersetzen
                  $empfaenger  = $_POST["email"];

                  // Betreff
                  $betreff = "Password reset of your AlTr Network account";

                  // Nachricht
                  $nachricht = "<html>
                                  <head>
                                    <title>Password reset of your AlTr Network account</title>
                                  </head>
                                  <body>
                                    <img src=\"http://altr.hol.es/assets/logo_lang.png\" alt=\"This graphic can not be shown.\"><br>
                                    Dear user,<br><br>
                                    You reseted your password. Please <a href=\"http://yourdomain.com/setpassword.php?account=". $_POST["username"] ."&code=". $beCode ."\">click</a> here to change your password. If the link doesn't work, please use the URL below.<br><br>
                                    http://yourdomain.com/setpassword.php?account=". $_POST["username"] ."&code=". $beCode ."<br><br>
                                    Sincerely<br><br>
                                    AlTr Networks
                                  </body>
                                </html>
                                ";

                  //'Content-type'-Header wird gesetzt, da HTML E-Mail
                  $header  = 'MIME-Version: 1.0' . "\r\n";
                  $header .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

                  // zusätzliche Header
                  $header .= "To: ". $_POST["username"] ." <". $_POST["email"] .">" . "\r\n";
                  $header .= "From: AlTr Networks <no-reply@yourdomain.com>" . "\r\n";

                  // verschicke die E-Mail
                  mail($empfaenger, $betreff, $nachricht, $header);
                  echo "<script type=\"text/javascript\">
                          alert(\"Successfully reseted your password! Reset link send to your E-mail address. Please also check your spam folder. If the email does not arrive after 30 minutes, contact the support.\");
                          window.location = \"login.php\";
                        </script>";

                }
              ?>
              <div class="field">
                <div class="control">
                  <input class="input is-large" name="username" type="text" placeholder="Your Username" pattern="^[a-zA-Z][a-zA-Z0-9-_]{2,12}$" maxlength="12" autofocus="">
                </div>
              </div>

              <div class="field">
                <div class="control">
                  <input class="input is-large" name="email" type="email" placeholder="Your Email">
                </div>
              </div>
              <button type="submit" class="button is-block is-info is-large is-fullwidth">Send password reset link</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
  <script async type="text/javascript" src="../js/bulma.js"></script>
</body>
</html>
