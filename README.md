# BergclubBern

## HowTo Code

This project has an Vagrant configuration File in the root. The general procedure is:

1. Install Vagrant (https://www.vagrantup.com/) and VirtualBox (https://www.virtualbox.org/)
2. Clone this repository in a directory
3. Navigate to that directory in a terminal and run the command `vagrant up`

Now a virtual machine is created and provisioned. If for some reason the configuration failes, you can retry the provisioning with `vagrant provision`. If for some reason this also fails, try to destroy the local environment with `vagrant destroy` and do a `vagrant up` afterwards.

If you are finished with your work, you can commit your changes (bear in mind our git workflow) and halt the virtual machine with `vagrant halt`.

## Wordpress installation
1. The wordpress site is accessible through IP (192.168.33.12) or through the URL vccm-all.dev 
2. The username / password for the wp site is 'admin'/'admin'

