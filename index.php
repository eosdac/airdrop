    <?php

//error_reporting(E_ALL);
//ini_set('display_errors', 1);

    // config
    $_lang = array(
        'en' => array(
            'language' => 'English',
            'page_title' => 'eosDAC Airdrop Tool',
            'welcome_message' => 'Thank you for participating in the eosDAC airdrop!',
            'tool_explanation' => 'This simple form can be used to request a manual airdrop to your Ethereum address or review the status the airdrop for your Ethereum address',
            'eth_address' => 'ETH Address',
            'eos_amount' => 'EOS Amount',
            'status' => 'Status',
            'transaction_hash' => 'Transaction Hash',
            'view_on_etherscan' => 'View transaction on etherscan',
            'missing_eth_address' => 'Please enter your Ethereum address which held EOS on April 15th at 01:00 UTC.',
            'eth_address_not_found' => 'We were unable to find the Ethereum address you supplied in the snapshot data.',
            //'terms' => 'I agree to the eosDAC <a href="https://eosdac.io/terms/">terms of service</a>',
            'submit' => 'Submit',
            //'error_terms' => 'You must agree to the terms of service.',
            'start_over' => 'Start over',
            'request_type_airdrop' => 'Request Airdrop for this Address',
            'request_type_status' => 'Review Status of this Address',
            'already_requested' => 'The airdrop for this address has already been requested or collected.',
            'airdrop_request_success' => 'Thank you! Your airdrop request has been successfully recorded. Please note it may take several days before your tokens are batched and sent to you.'
            ),
        'en2' => array(
            'language' => 'English2',
            'page_title' => 'eosDAC Airdrop Tool2',
            'welcome_message' => 'Thank you for participating in the eosDAC airdrop!2',
            'tool_explanation' => 'This simple form can be used to request a manual airdrop to your Ethereum address or review the status the airdrop for your Ethereum address2',
            'eth_address' => 'ETH Address2',
            'eos_amount' => 'EOS Amount2',
            'status' => 'Status2',
            'transaction_hash' => 'Transaction Hash2',
            'view_on_etherscan' => 'View transaction on etherscan2',
            'missing_eth_address' => 'Please enter your Ethereum address which held EOS on April 15th at 01:00 UTC.2',
            'eth_address_not_found' => 'We were unable to find the Ethereum address you supplied in the snapshot data.2',
            //'terms' => 'I agree to the eosDAC <a href="https://eosdac.io/terms/">terms of service</a>2',
            'submit' => 'Submit2',
            //'error_terms' => 'You must agree to the terms of service.2',
            'start_over' => 'Start over2',
            'request_type_airdrop' => 'Request Airdrop for this Address2',
            'request_type_status' => 'Review Status of this Address2',
            'already_requested' => 'The airdrop for this address has already been requested or collected.2',
            'airdrop_request_success' => 'Thank you! Your airdrop request has been successfully recorded. Please note it may take several days before your tokens are batched and sent to you.2'
            )
        );

    $lang = 'en';
    if (isset($_POST['lang']) && array_key_exists($_POST['lang'], $_lang)) {
        $lang = $_POST['lang'];
    }

    $strings = $_lang[$lang];

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
    <div class="container">

    <form method="POST">
        <input type="hidden" name="form_action" value="">
        <select name="lang" onchange="this.form.submit()">
        <?php
        foreach ($_lang as $key => $language_strings) {
            $selected = '';
            if ($lang == $key) {
                $selected = ' selected';
            }
            print "<option" . $selected . " value=\"" . $key . "\">" . $language_strings['language'] . "</option>";
        }
        ?>
        </select>
    </form>
<?php

    print "<h1>" . $strings['welcome_message'] . '</h1>';
    print "<p>" . $strings['tool_explanation'] . '</p>';

    // LOOK UP ADDRESS
    if ($action == 'lookup_eth_address') {
        $conn = mysqli_connect('', '', '', '');
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
        $request_type = isset($_POST['request_type']) ? $_POST['request_type'] : 'status';
        if ($error == '') {
            $query = "SELECT * FROM eos_holders WHERE eth_address = '" . $eth_address . "' LIMIT 1";
            $result = mysqli_query($conn, $query);
            $header = '<table class="table"><thead><tr><th>' . $strings['eth_address'] . '</th><th>' . $strings['eos_amount'] . '</th><th>' . $strings['status'] . '</th><th>' . $strings['transaction_hash'] . '</th></tr></thead>';
            $has_results = 0;
            $info = '';
            while($value = $result->fetch_array(MYSQLI_ASSOC)){
                if ($request_type == 'airdrop') {
                    if ($value['status'] == '') {
                        $update_query = "UPDATE eos_holders SET status = 'REQUESTED' WHERE eth_address = '" . $eth_address . "' AND status = ''";
                        mysqli_query($conn, $update_query);
                        $value['status'] = 'REQUESTED';
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
            } else {
                $error = $strings['eth_address_not_found'];
            }
            $result->close();
            mysqli_close($conn);
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
                <input type="text" class="form-control" name="eth_address" id="eth_address">
            </div>
            <?php
            $checked = '';
            if (isset($_POST['request_type']) && $_POST['request_type'] == 'airdrop') {
                $checked = ' checked';
            }
            ?>
            <div class="form-check">
                <input class="form-check-input"<?php print $checked; ?> type="radio" name="request_type" id="request_type_airdrop" value="airdrop" checked>
                <label class="form-check-label" for="request_type_airdrop">
                <?php print $strings['request_type_airdrop']; ?>
                </label>
            </div>
            <?php
            $checked = '';
            if (isset($_POST['request_type']) && $_POST['request_type'] == 'status') {
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
