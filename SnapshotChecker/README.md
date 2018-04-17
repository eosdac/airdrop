# Snapshot and Airdrop Tools for validating and updating our airdrop data

airdrop.sql:

* Schema for airdrop MySQL database

index.php:

* Simple page for querying the database.
* Be sure to add mysql credentials as needed.

create_sql.php:

* Clears the database and creates INSERT statements to populate the data based on a snapshot.

export_requested_to_be_processed.sql:

* Used to export a csv file of requested airdrops below 100 EOS.
* Example:
    * `mysql -u <user> -p <db> < export_requested_to_be_processed.sql | sed 's/\t/,/g' > requests.csv`

parse_logs.php:

* Used to parse the logs and create update sql statements.

parse_log_sequence.sh:

* Used to process a batch of log files.
* Example:
    * `./parse_log_sequence.sh 51 100`
    * `mysql -u <user> -p <db> < update.sql`

# Procedures

## Updating the Airdrop Tool Code.

1) Commit to git and push.
1) Login to the webserver.
1) cd airdrop
1) git pull
1) cd SnapshotChecker
1) `sudo cp index.php /var/www/html/airdrop/index_hidden.php`
1) Test at https://eosdac.io/airdrop/index_hidden.php
1) If needed, make changes and go back to step 1.
1) When satisfied `sudo cp index.php /var/www/html/airdrop/index.php` 

## Updating the Airdrop Data with COMPLETED transactions

1) Login to the webserver.
1) `mysql -u <user> -p <db>`
1) `use airdrop;`
1) Run `select count(*), status from eos_holders GROUP BY status;` and note the output
1) Login to the webserver in a second window.
1) cd airdrop
1) git pull
1) cd SnapshotChecker
1) `./parse_log_sequence.sh 101 200` (or whatever batch amount you want to use)
1) View update.sql to make sure it's valid.
1) `mysql -u <user> -p <db> < update.sql`
1) Run `select count(*), status from eos_holders GROUP BY status;` in the other window and check the results
