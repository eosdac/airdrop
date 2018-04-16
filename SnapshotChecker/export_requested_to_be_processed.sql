-- RUN this SQL file like so to create a CSV file:
-- mysql -u airdrop_user -p airdrop < export_requested_to_be_processed.sql | sed 's/\t/,/g' > requests.csv

USE airdrop;
SELECT eth_address,eos_amount FROM eos_holders WHERE eos_amount < 100 and status = 'REQUESTED';
