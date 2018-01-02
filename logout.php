<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - Free Bulma template</title>
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
          <h3 class="title has-text-grey">Logout</h3>
          <p class="subtitle has-text-grey">Please wait</p>
          <div class="box">
            <figure class="avatar">
              <img src="assets/logo_klein.png" >
            </figure>
            <form>
              <div class="field">
                <center><div class="title is-2 has-text-grey">Logging out...</div><div class="title"></div></center>
              </div>
            </form>
      </div>
    </div>
  </section>
  <script async type="text/javascript" src="../js/bulma.js"></script>
  <?php
    session_start();
    session_destroy();
    echo "<script>window.location.href = \"login.php\";</script>";
  ?>
</body>
</html>
