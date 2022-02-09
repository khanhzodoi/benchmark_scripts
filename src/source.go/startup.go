package cloudfunctions

import (
	"context"
	"fmt"
	"log"
	"net/http"
	"os"
	"google.golang.org/api/compute/v1"
	"time"
)

var ProjectID = ""
var Zone = ""
var Region = ""
var InstanceName = ""
var InstanceType = ""
var IDiskName = ""

func DeployInstance(w http.ResponseWriter, r *http.Request) {
	ProjectID = os.Getenv("PROJECT_ID")
	Zone = os.Getenv("ZONE")
	Region = os.Getenv("REGION")

	var listInstance = []string{"e2-standard-8","e2-standard-2",  "n2-standard-2", "n2-standard-8", "n1-custom-2-8192", "n1-custom-8-32768", "c2-standard-8"}
	for i:=0; i < len(listInstance); i++ {
		cs, err := compute.NewService(context.Background())
		if err != nil {
			w.WriteHeader(http.StatusInternalServerError)
			log.Fatal(err)
		}
		InstanceType = listInstance[i]
		InstanceName = "benchmark-"+InstanceType
		IDiskName = InstanceName+"-boot-disk"

		instance, err := GetInstance(cs)
		if err != nil {
			w.WriteHeader(http.StatusTemporaryRedirect)
			w.Write([]byte(err.Error() + " instance may not exist yet"))

			_, err = CreateInstance(cs)
			if err != nil {
				for {
					disk, derr := cs.Disks.Get(ProjectID, Zone, IDiskName).Context(context.Background()).Do()
					log.Print(IDiskName + " is " + disk.Status)
					time.Sleep(1 * time.Second)
					if derr != nil {
						startInstance(cs, w)
						break
					}
				}
				
			}
		} else {
			msg := "instance "+ InstanceName +" is in intermediate state: " + instance.Status
			w.WriteHeader(http.StatusAccepted)
			w.Write([]byte(msg))
			log.Println(msg)
		}
	}
}


func GetInstance(computeService *compute.Service) (*compute.Instance, error) {
	return computeService.Instances.Get(ProjectID, Zone, InstanceName).Do()
}


func StartInstance(computeService *compute.Service) (*compute.Operation, error) {
	return computeService.Instances.Start(ProjectID, Zone, InstanceName).Do()
}

// CreateInstance creates a given instance with metadata that logs its information.
func CreateInstance(computeService *compute.Service) (*compute.Operation, error) {
	startupMetadata := "#! /bin/bash\nyum -y install git\ngit clone https://github.com/khanhzodoi/benchmark_scripts.git /root/benchmark\nchmod +x /root/benchmark/src/*.sh\n./root/benchmark/src/gcp-startup-script.sh"
	instance := &compute.Instance{
		Name: InstanceName,
		MachineType: fmt.Sprintf("zones/%s/machineTypes/%s", Zone, InstanceType),
		NetworkInterfaces: []*compute.NetworkInterface{
			{
				Name:       "default",
				Subnetwork: fmt.Sprintf("projects/%s/regions/%s/subnetworks/default", ProjectID, Region),
				AccessConfigs: []*compute.AccessConfig{
					{
						Name:        "External NAT",
						Type:        "ONE_TO_ONE_NAT",
						NetworkTier: "PREMIUM",
					},
				},
			},
		},
		Scheduling: &compute.Scheduling{
			Preemptible: true,
		},
		Disks: []*compute.AttachedDisk{
			{
				Boot:       true,         // The first disk must be a boot disk.
				AutoDelete: true,         //Optional
				Mode:       "READ_WRITE", //Mode should be READ_WRITE or READ_ONLY
				Interface:  "SCSI",       //SCSI or NVME - NVME only for SSDs
				InitializeParams: &compute.AttachedDiskInitializeParams{
					DiskName: IDiskName,
					SourceImage: "projects/centos-cloud/global/images/family/centos-7",
					DiskType:    fmt.Sprintf("projects/%s/zones/%s/diskTypes/pd-ssd", ProjectID, Zone),
					DiskSizeGb:  100,
				},
			},
		},
		Metadata: &compute.Metadata{
			Items: []*compute.MetadataItems{
				{
					Key:   "startup-script",
					Value: &startupMetadata,
				},
				// {
				// 	Key:   "shutdown-script",
				// 	Value: &shutdownMetadata,
				// },
			},
		},
	}
	return computeService.Instances.Insert(ProjectID, Zone, instance).Do()
}

// startInstance is a wrapper function for the switch statement
func startInstance(cs *compute.Service, w http.ResponseWriter) {
	operation, err := StartInstance(cs)
	if err != nil {
		w.WriteHeader(http.StatusInternalServerError)
		log.Fatal(err)
	}
	w.WriteHeader(http.StatusOK)
	data, _ := operation.MarshalJSON()
	w.Write(data)

}
// https://cloud.google.com/compute/docs/disks#disk-types
// https://cloud.google.com/compute/docs/disks/extreme-persistent-disk
// https://cloud.google.com/compute/docs/reference/rest/v1/instances/attachDisk
// https://cloud.google.com/compute/docs/instances/creating-instance-with-custom-machine-type#api_2
// https://cloud.google.com/compute/docs/images/os-details#general-info