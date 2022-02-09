#!/bin/bash

/root/benchmark/src/pre_script.sh
/root/benchmark/src/benchmark_script.sh /root/benchmark/
# do something to send log to server
echo $(curl "http://metadata.google.internal/computeMetadata/v1/instance/machine-type" -H "Metadata-Flavor: Google") > /tmp/machinetype
sed -i "s/\// /g" /tmp/machinetype
machinetype=$(cat /tmp/machinetype | awk '{printf($4)}')
echo "GCP InstanceType: ${machinetype}" > /tmp/machinetype

poweroff