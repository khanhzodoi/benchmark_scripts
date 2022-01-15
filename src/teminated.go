package cloudfunctions

import (
	"context"
	"log"
	"net/http"
	"os"
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
		if instance.Status == "STOPPED" {
			operation, err := cs.Instances.Delete(ProjectID, Zone, InstanceName).Context(ctx).Do()
			if err != nil {
				w.WriteHeader(http.StatusInternalServerError)
				log.Fatal(err)
			}
			w.WriteHeader(http.StatusOK)
			data, _ := operation.MarshalJSON()
			w.Write(data)
		}

	}
}

// https://cloud.google.com/compute/docs/disks#disk-types
// https://cloud.google.com/compute/docs/disks/extreme-persistent-disk
// https://cloud.google.com/compute/docs/reference/rest/v1/instances/attachDisk
// https://cloud.google.com/compute/docs/instances/creating-instance-with-custom-machine-type#api_2
// https://cloud.google.com/compute/docs/images/os-details#general-info
