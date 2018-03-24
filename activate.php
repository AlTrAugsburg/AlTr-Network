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
    //Schauen ob Daten mitgegeben
    if(isset($_GET["account"])&&isset($_GET["code"])&&!isset($_GET["mailChanged"])){
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
      $res = mysqli_query($con, "SELECT ActivationCodes.activationCode FROM ActivationCodes WHERE BINARY ActivationCodes.username LIKE '%". rawurldecode($_GET["account"]) ."'");
      //Account exestiert nicht :O
      if(empty($res)){
        echo"<script type=\"text/javascript\">
                alert(\"Account does not exsist or is already ativated.\");
                window.location = \"index.html\";
              </script>";
        die();
      }
      $num = mysqli_num_rows($res);
      if($num == 1){
        while ($dsatz = mysqli_fetch_assoc($res)) {
          //Activationcode rausnehmen
          $code = $dsatz["activationCode"];
        }
      }
      else{
        //Falls es mehrere Ergebnisse gibt oder eins?!
        echo"<script type=\"text/javascript\">
                alert(\"Error with getting the code or the account is already activated. Please try again. If this message comes again, contact the support.\");
                window.location = \"index.html\";
              </script>";
        die();
      }
      //Activationcode überprüfen
      if($code==$_GET["code"]){
        //Beide stimmen überein :))
        //Markieren, dass Account aktiviert
        if(!mysqli_query($con, "UPDATE login SET checked = '1' WHERE login.ID = (SELECT login.ID WHERE BINARY login.ben = '". rawurldecode($_GET["account"]) ."');")){
          //Fehler :(
          echo"<script type=\"text/javascript\">
                  alert(\"Error with checking account. Please try again. If this message comes again, contact the support.\");
                  window.location = \"index.html\";
                </script>";
          die();
        }
        else{
          //Tabelle für Nachrichten erstellen
          if(!mysqli_query($con, "CREATE TABLE ". rawurldecode($_GET["account"]) ." ( ID INT (11) PRIMARY KEY, title VARCHAR(100), sender VARCHAR(12), text VARCHAR(1000), date VARCHAR(12) ); ")){
            echo"<script type=\"text/javascript\">
                    alert(\"Error with creating table. Please try again. If this message comes again, contact the support.\");
                    window.location = \"login.php\";
                  </script>";
            die();
          }
          //Endlich durch :)
          //Jetzt noch den ActivationCode löschen
          if(!mysqli_query($con, "DELETE FROM ActivationCodes WHERE BINARY ActivationCodes.username = '". $_GET["account"] ."'")){
            echo"<script type=\"text/javascript\">
                    alert(\"Error with deleting the code. Please try again. If this message comes again, contact the support.\");
                    window.location = \"login.php\";
                  </script>";
            die();
          }
          else{
            //Glückliche Kunde mitteilen :)
            echo"<script type=\"text/javascript\">
                    alert(\"Account activated successfully.\");
                    window.location = \"login.php\";
                  </script>";
          }
        }
      }
    }
    else{
      if(isset($_GET["account"])&&isset($_GET["code"])&&isset($_GET["mailChanged"])){
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
        $res = mysqli_query($con, "SELECT ActivationCodes.activationCode FROM ActivationCodes WHERE BINARY ActivationCodes.username LIKE '%". rawurldecode($_GET["account"]) ."'");
        //Account exestiert nicht :O
        if(empty($res)){
          echo"<script type=\"text/javascript\">
                  alert(\"Account does not exsist or is already ativated.\");
                  window.location = \"index.html\";
                </script>";
          die();
        }
        $num = mysqli_num_rows($res);
        if($num == 1){
          while ($dsatz = mysqli_fetch_assoc($res)) {
            //Activationcode rausnehmen
            $code = $dsatz["activationCode"];
          }
        }
        else{
          //Falls es mehrere Ergebnisse gibt oder eins?!
          echo"<script type=\"text/javascript\">
                  alert(\"Error with getting the code or the account is already activated. Please try again. If this message comes again, contact the support.\");
                  window.location = \"index.html\";
                </script>";
          die();
        }
        //Activationcode überprüfen
        if($code==$_GET["code"]){
          //Beide stimmen überein :))
          //Markieren, dass Account aktiviert
          if(!mysqli_query($con, "UPDATE login SET checked = '1' WHERE login.ID = (SELECT login.ID WHERE BINARY login.ben = '". rawurldecode($_GET["account"]) ."');")){
            //Fehler :(
            echo"<script type=\"text/javascript\">
                    alert(\"Error with checking account. Please try again. If this message comes again, contact the support.\");
                    window.location = \"index.html\";
                  </script>";
            die();
          }
          else{
            //Endlich durch :)
            //Jetzt noch den ActivationCode löschen
            if(!mysqli_query($con, "DELETE FROM ActivationCodes WHERE BINARY ActivationCodes.username = '". $_GET["account"] ."'")){
              echo"<script type=\"text/javascript\">
                      alert(\"Error with deleting the code. Please try again. If this message comes again, contact the support.\");
                      window.location = \"login.php\";
                    </script>";
              die();
            }
            else{
              //Glückliche Kunde mitteilen :)
              echo"<script type=\"text/javascript\">
                      alert(\"Account activated successfully.\");
                      window.location = \"login.php\";
                    </script>";
            }
          }
        }
      }
      else{
        echo"<script type=\"text/javascript\">
                alert(\"Funny bra! XD Try to be better next time ;)\");
                window.location = \"https://github.com/AlTrAugsburg/AlTr-Network\";
              </script>";
      }
    }
  ?>
</body>
</html>
