<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

include 'dbconnect.php';
include 'include_language.php';

$action = isset($_POST['form_action']) ? $_POST['form_action'] : '';
$error = '';

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

<!-- Copied from site, slightly modified -->
<header class="banner navbar navbar-default navbar-static-top " role="banner">
<div class="container">
<div class="navbar-header">
<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
<span class="sr-only">Toggle navigation</span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
</button>
<div id="logo">
<a href="https://eosdac.io/">
<img class="logo-main logo-reg" src="https://eosdac.io/wp-content/uploads/2018/03/eosdaclogo1-200-text-new-250x50.png" height="50" width="250" alt="eosDAC">
</a>
</div>
</div>
<nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">
<ul id="menu-menu-1" class="nav navbar-nav"><li class="menu-governance"><a href="https://eosdac.io/governance/">Governance</a></li>
<li class="menu-tokens"><a href="https://eosdac.io/tokens/">Tokens</a></li>
<li class="menu-exchanges"><a href="https://eosdac.io/exchanges/">Exchanges</a></li>
<li class="menu-timeline"><a href="https://eosdac.io/#timeline">Timeline</a></li>
<li class="menu-legal"><a href="https://eosdac.io/terms/">Legal</a></li>
<li class="menu-faq"><a href="https://eosdac.io/faq/">FAQ</a></li>
<li class="menu-news"><a href="https://eosdac.io/news/">News</a></li>
</ul>
</li>
</ul> </nav>
</div>
</header>
<!-- End copy -->

    <div class="container">
    <form method="POST">
        <input type="hidden" name="form_action" value="">
        <select name="lang" onchange="this.form.submit()">
        <?php
        foreach ($supported_languages as $language_code => $language_description) {
            $selected = '';
            if ($lang == $language_code) {
                $selected = ' selected';
            }
            print "<option" . $selected . " value=\"" . $language_code . "\">" . $language_description . "</option>";
        }
        ?>
        </select>
    </form>
<?php
    print "<h1>" . $strings['welcome_message'] . '</h1>';
    print "<p>" . $strings['tool_explanation'] . '</p>';

    $request_type = isset($_POST['request_type']) ? $_POST['request_type'] : 'status';

    // LOOK UP ADDRESS
    if ($action == 'lookup_eth_address') {
        /*
        $agree_to_terms = isset($_POST['agree_to_terms']) ? $_POST['agree_to_terms'] : '';
        if ($agree_to_terms != 'yes') {
            $error = $strings['error_terms'];
        }
        */
        $eth_address = isset($_POST['eth_address']) ? mysqli_real_escape_string($conn,$_POST['eth_address']) : '';
        if ($eth_address == '') {
            $error = $strings['missing_eth_address'];
        }
        if ($error == '') {
            $query = "SELECT * FROM eos_holders WHERE eth_address = '" . $eth_address . "' LIMIT 1";
            $result = mysqli_query($conn, $query);
            $header = '<table class="table"><thead><tr><th>' . $strings['status'] . '</th><th>' . $strings['eth_address'] . '</th><th>' . $strings['eos_amount'] . '</th><th>' . $strings['transaction_hash'] . '</th></tr></thead>';
            $has_results = 0;
            $info = '';
            $status = '';
            while($value = $result->fetch_array(MYSQLI_ASSOC)){
                $status = $value['status'];
                if ($status == '') {
                    $status = 'UNCLAIMED';
                }

                if ($request_type == 'airdrop') {
                    if ($value['status'] == '') {
                        $update_query = "UPDATE eos_holders SET status = 'REQUESTED' WHERE eth_address = '" . $eth_address . "' AND status = ''";
                        mysqli_query($conn, $update_query);
                        $status = 'REQUESTED';
                        $info = 'airdrop_request_success';
                    } else {
                        $info = 'already_requested';
                    }
                }
                if ($has_results == 0) {
                    if ($info != '') {
                        $info_type = 'info';
                        if ($info == 'airdrop_request_success') {
                            $info_type = 'success';
                        }
                        print "<div class=\"alert alert-" . $info_type . "\" role=\"alert\">";
                        print $strings[$info];
                        print "</div>";
                    }
                    print $header;
                }
                $has_results = 1;
                print '<tr>';
                print '<td>' . $status . '</td><td><a href="https://etherscan.io/address/' . $value['eth_address'] . '">' . $value['eth_address'] . '</a></td><td>' . number_format($value['eos_amount'],4) . '</td>';
                print '<td><a href="https://etherscan.io/tx/' . $value['transaction_hash'] . '">' . $strings['view_on_etherscan'] . '</a></td>';
                print '</tr>';
            }
            if ($has_results) {
                print '</table>';

                if ($status == 'REQUESTED') {
                    print '<p><strong>REQUESTED</strong>: ' . $strings['requested_explanation'] . '</p>';
                }
                if ($status == 'COLLECTED') {
                    print '<p><strong>COLLECTED</strong>: ' . $strings['collected_explanation'] . '</p>';
                }
                if ($status == 'UNCLAIMED') {
                    print '<p><strong>UNCLAIMED</strong>: ' . $strings['unclaimed_explanation'] . '</p>';
                }

            } else {
                $error = $strings['eth_address_not_found'];
            }
        }
        if ($error != '') {
            $action = '';    
        } else {
            ?>
            <br />
            <form method="POST">
                <input type="hidden" name="form_action" value="">
                <input type="hidden" name="lang" value="<?php print $lang; ?>">
                <button type="submit" class="btn btn-primary"><?php print $strings['start_over']; ?></button>
            </form>
            <?php
        }
    }

    // BEING HERE
    if ($action == '') {
        if ($error) {
            ?>
            <div class="alert alert-danger" role="alert">
              <?php print $error; ?>
            </div>
            <?php
        }
        ?>
        <form method="POST">
            <div class="form-group">
                <label for="eth_address"><?php print $strings['eth_address']; ?></label>
                <input type="text" class="form-control" name="eth_address" id="eth_address" placeholder="<?php print $strings['eth_address_placeholder']; ?>">
                <small id="eth_address" class="form-text text-muted"><?php print $strings['no_private_key']; ?></small>
            </div>
            <?php
            $checked = '';
            if ($request_type == 'status') {
                $checked = ' checked';
            }
            ?>
            <div class="form-check">
                <input class="form-check-input"<?php print $checked; ?> type="radio" name="request_type" id="request_type_status" value="status">
                <label class="form-check-label" for="request_type_status">
                <?php print $strings['request_type_status']; ?>
                </label>
            </div>
            <?php
            $checked = '';
            if ($request_type == 'airdrop') {
                $checked = ' checked';
            }
            ?>
            <div class="form-check">
                <input class="form-check-input"<?php print $checked; ?> type="radio" name="request_type" id="request_type_airdrop" value="airdrop">
                <label class="form-check-label" for="request_type_airdrop">
                <?php print $strings['request_type_airdrop']; ?>
                </label>
            </div>
          <?php
          ?>
            <br />
            <input type="hidden" name="form_action" value="lookup_eth_address">
            <input type="hidden" name="lang" value="<?php print $lang; ?>">
            <button type="submit" class="btn btn-primary"><?php print $strings['submit']; ?></button>
        </form>
        <br/>
        <br/>
        <hr/>
        <?php
    }
    print "<h2>" . $strings['airdrop_status'] . "</h2>";

    include "include_airdrop_status.php";

    ?>
    </div>
</body>
</html>
