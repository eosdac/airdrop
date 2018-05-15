<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

include 'dbconnect.php';
include 'include_language.php';
include 'include_header.php';

$action = isset($_POST['form_action']) ? $_POST['form_action'] : '';
$error = '';

?>

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
    print "<h1>" . $strings['complete'] . '</h1>';

    include "include_airdrop_status.php";

    print "<p>";
    print "<strong>WITHHELD:</strong> " . $strings['withheld_explanation'] . "<br />";
    print "</p>";

    ?>
    </div>
</body>
</html>
