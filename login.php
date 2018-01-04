r<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AlTr Networks Login</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha256-eZrrJcwDc/3uDhsdt61sL2oOBY362qM3lon1gyExkL0=" crossorigin="anonymous" />
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
  <!-- Bulma Version 0.6.0 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.6.0/css/bulma.min.css" integrity="sha256-HEtF7HLJZSC3Le1HcsWbz1hDYFPZCqDhZa9QsCgVUdw=" crossorigin="anonymous" />
  <link rel="stylesheet" type="text/css" href="css/login.css">
</head>
<body>
  <section class="hero is-success is-fullheight">
    <div class="hero-body">
      <div class="container has-text-centered">
        <div class="column is-4 is-offset-4">
          <h3 class="title has-text-grey">Login</h3>
          <p class="subtitle has-text-grey">Please login to proceed.</p>
          <div class="box">
            <figure class="avatar">
              <img src="assets/logo_klein.png" >
            </figure>
            <form id="2" name="2" action="?login" method="post">
              <?php
              //Session starten oder aufnehmen
              session_start();
              //Überprüfen ob schon angemeldet
              if(isset($_SESSION["altrnet"])){
                echo "<script>window.location.href = \"inboxn.php\";</script>";
              }
              $p = $_POST["password"];
              $e = $_POST["email"];
              //Prüfen ob Login Sequenz
              if(isset($_GET["login"])){
                if($p!=null&&$e!=null){
                  echo "<div class=\"field\">
                          <div class=\"control\">
                            <input class=\"Disabled input is-large\" type=\"email\" placeholder=\"Your Email\" autofocus=\"\" name=\"email\" disabled>
                          </div>
                        </div>

                        <div class=\"field\">
                          <div class=\"control\">
                            <input class=\"Disabled input is-large\" type=\"password\" placeholder=\"Your Password\" name=\"password\" disabled>
                          </div>
                        </div>
                        <br>
                        <center><button class=\"button is-block is-info is-large is-loading\"></button></center>";
                  /*Verbindung zur 1.Datenbank herstellen*/
                	$servername = "Server";
                	$database = "Databasename";
                	$username = "Username";
                	$password = "Password";
                  // Create connection
                  $con = mysqli_connect($servername, $username, $password, $database);
                  // Check connection
                  if (!$con) {
                		die("Connection failed: " . mysqli_connect_error());
                	}
                  //Überprüfen Passwort und Email
                  $res = mysqli_query($con, "SELECT  login.pass FROM login WHERE  login.email= '". $e."'");
                  /*Daten ausgeben*/
                	$num = mysqli_num_rows($res);
                	if($num > 1) exit ("<script>window.location.href = \"login.php?fdb\";</script>");
                  if($num == 0) exit ("<script>window.location.href = \"login.php?nexsist\";</script>");
                  /*Datnesätze aus Abfrage bearbeiten*/
                	while ($dsatz = mysqli_fetch_assoc($res)){
                		$pass = $dsatz[pass];
                  }
                  //Passwort prüfen
                  if($pass == md5($p)){
                    $res = mysqli_query($con, "SELECT  login.ben FROM login WHERE  login.email= '". $e."'");
                    while ($dsatz = mysqli_fetch_assoc($res)){
                  		$ben = $dsatz[ben];
                    }
                    $_SESSION["altrnet"] = $ben;
                    echo "<script>window.location.href = \"inboxn.php\";</script>";
                  }
                  else{
                    echo "<script>window.location.href = \"login.php?wrongpass=". $e ."\";</script>";
                  }
                  mysqli_close($con);
                }
                else {
                  echo "<script>window.location.href = \"login.php?npne\";</script>";
                }
              }
              else{
                //Kein Passwort oder Email response
                if(isset($_GET["npne"])){
                  echo "<div class=\"notification is-danger\">
                          <center>Please enter email and password!</center>
                        </div>
                        <div class=\"field\">
                        <div class=\"control\">
                          <input class=\"input is-large\" type=\"email\" placeholder=\"Your Email\" autofocus=\"\" name=\"email\">
                        </div>
                      </div>

                      <div class=\"field\">
                        <div class=\"control\">
                          <input class=\"input is-large\" type=\"password\" placeholder=\"Your Password\" name=\"password\">
                        </div>
                      </div>
                      <br>
                      <center><button class=\"button is-block is-info is-large\" type = \"submit\">Login</button></center>";
                }
                else {
                  //Fehler in DB, da mehr als 1 Ergebnis response
                  if(isset($_GET["fdb"])){
                    echo "<div class=\"notification is-danger\">
                            <center>Error in database. Please click <a href=\"suport.php\">here</a> to contact the support.</center>
                          </div>
                          <div class=\"field\">
                          <div class=\"control\">
                            <input class=\"input is-large\" type=\"email\" placeholder=\"Your Email\" autofocus=\"\" name=\"email\">
                          </div>
                        </div>

                        <div class=\"field\">
                          <div class=\"control\">
                            <input class=\"input is-large\" type=\"password\" placeholder=\"Your Password\" name=\"password\">
                          </div>
                        </div>
                        <br>
                        <center><button class=\"button is-block is-info is-large\" type = \"submit\">Login</button></center>";
                  }
                  else{
                    //Account exestiert nicht
                    if(isset($_GET["nexsist"])){
                      echo "<div class=\"notification is-danger\">
                                <center>This account doesn't exsist. Please click <a href=\"create.php\">here</a> to create an account.</center>
                              </div>
                              <div class=\"field\">
                              <div class=\"control\">
                                <input class=\"input is-large\" type=\"email\" placeholder=\"Your Email\" autofocus=\"\" name=\"email\">
                              </div>
                            </div>

                            <div class=\"field\">
                              <div class=\"control\">
                                <input class=\"input is-large\" type=\"password\" placeholder=\"Your Password\" name=\"password\">
                              </div>
                            </div>
                            <br>
                            <center><button class=\"button is-block is-info is-large\" type = \"submit\">Login</button></center>";
                    }
                    else{
                      if(isset($_GET["wrongpass"])){
                        echo "<div class=\"notification is-danger\">
                                  <center>Wrong Password. Please try again.</center>
                                </div>
                                <div class=\"field\">
                                <div class=\"control\">
                                  <input class=\"input is-large\" type=\"email\" placeholder=\"Your Email\" autofocus=\"\" name=\"email\" value=\"".$_GET["wrongpass"]."\">
                                </div>
                              </div>

                              <div class=\"field\">
                                <div class=\"control\">
                                  <input class=\"input is-large\" type=\"password\" placeholder=\"Your Password\" name=\"password\">
                                </div>
                              </div>
                              <br>
                              <center><button class=\"button is-block is-info is-large\" type = \"submit\">Login</button></center>";
                      }
                      else{
                      echo"<div class=\"field\">
                            <div class=\"control\">
                              <input class=\"input is-large\" type=\"email\" placeholder=\"Your Email\" autofocus=\"\" name=\"email\">
                            </div>
                          </div>

                          <div class=\"field\">
                            <div class=\"control\">
                              <input class=\"input is-large\" type=\"password\" placeholder=\"Your Password\" name=\"password\">
                            </div>
                          </div>
                          <br>
                          <center><button class=\"button is-block is-info is-large\" type = \"submit\">Login</button></center>";
                      }
                    }
                  }
                }
              }
              ?>
            </form>
          </div>
          <p class="has-text-grey">
            <a href="../">Sign Up</a> &nbsp;·&nbsp;
            <a href="../">Forgot Password</a> &nbsp;·&nbsp;
            <a href="../">Need Help?</a>
          </p>
        </div>
      </div>
    </div>
  </section>
  <script async type="text/javascript" src="../js/bulma.js"></script>
</body>
</html>
