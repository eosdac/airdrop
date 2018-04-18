<?php
include 'dbconnect.php';
include 'include_language.php';
?>
<html>
 <head>
  <title><?php print $strings['page_title']; ?></title>
  <meta charset="utf-8">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container">
        <?php
        include "include_airdrop_status.php";
        ?>
    </div>
</body>
</html>
