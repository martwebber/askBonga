
Use the files in the www folder as the base source-code.
I've modified the files obtained from the Linux Redhat server to fix backwards compatibility issues 
encountered from upgrading to php 7.2.

The bonga-link namespace host is bonga-link.kubernetesuat.safaricom.net.
The resulting url for the bonga-link app is bonga-link.kubernetesuat.safaricom.net/bongalink/.
Note that the default endpoint doesn't have an index.php file, thus accessing the above endpoint will
return a 403 forbidden error. use the /scripts/home.php path i.e 
bonga-link.kubernetesuat.safaricom.net/bongalink/scripts/home.php

There is still room for improvement:
- configuring apache htaccess rules while building the app container. We can edit the dockerfile to 
add a .htaccess file and specify access rules while building the image. 
- not all links are fully functional. To fix a broken link:
	i) refer to the error code specific to that link
	ii) modify the script in the www source-code folder
	iii) While running docker desktop:
		open a CLI and cd into the Bonga-Link-K8 folder
		run:
			docker build -f Dockerfile -t svdt5kubreg01.safaricom.net/app-bonga-link:v1.0.x
		** note that we are on v1.0.8 currently. modify x to mark the incremented version **
			docker push svdt5kubreg01.safaricom.net/app-bonga-link:v1.0.xj
	iv) Open the k8 dashboard > bonga-link namespace.
		View/Edit yaml for app-bonga-link deployment
		change image under spec > template > spec > container > image to point to the newly 
		pushed image.
	v) Update the deployment.
	
	This will pull the new image from the svdt5kubreg01.safaricom.net registry and update the container
	pods. After the resources have been created, the broken link should work just fine.
		
ADDING NEW HOST TO THE HOSTS
1. Login to the k8 cluster > switch to bonga-link namespace
2. Select "View/Edit yaml" under app-bonga-link deployment options.
3. Add the following json to in containers json array [] under spec>template>spec>containers
	(Add a , to separate hostAliases from previous array element)

	"hostAliases": [
	{
		"ip": "127.0.0.2",
		"hostname": ["hostI.name.net"]
	},
	{
		"ip": "127.0.0.3",
		"hostname": ["hostII.name.net"]
	}
	]

** edit ip and hostname accordingly. This entry can have an unbounded number of
ip-hostname entries. **

4. Update the deployment.

YAML DEPLOYMENT FILES AND INGRESS RULES.
Refer to the deployment.yaml, ingress-rules.json for creation of app-bonga-link resources.