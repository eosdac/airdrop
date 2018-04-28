<?php
$total_addresses = 0;
$total_addresses_collected = 0;
$total_addresses_requested = 0;
$total_addresses_withheld = 0;
$total_addresses_blank = 0;

$total_eos = 0;
$total_eos_collected = 0;
$total_eos_requested = 0;
$total_eos_withheld = 0;
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
        case 'WITHHELD':
            $total_addresses_withheld += $value['address_count'];
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
        case 'WITHHELD':
            $total_eos_withheld += $value['total_eos'];
            break;
        default:
            $total_eos_blank += $value['total_eos'];
            break;
    }
    $status = $value['status'];
    if ($status == '') {
        $status = 'UNCLAIMED';
    }

    $table .= '<tr><td>' . $status . '</td><td>' . number_format($value['address_count']) . '</td><td>' . number_format($value['total_eos'],4) . '</td>';
    $table .= '</tr>';
}
$table .= '</table>';

$total_addresses_collected_percent = $total_addresses_collected / $total_addresses;
$total_addresses_collected_percent = number_format( $total_addresses_collected_percent * 100, 0 );
$total_addresses_requested_percent = $total_addresses_requested / $total_addresses;
$total_addresses_requested_percent = number_format( $total_addresses_requested_percent * 100, 0 );
$total_addresses_withheld_percent = $total_addresses_withheld / $total_addresses;
$total_addresses_withheld_percent = number_format( $total_addresses_withheld_percent * 100, 0 );
$total_addresses_blank_percent = $total_addresses_blank / $total_addresses;
$total_addresses_blank_percent = number_format( $total_addresses_blank_percent * 100, 0 );
if ($total_addresses_collected_percent + $total_addresses_requested_percent + $total_addresses_blank_percent + $total_addresses_withheld_percent > 100) {
    $total_addresses_blank_percent -= 1;
}

$total_eos_collected_percent = $total_eos_collected / $total_eos;
$total_eos_collected_percent = number_format( $total_eos_collected_percent * 100, 0 );
$total_eos_requested_percent = $total_eos_requested / $total_eos;
$total_eos_requested_percent = number_format( $total_eos_requested_percent * 100, 0 );
$total_eos_withheld_percent = $total_eos_withheld / $total_eos;
$total_eos_withheld_percent = number_format( $total_eos_withheld_percent * 100, 0 );
$total_eos_blank_percent = $total_eos_blank / $total_eos;
$total_eos_blank_percent = number_format( $total_eos_blank_percent * 100, 0 );
if ($total_eos_collected_percent + $total_eos_requested_percent + $total_eos_blank_percent + $total_eos_withheld_percent > 100) {
    $total_eos_blank_percent -= 1;
}
?>

<h3><?php print $strings['addresses']; ?></h3>
<div class="progress">
    <div class="progress-bar progress-bar-success" role="progressbar" style="width: <?php print $total_addresses_collected_percent; ?>%" aria-valuenow="<?php print $total_addresses_collected_percent; ?>" aria-valuemin="0" aria-valuemax="100">COLLECTED</div>
    <div class="progress-bar" role="progressbar" style="width: <?php print $total_addresses_requested_percent; ?>%" aria-valuenow="<?php print $total_addresses_requested_percent; ?>" aria-valuemin="0" aria-valuemax="100">REQUESTED</div>
    <div class="progress-bar progress-bar-info" role="progressbar" style="width: <?php print $total_addresses_blank_percent; ?>%" aria-valuenow="<?php print $total_addresses_blank_percent; ?>" aria-valuemin="0" aria-valuemax="100">UNCLAIMED</div>
    <div class="progress-bar progress-bar-warning" role="progressbar" style="width: <?php print $total_addresses_withheld_percent; ?>%" aria-valuenow="<?php print $total_addresses_withheld_percent; ?>" aria-valuemin="0" aria-valuemax="100">WITHHELD</div>
</div>

<h3><?php print $strings['eosdac_tokens']; ?></h3>
<div class="progress">
    <div class="progress-bar progress-bar-success" role="progressbar" style="width: <?php print $total_eos_collected_percent; ?>%" aria-valuenow="<?php print $total_eos_collected_percent; ?>" aria-valuemin="0" aria-valuemax="100">COLLECTED</div>
    <div class="progress-bar" role="progressbar" style="width: <?php print $total_eos_requested_percent; ?>%" aria-valuenow="<?php print $total_eos_requested_percent; ?>" aria-valuemin="0" aria-valuemax="100">REQUESTED</div>
    <div class="progress-bar progress-bar-info" role="progressbar" style="width: <?php print $total_eos_blank_percent; ?>%" aria-valuenow="<?php print $total_eos_blank_percent; ?>" aria-valuemin="0" aria-valuemax="100">UNCLAIMED</div>
    <div class="progress-bar progress-bar-warning" role="progressbar" style="width: <?php print $total_eos_withheld_percent; ?>%" aria-valuenow="<?php print $total_eos_withheld_percent; ?>" aria-valuemin="0" aria-valuemax="100">WITHHELD</div>
</div>
<?php

print $table;