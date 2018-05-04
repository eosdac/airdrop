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
   if ((start <= 1000))
   then
      php parse_logs.php Batch$filenumber.log >> update.sql
   else
      php parse_logs.php Batch$filenumber.log 2 >> update.sql
   fi
   echo "Batch$filenumber.log parsed and added to update.sql"
done
