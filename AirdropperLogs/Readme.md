This directory contains the logs from the eosDAC airdrop

Noted Issues : 

1. First Transaction was seperated
2. Batch 0031 had timeout - however all transactions appear to have been loaded
3. Batch 0102 had wrong nonce set and rerun caused erasure of logs - Believe run but need to check addresses from this batch
4. Batch 0107 also logged batch 0108 because counter wasn't advanced.
5. Batch 0485 also logged batch 0486 because counter wasn't advanced
6. Batch 0499 has an unexpected nonce issue - Will need to be picked up during end checks.. 
7. Batch 0573 appears to have fallen victim from a temporary ethereum gas price spike which made some timeout  - Will need to be picked up during end checks. - note to keep constant check on fast gas price.
8. Batch 0675 had an issue on final transaction - not logged 
9. Batch 0707 had gas price spike issue - last few transactions may have been affected - Will need to be picked up during end checks
10. Batch 0788 also logged batch 0789 because counter wasn't advanced.


