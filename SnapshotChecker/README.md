This is where the official snapshot will be kept with tools to allow people to check what the snapshot says per account

airdrop.sql:
	Schema for airdrop MySQL database

index.php:
	Simple page for querying the database.
	Be sure to add mysql credentials as needed.

create_sql.php:
	Clears the database and creates INSERT statements to populate the data based on a snapshot.

export_requested_to_be_processed.sql:
	Used to export a csv file of requested airdrops below 100 EOS.
	Example:
		`mysql -u airdrop_user -p airdrop < export_requested_to_be_processed.sql | sed 's/\t/,/g' > requests.csv`

parse_logs.php:
	Used to parse the logs and create update sql statements.

parse_log_sequence.sh:
	Used to process a batch of log files.
	Example:
		`./parse_log_sequence.sh 51 100`
		`mysql -u airdrop_user -p airdrop < update.sql`


