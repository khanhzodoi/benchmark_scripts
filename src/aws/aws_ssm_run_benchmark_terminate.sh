#!/bin/bash

MONTH=$(date -d "$D" '+%m')
YEAR=$(date -d "$D" '+%Y')
NEW_S3_FOLDER=$MONTH-$YEAR
sudo bash /benchmark/benchmark_scripts/src/pre_script.sh 
#sudo bash /benchmark/benchmark_scripts/src/benchmark_script.sh /test
sudo aws s3 cp /benchmark/benchmark_scripts/src/pre_script.sh s3://cloud-instances-benchmark-log/$NEW_S3_FOLDER/
sudo shutdown +1
