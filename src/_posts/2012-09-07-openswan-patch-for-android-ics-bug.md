---
layout: post
title: "Openswan Patch for Android ICS Bug"
date: 2012-09-07 16:22:40
category: Security
tags:
  - security
  - vpn
  - ec2
  - aws
---

I recently [setup a private VPN using an Amazon EC2 instance]({% post_url 2012-09-07-internet-security-when-travelling %}). Unfortunately, while my Samsung Galaxy Nexus, which is running Android JellyBean, has no trouble connecting to the VPN, my wife's Samsung Galaxy S2, which is running Android ICS, refused to connect. The only error on the phone was "Timeout". Very helpful. Thankfully, the authentication log on the EC2 instance revealed much more:

``` :noln /var/log/auth.log
Sep  7 14:38:22 localhost pluto[7325]: "L2TP-PSK-NAT"[32] xxx.xxx.xxx.xxx #30: sending notification PAYLOAD_MALFORMED to xxx.xxx.xxx.xxx:60138
Sep  7 14:38:24 localhost pluto[7325]: "L2TP-PSK-NAT"[32] xxx.xxx.xxx.xxx #30: byte 7 of ISAKMP NAT-OA Payload must be zero, but is not
Sep  7 14:38:24 localhost pluto[7325]: "L2TP-PSK-NAT"[32] xxx.xxx.xxx.xxx #30: malformed payload in packet
```

Using the information in that log, I was able to find this [bug report](http://code.google.com/p/android/issues/detail?id=23124). Apparently, Android ICS contains a bug in its implementation of ipsec-tools. Samsung is rumoured to be releasing an update to JellyBean for the Galaxy S2 very soon, but we're travelling this weekend and I would like for her phone to be able to tunnel through the VPN while we're away for security reasons (please see [earlier post]({% post_url 2012-09-07-internet-security-when-travelling %})).  

Thankfully, I found [this comment](http://code.google.com/p/android/issues/detail?id=23124#c180) on the issue, which provided a patch for Openswan that makes allowances for the ipsec-tools bug in Android ICS. Of course, this meant that I had to build Openswan from source rather than using the previously installed ```.deb``` package. No worries, let's get started.

First, as I was using a brand new EC2 instance running Ubuntu, I had to setup the build environment:

``` :noln Console
$ sudo apt-get install make gcc bison flex
$ sudo apt-get install libgmp3-dev # Required to build openswan
```

I then downloaded the Openswan sources, unpacked them, applied the patch, built and installed the patched version of Openswan, and restarted some services:

``` :noln Console
$ wget http://ftp.openswan.org/openswan/openswan-2.6.38.tar.gz
$ tar xzf openswan-2.6.38.tar.gz
$ cd openswan-2.6.38
$ wget http://android.googlecode.com/issues/attachment?aid=231240180000&name=openswan-android-ics-natoa.patch
$ patch < openswan-android-ics-natoa.patch
$ make programs
$ sudo make install
$ sudo /etc/init.d/ipsec restart
$ sudo /etc/init.d/xl2tpd restart
```

At this point, the Galaxy S2 was able to connect to the VPN.

Hopefully, the JellyBean update for the S2 will be released soon and fix this issue, at which point I can revert back to using the unpatched version of Openswan.
