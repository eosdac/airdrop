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
            'eth_address_placeholder' => ' 0x... (this should be your public ETH address)',
            'no_private_key'=> 'Warning: please do not enter your private key!',
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
            'airdrop_request_success' => 'Thank you! Your airdrop request has been successfully recorded. Please note it may take several days before your tokens are batched and sent to you.',
            'address_count' => 'Address Count',
            'total_eosdac' => 'Total eosDAC',
            'airdrop_status' => 'Airdrop Status',
            ),
        'kor' => array(
            'language' => '한국어',
            'page_title' => 'eosDAC 에어드랍 툴',
            'welcome_message' => 'eosDAC 에어드랍에 참여해 주셔서 감사합니다!',
            'tool_explanation' => '기입란에 이더리움 주소를 입력하신 다음 에어드랍을 신청하시거나 또는 스냅샷 상태를 확인할 수 있습니다.',
            'eth_address' => '이더리움 주소',
            'eth_address_placeholder' => '0x... 이 주소는 귀하의 ETH 주소 여야합니다.',
            'no_private_key'=> '유의사항 : 개인 프라이빗 키를 입력하지 마십시오!',
            'eos_amount' => 'EOS 토큰 양',
            'status' => '상태',
            'transaction_hash' => '거래 해시 내역',
            'view_on_etherscan' => '이더스캔으로 거래 내역 보기',
            'missing_eth_address' => '4월 15일 01:00 UTC 시각에 EOS를 보유한 이더리움 주소를 기입하여 주십시오.',
            'eth_address_not_found' => '기입하신 이더리움 주소에 해당하는 스냅샷 정보를 찾지 못했습니다.',
            //'terms' => 'eosDAC <a href="https://eosdac.io/terms/">이용 약관</a>에 동의합니다.',
            'submit' => '제출하기',
            //'error_terms' => '이용 약관에 대한 동의가 필요합니다.',
            'start_over' => '다시 시도',
            'request_type_airdrop' => '이더리움 주소 에어드랍을 신청하기 ',
            'request_type_status' => '이더리움 주소 스냅샷 확인하기',
            'already_requested' => '이 이더리움 주소의 에어드랍은 이미 신청되었거나 전송이 완료되었습니다.',
            'airdrop_request_success' => '에어드랍 신청이 성공적으로 접수되었습니다. 신청하신 이더리움 주소로 eosDAC 토큰이 전송되기까지 며칠 정도 소요될 수 있습니다. 감사합니다.',
            'address_count' => '주소 개수',
            'total_eosdac' => '총 eosDAC',
            'airdrop_status' => '에어드랍 상태',
            ),
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

            $query = "SELECT count(*) as address_count, status, sum(eos_amount) as total_eos FROM eos_holders GROUP
 BY status";
            $result = mysqli_query($conn, $query);
            print '<table class="table"><thead><tr><th>' . $strings['address_count'] . '</th><th>' . $strings['stat
us'] . '</th><th>' . $strings['total_eosdac'] . '</th></tr></thead>';
            while($value = $result->fetch_array(MYSQLI_ASSOC)){
                print '<tr>';
                foreach ($value as $key => $element) {
                    print '<td>' . $element . '</td>';
                }
                print '</tr>';
            }
            print '</table>';
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
