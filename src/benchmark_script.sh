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
	speed_test 'http://speedtest.fpt.vn/speedtest/random4000x4000.jpg' 'FPT Telecom, Ho Chi Minh, VN'
}

fio_test() {
	# Khanh
	if [ -e '/usr/bin/fio' ]; then
		echo "Fio Test"
		local tmp_1=$(mktemp)
		local tmp_2=$(mktemp)
		local tmp_3=$(mktemp)
		local tmp_4=$(mktemp)
		local test_dir="/tmp/fio_test"
		mkdir $test_dir > /dev/null 2>&1

		sudo fio --name=write_throughput --directory=$test_dir --numjobs=8 --size=4G --time_based --runtime=60s --ramp_time=2s --ioengine=libaio --direct=1 --verify=0 --bs=1M --iodepth=64 --rw=write --group_reporting=1 --output="$tmp_1"
		if [ $(fio -v | cut -d '.' -f 1) == "fio-2" ]; then
			local seq_iops_write=`grep "iops=" "$tmp_1" | grep write | awk -F[=,]+ '{print $6}'`
			local seq_bw_write=`grep "bw=" "$tmp_1" | grep write | awk -F[=,B]+ '{if(match($4, /[0-9]+K$/)) {printf("%.1f", int($4)/1024);} else if(match($4, /[0-9]+M$/)) {printf("%.1f", substr($4, 0, length($4)-1))} else {printf("%.1f", int($4)/1024/1024);}}'`"MB/s"
		elif [ $(fio -v | cut -d '.' -f 1) == "fio-3" ]; then
			local seq_iops_write=`grep "IOPS=" "$tmp_1" | grep write | awk -F[=,]+ '{print $2}'`
			local seq_bw_write=`grep "bw=" "$tmp_1" | grep WRITE | awk -F"[()]" '{print $2}'`
		fi
		rm -rf $test_dir/*

		sudo fio --name=write_iops --directory=$test_dir --size=4G --time_based --runtime=60s --ramp_time=2s --ioengine=libaio --direct=1 --verify=0 --bs=4K --iodepth=64 --rw=randwrite --group_reporting=1 --output="$tmp_2"
		if [ $(fio -v | cut -d '.' -f 1) == "fio-2" ]; then
			local rand_iops_write=`grep "iops=" "$tmp_2" | grep write | awk -F[=,]+ '{print $6}'`
			local rand_bw_write=`grep "bw=" "$tmp_2" | grep write | awk -F[=,B]+ '{if(match($4, /[0-9]+K$/)) {printf("%.1f", int($4)/1024);} else if(match($4, /[0-9]+M$/)) {printf("%.1f", substr($4, 0, length($4)-1))} else {printf("%.1f", int($4)/1024/1024);}}'`"MB/s"
		elif [ $(fio -v | cut -d '.' -f 1) == "fio-3" ]; then
			local rand_iops_write=`grep "IOPS=" "$tmp_2" | grep write | awk -F[=,]+ '{print $2}'`
			local rand_bw_write=`grep "bw=" "$tmp_2" | grep WRITE | awk -F"[()]" '{print $2}'`
		fi
		rm -rf $test_dir/*

		sudo fio --name=read_throughput --directory=$test_dir --numjobs=8 --size=4G --time_based --runtime=60s --ramp_time=2s --ioengine=libaio --direct=1 --verify=0 --bs=1M --iodepth=64 --rw=read --group_reporting=1 --output="$tmp_3"
		if [ $(fio -v | cut -d '.' -f 1) == "fio-2" ]; then
			local seq_iops_read=`grep "iops=" "$tmp_3" | grep read | awk -F[=,]+ '{print $6}'`
			local seq_bw_read=`grep "bw=" "$tmp_3" | grep read | awk -F[=,B]+ '{if(match($4, /[0-9]+K$/)) {printf("%.1f", int($4)/1024);} else if(match($4, /[0-9]+M$/)) {printf("%.1f", substr($4, 0, length($4)-1))} else {printf("%.1f", int($4)/1024/1024);}}'`"MB/s"			
		elif [ $(fio -v | cut -d '.' -f 1) == "fio-3" ]; then
			local seq_iops_read=`grep "IOPS=" "$tmp_3" | grep read | awk -F[=,]+ '{print $2}'`
			local seq_bw_read=`grep "bw=" "$tmp_3" | grep READ | awk -F"[()]" '{print $2}'`
		fi
		rm -rf $test_dir/*

		sudo fio --name=read_iops --directory=$test_dir --size=4G --time_based --runtime=60s --ramp_time=2s --ioengine=libaio --direct=1 --verify=0 --bs=4K --iodepth=64 --rw=randread --group_reporting=1 --output="$tmp_4"
		if [ $(fio -v | cut -d '.' -f 1) == "fio-2" ]; then
			local rand_iops_read=`grep "iops=" "$tmp_4" | grep read | awk -F[=,]+ '{print $6}'`
			local rand_bw_read=`grep "bw=" "$tmp_4" | grep read | awk -F[=,B]+ '{if(match($4, /[0-9]+K$/)) {printf("%.1f", int($4)/1024);} else if(match($4, /[0-9]+M$/)) {printf("%.1f", substr($4, 0, length($4)-1))} else {printf("%.1f", int($4)/1024/1024);}}'`"MB/s"			
		elif [ $(fio -v | cut -d '.' -f 1) == "fio-3" ]; then
			local rand_iops_read=`grep "IOPS=" "$tmp_4" | grep read | awk -F[=,]+ '{print $2}'`
			local rand_bw_read=`grep "bw=" "$tmp_4" | grep READ | awk -F"[()]" '{print $2}'`
		fi
		rm -rf $test_dir/*

		echo -e "Random read performance      : ${RED}IOPS =${PLAIN} $rand_iops_read, BW = $rand_bw_read"
		echo -e "Random write performance     : ${RED}IOPS =${PLAIN} $rand_iops_write, BW = $rand_bw_write"
		echo -e "Sequential read perfomance   : IOPS = $seq_iops_read, ${RED}BW =${PLAIN} $seq_bw_read"
		echo -e "Sequential write performance : IOPS = $seq_iops_write, ${RED}BW =${PLAIN} $seq_bw_write"

		# Cleanup temp files
		rm -rf $tmp_1 $tmp_2 $tmp_3 $tmp_4 $test_dir
	else
		echo "Fio is missing!!! Please install Fio before running test."
	fi
}

passmark_cpu() {
	# Khanh
	if [ -e '/usr/bin/pt_linux_x64' ]; then
		echo "Passmark Test"
		local result_filename="results_cpu.yml"

		cd /tmp > /dev/null 2>&1
		pt_linux_x64 -p 4 -i 3 -d 2 -r 1 > /dev/null 2>&1

		local cpu_mark=`grep "SUMM_CPU:" "$result_filename"| cut -d ":" -f 2 | awk '{printf("%.0f", $1)}'`
		local cpu_integer_math=`grep "CPU_INTEGER_MATH:" "$result_filename"| cut -d ":" -f 2 | awk '{printf("%.0f Million Operations/s", $1)}'`
		local cpu_floatingpoint_math=`grep "CPU_FLOATINGPOINT_MATH:" "$result_filename"| cut -d ":" -f 2 | awk '{printf("%.0f Million Operations/s", $1)}'`
		local cpu_sorting=`grep "CPU_SORTING:" "$result_filename"| cut -d ":" -f 2 | awk '{printf("%.0f Million Primes/s", $1)}'`
		local cpu_prime=`grep "CPU_PRIME:" "$result_filename"| cut -d ":" -f 2 | awk '{printf("%.0f Thousand Strings/s", $1)}'`
		local cpu_encryption=`grep "CPU_ENCRYPTION:" "$result_filename"| cut -d ":" -f 2 | awk '{printf("%.0f MB/s", $1)}'`
		local cpu_compression=`grep "CPU_COMPRESSION:" "$result_filename"| cut -d ":" -f 2 | awk '{printf("%.0f MB/s", $1)}'`
		local cpu_sse=`grep "CPU_sse:" "$result_filename"| cut -d ":" -f 2 | awk '{printf("%.0f Million Matrices/s", $1)}'`
		local cpu_singlethreaded=`grep "CPU_SINGLETHREAD:" "$result_filename"| cut -d ":" -f 2 | awk '{printf("%.0f Million Operations/s", $1)}'`

		echo "CPU Mark                   : $cpu_mark"
		echo "Integer Math               : $cpu_integer_math"
		echo "Floating Point Math        : $cpu_floatingpoint_math"
		echo "Prime Numbers              : $cpu_prime"
		echo "Sorting                    : $cpu_sorting"
		echo "Encryption                 : $cpu_encryption"
		echo "Compression                : $cpu_compression"
		echo "CPU Single Threaded        : $cpu_singlethreaded"
		echo "Extended Instructions(SSE) : $cpu_sse"

		rm -rf /tmp/results_cpu.yml
		cd - > /dev/null 2>&1

	else
		echo "Passmark is missing!!! Please install Passmark before running test."
	fi
}

passmark_memory() {  
	# Tuong 
	if [ -e '/usr/bin/pt_linux_x64' ]; then
		echo "Passmark Test"
		local FILE=results_memory.yml

		cd /tmp > /dev/null 2>&1
		pt_linux_x64 -p 2 -i 2 -d 2 -r 2 > /dev/null 2>&1

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

		rm -rf /tmp/$FILE
		cd - > /dev/null 2>&1

	else
		echo "Passmark is missing!!! Please install Passmark before running test."
	fi

}

iozone_filesystem() {
	# Son
	if [ -e '/usr/bin/iozone' ]; then
		echo "IOZone Test"
		local TEST_FILE_SIZE=5242880 #file size temporary iozone using to test, recommend x3 size of memmory. Reference https://www.thegeekstuff.com/2011/05/iozone-examples/
		local TEST_RECORD_SIZE=1024 #1M 
		local name_output_file="/tmp/$(date +%Y-%m-%d_%H-%M-%S).iozone"

		echo "$(iozone -s $TEST_FILE_SIZE -r $TEST_RECORD_SIZE -i 0 -i 1 -i 2 -b tmp_file)" > "$name_output_file"

        local initial_write=`grep $TEST_FILE_SIZE "$name_output_file" | grep $TEST_RECORD_SIZE | grep -v "iozone" | awk '{printf($3)}'`
        local rewrite=`grep $TEST_FILE_SIZE "$name_output_file" | grep $TEST_RECORD_SIZE | grep -v "iozone" | awk '{printf($4)}'`
        local read=`grep $TEST_FILE_SIZE "$name_output_file" | grep $TEST_RECORD_SIZE | grep -v "iozone" | awk '{printf($5)}'`
        local re_read=`grep $TEST_FILE_SIZE "$name_output_file" | grep $TEST_RECORD_SIZE | grep -v "iozone" | awk '{printf($6)}'`
        local rand_read=`grep $TEST_FILE_SIZE "$name_output_file" | grep $TEST_RECORD_SIZE | grep -v "iozone" | awk '{printf($7)}'`
        local rand_write=`grep $TEST_FILE_SIZE "$name_output_file" | grep $TEST_RECORD_SIZE | grep -v "iozone" | awk '{printf($8)}'`

		printf "Write performance			:${GREEN}%-16s${PLAIN}\n" "${initial_write}kB/s"
		printf "Re-write performance			:${GREEN}%-16s${PLAIN}\n" "${rewrite}kB/s"
		printf "Read performance			:${GREEN}%-16s${PLAIN}\n" "${read}kB/s"
		printf "Re_read performance			:${GREEN}%-16s${PLAIN}\n" "${re_read}kB/s"
		printf "Random-write performance		:${GREEN}%-16s${PLAIN}\n" "${rand_write}kB/s"
		printf "Random-read performance			:${GREEN}%-16s${PLAIN}\n" "${rand_read}kB/s"
        	rm -f tmp_file
		rm -f "/tmp/$name_output_file" #remove log file
	else
		echo "IOZone is missing!!! Please install IOZone before running test."
	fi
}


test() {
	date=$( date )

	echo "Date                 : $(date +%Y-%m-%d_%H-%M-%S)"
	echo ""

	echo "CPU Speed"
	next
	passmark_cpu && next
	echo ""

	echo "Memory Speed"
	next
	passmark_memory && next
	echo ""
	
	echo "Filesystem Speed"
	next
	iozone_filesystem && next
	echo ""

	echo "Disk Speed"
	next
	fio_test && next
	echo ""

	echo "Network Speedtest"
	next
	printf "%-40s%-16s%-14s\n" "Node Name" "IPv4 address" "Download Speed"
	speed && next

}


clear
tmp=$(mktemp)
test | tee $tmp
cat $tmp >> ~/benchmark.log
rm -rf $tmp
