<?php

// NOTES:

/*
eosDAC php create_sql.php > clear_and_refresh_eos_holders_distribution_297.sql
eosDAC sed -i '' '$ s/.$//' clear_and_refresh_eos_holders_distribution_297.sql 
*/


$snapshot_filename = 'distribution_297.csv';

print "TRUNCATE TABLE eos_holders;\n";
print "INSERT INTO `eos_holders` (`eth_address`, `eos_amount`, `status`, `transaction_hash`) VALUES\n";

if (($handle = fopen($snapshot_filename, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        print "('" . $data[0] . "','" . $data[1] . "','',''),\n";
    }
    fclose($handle);
}