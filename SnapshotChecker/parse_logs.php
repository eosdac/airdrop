<?php

$log_folder = '../AirdropperLogs';
if (isset($argv[2])) {
    $log_folder .= $argv[2];
}
$log_file = $log_folder . '/' . $argv[1];

if (count($argv) == 1) {
    print "Please add a log file name such as Batch0100.log\n";
    exit();
}

if (!file_exists($log_file)) {
    print $log_file . " can not be found.\n";
    print "Please add a log file name such as Batch0100.log\n";
    exit();
}

$log_data = array();

if (($handle = fopen($log_file, "r")) !== FALSE) {
    $line_data = array();
    while (($line = fgets($handle)) !== false) {
        $raw_line_data = explode(' ',$line);
        //var_dump($raw_line_data);
        if (count($raw_line_data) > 7) {
            if (trim($raw_line_data[2]) == 'trans' && trim($raw_line_data[4]) == 'TO:') {
                $line_data['address'] = trim($raw_line_data[7]);
            }
        }
        if (array_key_exists('address', $line_data) && trim($raw_line_data[0]) == '"transactionHash":') {
            $line_data['transaction_hash'] = trim($raw_line_data[1], "\" ,\t\n\r");
        }
        if (array_key_exists('address', $line_data) && array_key_exists('transaction_hash', $line_data)) {
            $log_data[] = $line_data;
            $line_data = array();
        }
    }
    fclose($handle);
}

foreach ($log_data as $log_entry) {
    print "UPDATE eos_holders SET status = 'COLLECTED', transaction_hash = '" . $log_entry['transaction_hash'] . "' WHERE status != 'WITHHELD' AND eth_address = '" . $log_entry['address'] . "';\n";
}

//var_dump($log_data);
