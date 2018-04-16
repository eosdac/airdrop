#!/bin/bash
echo '' >  update.sql
args=("$@")
start=${args[0]}
end=${args[1]}
if ((start >= end)); then
    echo "Please include start and end paramters."
    exit
fi
for ((i = $start; i <= $end; i++));
do
   filenumber=$(printf "%04d\n" "$i")
   php parse_logs.php Batch$filenumber.log >> update.sql
   echo "Batch$filenumber.log parsed and added to update.sql"
done
