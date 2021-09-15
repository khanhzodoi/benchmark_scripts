#!/bin/bash


# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[0;33m'
PLAIN='\033[0m'

# check root
[[ $EUID -ne 0 ]] && echo -e "${RED}Error:${PLAIN} This script must be run as root!" && exit 1


next() {
	printf "%-70s\n" "-" | sed 's/\s/-/g'
}

speed_test() {
	local speedtest=$(wget -4O /dev/null -T300 $1 2>&1 | awk '/\/dev\/null/ {speed=$3 $4} END {gsub(/\(|\)/,"",speed); print speed}')
	local ipaddress=$(ping -c1 -4 -n `awk -F'/' '{print $3}' <<< $1` | awk -F '[()]' '{print $2;exit}')
	local nodeName=$2
	printf "${YELLOW}%-40s${GREEN}%-16s${RED}%-14s${PLAIN}\n" "${nodeName}" "${ipaddress}" "${speedtest}"
}

speed_test_v6() {
	local speedtest=$(wget -6O /dev/null -T300 $1 2>&1 | awk '/\/dev\/null/ {speed=$3 $4} END {gsub(/\(|\)/,"",speed); print speed}')
	local ipaddress=$(ping6 -c1 -n `awk -F'/' '{print $3}' <<< $1` | awk -F '[()]' '{print $2;exit}')
	local nodeName=$2
	printf "${YELLOW}%-40s${GREEN}%-16s${RED}%-14s${PLAIN}\n" "${nodeName}" "${ipaddress}" "${speedtest}"
}

speed() {
	speed_test 'http://cachefly.cachefly.net/100mb.test' 'CacheFly'
	speed_test 'https://lax-ca-us-ping.vultr.com/vultr.com.100MB.bin' 'Vultr, Los Angeles, CA'
	speed_test 'https://wa-us-ping.vultr.com/vultr.com.100MB.bin' 'Vultr, Seattle, WA'
	speed_test 'http://speedtest.tokyo2.linode.com/100MB-tokyo.bin' 'Linode, Tokyo, JP'
	speed_test 'http://speedtest.singapore.linode.com/100MB-singapore.bin' 'Linode, Singapore, SG'
	speed_test 'http://speedtest.hkg02.softlayer.com/downloads/test100.zip' 'Softlayer, HongKong, CN'
	speed_test 'http://speedtest1.vtn.com.vn/speedtest/random4000x4000.jpg' 'VNPT, Ha Noi, VN'
	speed_test 'http://speedtest5.vtn.com.vn/speedtest/random4000x4000.jpg' 'VNPT, Da Nang, VN'
	speed_test 'http://speedtest3.vtn.com.vn/speedtest/random4000x4000.jpg' 'VNPT, Ho Chi Minh, VN'
	speed_test 'http://speedtestkv1a.viettel.vn/speedtest/random4000x4000.jpg' 'Viettel Network, Ha Noi, VN'
	speed_test 'http://speedtestkv2a.viettel.vn/speedtest/random4000x4000.jpg' 'Viettel Network, Da Nang, VN'
	speed_test 'http://speedtestkv3a.viettel.vn/speedtest/random4000x4000.jpg' 'Viettel Network, Ho Chi Minh, VN'
	speed_test 'http://speedtesthn.fpt.vn/speedtest/random4000x4000.jpg' 'FPT Telecom, Ha Noi, VN'
	speed_test 'http://speedtest.fpt.vn/speedtest/random4000x4000.jpg' 'FPT Telecom, Ho Chi Minh, VN'
}

fio_test() {
	# Khanh
	if [ -e '/usr/bin/fio' ]; then
		echo "Fio Test"
		local tmp=$(mktemp)
		fio --randrepeat=1 --ioengine=libaio --direct=1 --gtod_reduce=1 --name=fio_test --filename=fio_test --bs=4k --numjobs=1 --iodepth=64 --size=256M --readwrite=randrw --rwmixread=75 --runtime=30 --time_based --output="$tmp"
		
		if [ $(fio -v | cut -d '.' -f 1) == "fio-2" ]; then
			local iops_read=`grep "iops=" "$tmp" | grep read | awk -F[=,]+ '{print $6}'`
			local iops_write=`grep "iops=" "$tmp" | grep write | awk -F[=,]+ '{print $6}'`
			local bw_read=`grep "bw=" "$tmp" | grep read | awk -F[=,B]+ '{if(match($4, /[0-9]+K$/)) {printf("%.1f", int($4)/1024);} else if(match($4, /[0-9]+M$/)) {printf("%.1f", substr($4, 0, length($4)-1))} else {printf("%.1f", int($4)/1024/1024);}}'`"MB/s"
			local bw_write=`grep "bw=" "$tmp" | grep write | awk -F[=,B]+ '{if(match($4, /[0-9]+K$/)) {printf("%.1f", int($4)/1024);} else if(match($4, /[0-9]+M$/)) {printf("%.1f", substr($4, 0, length($4)-1))} else {printf("%.1f", int($4)/1024/1024);}}'`"MB/s"
			
		elif [ $(fio -v | cut -d '.' -f 1) == "fio-3" ]; then
			local iops_read=`grep "IOPS=" "$tmp" | grep read | awk -F[=,]+ '{print $2}'`
			local iops_write=`grep "IOPS=" "$tmp" | grep write | awk -F[=,]+ '{print $2}'`
			local bw_read=`grep "bw=" "$tmp" | grep READ | awk -F"[()]" '{print $2}'`
			local bw_write=`grep "bw=" "$tmp" | grep WRITE | awk -F"[()]" '{print $2}'`
		fi

		echo "Read performance     : $bw_read"
		echo "Read IOPS            : $iops_read"
		echo "Write performance    : $bw_write"
		echo "Write IOPS           : $iops_write"
		
		rm -f $tmp fio_test
	else
		echo "Fio is missing!!! Please install Fio before running test."
	fi
}

passmark_cpu() {
	# Khanh 
}

passmark_memory() {
	# Tuong 
	pt_linux_x64 -p 2 -i 2 -d 2 -r 2

	local FILE=results_memory.yml
	

	sed -i "30s/ME_ALLOC_S/Database Operations/" $FILE
	sed -i "30s/$/ Thousand Operations\/s/" $FILE
	sed -i "31s/ME_READ_S/Memory Read Cached/" $FILE
	sed -i "31s/$/ MB\/s/" $FILE
	sed -i "32s/ME_READ_L/Memory Read Uncached/" $FILE
	sed -i "32s/$/ MB\/s/" $FILE
	sed -i "33s/ME_WRITE/Memory Write/" $FILE
	sed -i "33s/$/ MB\/s/" $FILE
	sed -i "34s/ME_LARGE/Available RAM/" $FILE
	sed -i "35s/ME_LATENCY/Memory Latency/" $FILE
	sed -i "35s/$/ Nanoseconds/" $FILE
	sed -i "36s/ME_THREADED/Memory Threaded/" $FILE

	sed -n '30,36p;37q' $FILE

}

iozone_filesystem() {
	# Son 
}


test() {

	echo "Date                 : $date"
	echo ""
	echo "Disk Speed"
	next
	fio_test $cores
	echo ""
	echo "CPU Speed"
	next
	sysbench_cpu && next
	echo ""
	echo "Speedtest"
	next
	printf "%-40s%-16s%-14s\n" "Node Name" "IPv4 address" "Download Speed"
	speed && next
	echo "Memory Test"
	next
	passmark_memory && next
}


clear
tmp=$(mktemp)
test | tee $tmp
cat $tmp >> ~/benchmark.log