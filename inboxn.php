<!DOCTYPE html>
<html>
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
  <?php
    session_start();
    //Überprüfen ob angemeldet
    if(!isset($_SESSION["altrnet"])){
      echo "<script>window.location.href = \"login.php\";</script>";
    }
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
    $ben = $_SESSION["altrnet"];
  ?>
  <div class="navbar is-info">
    <div class="container">
      <div class="navbar-brand">
        <a class="navbar-item" href="inbox.php">
          <img src="assets/logow.png">
        </a>
      </div>
      <div class="navbar-end">
        <div class="navbar-item has-dropdown is-hoverable">
          <a class="navbar-link">
            Account
          </a>

          <div class="navbar-dropdown is-white">
            <a class="navbar-item">
              Dashboard
            </a>
            <a class="navbar-item">
              Profile
            </a>
            <a class="navbar-item">
              Settings
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
          <a class="button is-danger is-block is-bold">
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
                $res = mysqli_query($con, "SELECT  login.pass FROM login WHERE  login.email= '". $e."'");
               ?>
               Seitenzahl
            </div>
            <a class="button is-link"><i class="fa fa-chevron-left"></i></a>
            <a class="button is-link"><i class="fa fa-chevron-right"></i></a>
          </div>
          <br><br>
        </div>
      </div>
      <!--Nachrichten-->
      <?php
        $res = mysqli_query($con, "SELECT ". $ben .".title, ". $ben .".sender, ". $ben .".date FROM ". $ben ." LIMIT 0, 10");
        if(mysqli_num_rows($res) == 0){
          echo "<div class=\"box\">
                  <div class=\"content\">
                    <p>
                      <strong>Sie haben leider keine Nachrichten</strong>
                    </p>
                  </div>
                </div>";
        }
        else {
          while ($dsatz = mysqli_fetch_assoc($res)) {
            echo "<div class=\"box\">
                    <div class=\"content\">
                      <p>
                        <a>". $dsatz["title"] ."</a>
                        <br><strong>From:</strong>". $dsatz["sender"] ." <strong>Date:</strong>". $dsatz["date"] ."
                      </p>
                    </div>
                  </div>";
          }
        }
      ?>
      <div class="box">
        <div class="content">
          <p>
            <a>This will be the title with link to message</a>
            <br><strong>From:</strong>Mister X <strong>Date:</strong>31.12.2000
          </p>
        </div>
      </div>
    </div>
    <div class="column is-5 hero is-fullheight is-light">
      lalala
    </div>
  </div>
  <?php
    mysqli_close($con);
   ?>
</body>
</html>

