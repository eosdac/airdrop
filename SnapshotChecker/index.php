    <?php

//error_reporting(E_ALL);
//ini_set('display_errors', 1);

    $lang = 'en';
    $language_files = array_diff(scandir('./lang'), array('.', '..'));
    if (isset($_POST['lang']) && in_array($_POST['lang'] . '.json', $language_files)) {
        $lang = $_POST['lang'];
    }
    $supported_languages = array();
    $strings = array();
    foreach($language_files as $language_file) {
        // note: This is safe because $language_files can only be set via scandir('./lang')
        $language_file_json = file_get_contents('./lang/' . $language_file);
        $langage_data = json_decode($language_file_json, true);
        reset($langage_data);
        $langauge_code = key($langage_data);
        $supported_languages[$langauge_code] = $langage_data[$langauge_code]['language'];
        if ($lang == $langauge_code) {
            $strings = $langage_data[$langauge_code];
        }
    }

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
        include 'dbconnect.php';
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
            $header = '<table class="table"><thead><tr><th>' . $strings['eth_address'] . '</th><th>' . $strings['eos_amount'] . '</th><th>' . $strings['status'] . '</th><th>' . $strings['transaction_hash'] . '</th></tr></thead>';
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
                        $status = $value['status'] = 'REQUESTED';
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
                foreach ($value as $key => $element) {
                    if ($key == 'transaction_hash' && $element != '') {
                        $element = '<a href="https://etherscan.io/tx/' . $element . '">' . $strings['view_on_etherscan'] . "</a>";
                    }
                    print '<td>' . $element . '</td>';
                }
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

            print "<h2>" . $strings['airdrop_status'] . "</h2>";

            $total_addresses = 0;
            $total_addresses_collected = 0;
            $total_addresses_requested = 0;
            $total_addresses_blank = 0;

            $total_eos = 0;
            $total_eos_collected = 0;
            $total_eos_requested = 0;
            $total_eos_blank = 0;

            $query = "SELECT status, count(*) as address_count, sum(eos_amount) as total_eos FROM eos_holders GROUP BY status";
            $result = mysqli_query($conn, $query);
            $table = '<table class="table"><thead><tr><th>' . $strings['status'] . '</th><th>' . $strings['address_count'] . '</th><th>' . $strings['total_eosdac'] . '</th></tr></thead>';
            while($value = $result->fetch_array(MYSQLI_ASSOC)){
                $total_addresses += $value['address_count'];
                switch ($value['status']) {
                    case 'REQUESTED':
                        $total_addresses_requested += $value['address_count'];
                        break;
                    case 'COLLECTED':
                        $total_addresses_collected += $value['address_count'];
                        break;
                    default:
                        $total_addresses_blank += $value['address_count'];
                        break;
                }
                $total_eos += $value['total_eos'];
                switch ($value['status']) {
                    case 'REQUESTED':
                        $total_eos_requested += $value['total_eos'];
                        break;
                    case 'COLLECTED':
                        $total_eos_collected += $value['total_eos'];
                        break;
                    default:
                        $total_eos_blank += $value['total_eos'];
                        break;
                }
                $status = $value['status'];
                if ($status == '') {
                    $status = 'UNCLAIMED';
                }

                $table .= '<tr><td>' . $status . '</td><td>' . $value['address_count'] . '</td><td>' . $value['total_eos'] . '</td>';
                $table .= '</tr>';
            }
            $table .= '</table>';

            $total_addresses_collected_percent = $total_addresses_collected / $total_addresses;
            $total_addresses_collected_percent = number_format( $total_addresses_collected_percent * 100, 0 );
            $total_addresses_requested_percent = $total_addresses_requested / $total_addresses;
            $total_addresses_requested_percent = number_format( $total_addresses_requested_percent * 100, 0 );
            $total_addresses_blank_percent = $total_addresses_blank / $total_addresses;
            $total_addresses_blank_percent = number_format( $total_addresses_blank_percent * 100, 0 );
            if ($total_addresses_collected_percent + $total_addresses_requested_percent + $total_addresses_blank_percent > 100) {
                $total_addresses_blank_percent -= 1;
            }

            $total_eos_collected_percent = $total_eos_collected / $total_eos;
            $total_eos_collected_percent = number_format( $total_eos_collected_percent * 100, 0 );
            $total_eos_requested_percent = $total_eos_requested / $total_eos;
            $total_eos_requested_percent = number_format( $total_eos_requested_percent * 100, 0 );
            $total_eos_blank_percent = $total_eos_blank / $total_eos;
            $total_eos_blank_percent = number_format( $total_eos_blank_percent * 100, 0 );
            if ($total_eos_collected_percent + $total_eos_requested_percent + $total_eos_blank_percent > 100) {
                $total_eos_blank_percent -= 1;
            }
            ?>

            <h3><?php print $strings['addresses']; ?></h3>
            <div class="progress">
              <div class="progress-bar progress-bar-success" role="progressbar" style="width: <?php print $total_addresses_collected_percent; ?>%" aria-valuenow="<?php print $total_addresses_collected_percent; ?>" aria-valuemin="0" aria-valuemax="100">COLLECTED</div>
              <div class="progress-bar" role="progressbar" style="width: <?php print $total_addresses_requested_percent; ?>%" aria-valuenow="<?php print $total_addresses_requested_percent; ?>" aria-valuemin="0" aria-valuemax="100">REQUESTED</div>
              <div class="progress-bar progress-bar-info" role="progressbar" style="width: <?php print $total_addresses_blank_percent; ?>%" aria-valuenow="<?php print $total_addresses_blank_percent; ?>" aria-valuemin="0" aria-valuemax="100">UNCLAIMED</div>
            </div>

            <h3><?php print $strings['eosdac_tokens']; ?></h3>
            <div class="progress">
              <div class="progress-bar progress-bar-success" role="progressbar" style="width: <?php print $total_eos_collected_percent; ?>%" aria-valuenow="<?php print $total_eos_collected_percent; ?>" aria-valuemin="0" aria-valuemax="100">COLLECTED</div>
              <div class="progress-bar" role="progressbar" style="width: <?php print $total_eos_requested_percent; ?>%" aria-valuenow="<?php print $total_eos_requested_percent; ?>" aria-valuemin="0" aria-valuemax="100">REQUESTED</div>
              <div class="progress-bar progress-bar-info" role="progressbar" style="width: <?php print $total_eos_blank_percent; ?>%" aria-valuenow="<?php print $total_eos_blank_percent; ?>" aria-valuemin="0" aria-valuemax="100">UNCLAIMED</div>
            </div>
            <?php

            print $table;

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
          /*
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="agree_to_terms" name="agree_to_terms" value="yes">
                <label class="form-check-label" for="agree_to_terms"><?php print $strings['terms']; ?></label>
            </div>
          */
          ?>
            <br />
            <input type="hidden" name="form_action" value="lookup_eth_address">
            <input type="hidden" name="lang" value="<?php print $lang; ?>">
            <button type="submit" class="btn btn-primary"><?php print $strings['submit']; ?></button>
        </form>
        <?php
    }
    ?>
    </div>
</body>
</html>
