<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
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
    $servername = "mysql.hostinger.com";
    $database = "u808613999_net";
    $username = "u808613999_net";
    $password = "Albert31";
    // Create connection
    $con = mysqli_connect($servername, $username, $password, $database);
    // Check connection
    if (!$con) {
      die("Connection failed: " . mysqli_connect_error());
    }
    $ben = $_SESSION["altrnet"];
    $res = mysqli_query($con, "SELECT * FROM ". $ben ." WHERE *");
    
  ?>
  <nav class="navbar has-shadow">
    <div class="container">
      <div class="navbar-brand">
        <a class="navbar-item" href="inbox.php">
          <img src="assets/logot.png">
        </a>

        <div class="navbar-burger burger" data-target="navMenu">
          <span></span>
          <span></span>
          <span></span>
        </div>
      </div>

      <div id="navMenu" class="navbar-menu">
        <div class="navbar-end">
          <div class="navbar-item has-dropdown is-hoverable">
            <a class="navbar-link">
              Account
            </a>

            <div class="navbar-dropdown">
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
  </nav>
<div class="columns" id="mail-app">
  <aside class="column is-2 aside hero is-fullheight">
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
          <a href="#" class="item"><span class="icon"><i class="fa fa-folder-o"></i></span><span class="name">Folders</span></a>
        </div>
      </div>
  </aside>
  <div class="column is-4 messages hero is-fullheight" id="message-feed">
    <div class="action-buttons">
      <div class="control is-grouped">
        <a class="button is-small"><i class="fa fa-chevron-down"></i></a>
        <a class="button is-small" href=""><i class="fa fa-refresh"></i></a>
      </div>
      <div class="control is-grouped">
        <a class="button is-small"><i class="fa fa-inbox"></i></a>
        <a class="button is-small"><i class="fa fa-exclamation-circle"></i></a>
        <a class="button is-small"><i class="fa fa-trash-o"></i></a>
      </div>
      <div class="control is-grouped">
        <a class="button is-small"><i class="fa fa-folder"></i></a>
        <a class="button is-small"><i class="fa fa-tag"></i></a>
      </div>
      <!--Seitenzahlen-->
      <div class="control is-grouped pg">
        <div class="title">
          <?php
            $res = mysqli_query($con, "SELECT  login.pass FROM login WHERE  login.email= '". $e."'");
           ?>
        </div>
        <a class="button is-link"><i class="fa fa-chevron-left"></i></a>
        <a class="button is-link"><i class="fa fa-chevron-right"></i></a>
      </div>
    </div>
  </div>
  <div class="column is-6 hero is-fullheight">
    Hier Mail.
  </div>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.0.3/vue.min.js" integrity="sha256-5CEXP4Sh+bwJYBngjYYh2TEev9kTDwcjw60jZatTHtY=" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Faker/3.1.0/faker.min.js" integrity="sha256-QHdJObhDO++VITP6S4tMlDHRWMaUOk+s/xWIRgF/YY0=" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js" integrity="sha256-4PIvl58L9q7iwjT654TQJM+C/acEyoG738iL8B8nhXg=" crossorigin="anonymous"></script>
<script type="text/javascript">
  $(document).ready(function(){
    window.inbox = {};
    window.paginate = {
      total: Math.random() * (54236 - 100) + 3
    }
    for (var i = 0; i <= 10; i++) {
      window.inbox[i] = {
        from : faker.name.findName(),
        timestamp: null,
        subject : faker.lorem.sentence().substring(0,40),
        snippet : faker.lorem.lines(),
        fullMail: window.faker.lorem.paragraphs(faker.random.number(40)),
        email : faker.internet.email()
      };
    }
    var inboxVue = new Vue({
      el: '#mail-app',
      data: {
        messages: window.inbox,
        paginate: {
          pointer: {
            start: 1,
            end: 10
          },
          total: 100
        }
      },
      methods: {
        showMessage: function (msg, index) {
          $('#message-pane').removeClass('is-hidden');
          $('.card').removeClass('active');
          $('#msg-card-'+index).addClass('active');
          $('.message .address .name').text(msg.from);
          $('.message .address .email').text(msg.email);
          var msg_body = '<p>' +
            msg.snippet +
            '</p>' +
            '<br>' +
            '<p>' +
            msg.fullMail +
            '</p>';
          $('.message .content').html(msg_body);
        }
      }
    });
  });
</script>
<?php
  mysqli_close($con);
 ?>
</body>
</html>
