package cloudfunctions

import (
	"context"
	"log"
	"net/http"
	"os"
	"time"
	"google.golang.org/api/compute/v1"
)

var ProjectID = ""
var Zone = ""
var Region = ""
var InstanceName = ""

func DeployInstance(w http.ResponseWriter, r *http.Request) {
	ProjectID = os.Getenv("PROJECT_ID")
	Zone = os.Getenv("ZONE")
	Region = os.Getenv("REGION")
	InstanceName = os.Getenv("INSTANCE_NAME")

	listInstance := [...]string{"e2-standard-2", "e2-standard-8", "n2-standard-2", "n2-standard-8", "n1-custom-2-8192", "n1-custom-8-32768", "c2-standard-8" }
	
	index := 0
	for ok := true; ok; ok = ( index <  len(listInstance)) {
		InstanceName := listInstance[index]
		ctx := context.Background()
		cs, err := compute.NewService(ctx)
		if err != nil {
			w.WriteHeader(http.StatusInternalServerError)
			log.Fatal(err)
		}
		instance, err := cs.Instances.Get(ProjectID, Zone, InstanceName).Do()
		if err != nil {
			w.WriteHeader(http.StatusTemporaryRedirect)
			w.Write([]byte(err.Error() + " instance may not exist yet"))
			log.Print(err)
	
		} else 
		{
			for ok := true; ok; ok = ( instance.Status !=  "TERMINATED") {
				time.Sleep(60 * time.Second)
			}
	
			operation, err := cs.Instances.Delete(ProjectID, Zone, InstanceName).Context(ctx).Do()
			if err != nil {
				w.WriteHeader(http.StatusInternalServerError)
				log.Fatal(err)
			}
			w.WriteHeader(http.StatusOK)
			data, _ := operation.MarshalJSON()
			w.Write(data)
		
			w.Write([]byte(" INSTANCE STATUS AFTER delete: " + instance.Status))
			log.Print(instance.Status)
	
		}
		index := index +1
	}
	
}

// https://cloud.google.com/compute/docs/disks#disk-types
// https://cloud.google.com/compute/docs/disks/extreme-persistent-disk
// https://cloud.google.com/compute/docs/reference/rest/v1/instances/attachDisk
// https://cloud.google.com/compute/docs/instances/creating-instance-with-custom-machine-type#api_2
// https://cloud.google.com/compute/docs/images/os-details#general-info
