---
layout: post
title: "VirtualBox Host-only Adater for Ubuntu VM"
date: 2013-02-25 13:17:53
category: Lyfebites
tags:
  - ubuntu
  - virtualbox
  - vm
  - networking
---

Just some notes to help me next time I go about setting up an Ubunutu VM

## Configure host-only interface
``` :noln /etc/network/interfaces
# The loopback interface
auto lo
iface lo inet loopback

# The primary network interface
auto eth0
iface eth0 inet dhcp

# The secondary network interface
auto eth1
iface eth1 inet static
    address 192.168.56.101
    netmask 255.255.255.0
    gateway 192.168.56.1
```

`sudo /etc/init.d/networking restart`

## Update system
``` :noln Console
sudo apt-get update
sudo apt-get upgrade
sudo reboot
```

### Build PHP v5.3.22 on Ubuntu 12.04.1 TLS
``` :noln Console
sudo apt-get install build-essential
sudo apt-get install dpkg-dev # necessary?
sudo apt-get install apache2-prefork-dev libpng-dev libmcrypt-dev libjpeg-dev libicu-dev libreadline-dev libxslt-dev libxml2-dev libcurl4-dev libbz2-dev libmhash-dev libltdl-dev
```

### Build PHP v5.4.x on Ubunutu 12.04.1 TLS
`???`

