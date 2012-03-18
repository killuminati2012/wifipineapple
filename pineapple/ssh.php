<?php 

if (isset($_GET[deletekey])) {
	exec("rm -rf /etc/dropbear/id_rsa");
	echo "<pre>SSH Key Pair Removed from /etc/dropbear/id_rsa</pre>";
	}

if (isset($_GET[generatekey])) {
	exec("rm -rf /etc/dropbear/id_rsa");
	exec("dropbearkey -t rsa -f /etc/dropbear/id_rsa");
	echo "<pre>SSH Key Pair Generated and stored in /etc/dropbear/id_rsa</pre>";
	}

if (isset($_GET[connect])) {
	if (exec("ps aux | grep [s]sh | grep -v -e ssh.php") == "") {
		echo "<pre>Starting SSH connection.</pre>";
		exec("echo /www/pineapple/ssh-connect.sh | at now");
		sleep(2);
	} else {
		echo "<pre>Process Snapshot reports SSH is already running. Try disconnecting then reconnecting.</pre>";
	}
}

if (isset($_GET[disconnect])) {
	if (exec("ps aux | grep [s]sh | grep -v -e ssh.php") == "") {
		echo "<pre>Process Snapshop reports no SSH sessions running. No session to disconnect.</pre>";
	} else {
		echo "<pre>Killing SSH session</pre>";
		exec("kill `ps aux | grep -v -e ssh.php | awk '/[s]sh/{print $1}'`");
		sleep(2);
	}
}

if (isset($_GET[enablekeepalive])) {
	if (exec("grep ssh-keepalive.sh /etc/crontabs/root") == "") {
		exec("echo '*/5 * * * * /www/pineapple/ssh-keepalive.sh' >> /etc/crontabs/root");
		echo "<pre>SSH Keep Alive script added to Cron Jobs. Be sure to enable Cron Daemon from <a href='jobs.php'><b>Jobs</b></a>.</pre>";
	} else {
		echo "<pre>SSH Keep Alive script already in Crontab. Check <a href='jobs.php'><b>Jobs</b></a> to troubleshoot.</pre>";
	}
}

if (isset($_GET[disablekeepalive])) {
	exec("sed -i '/ssh-keepalive.sh/d' /etc/crontabs/root");
	echo "<pre>SSH Keep Alive script removed from Cron Jobs. See <a href='jobs.php'><b>Jobs</b></a></pre>";
}



$sshonboot = (exec("grep ssh-connect.sh /etc/rc.local"));

if (isset($_GET[enable])) {
	if (exec("grep ssh-connect.sh /etc/rc.local") == "") {
		exec("sed -i '/exit 0/d' /etc/rc.local"); 
		exec("echo /www/pineapple/ssh-connect.sh >> /etc/rc.local");
		exec("echo exit 0 >> /etc/rc.local");
		echo "<pre>SSH on boot enabled.</pre>";
		$sshonboot = "true";
	} else {
		echo "<pre>SSH Connect on boot already enabled, not touching rc.local</pre>";
	}
}                              

if (isset($_GET[disable])) {
	exec("sed -i '/ssh-connect.sh/d' /etc/rc.local");
	echo "<pre>SSH on boot disabled.</pre>";
	$sshonboot = "";                  
}

$filename = "/root/.ssh/known_hosts";
if (!file_exists($filename)) {
exec("echo ' ' > /root/.ssh/known_hosts");
}
?>

<html>
<head>
<?php if(isset($_GET[goback])){ 
echo "<meta http-equiv=\"refresh\" content=\"0; url=/pineapple/\">";
} ?>

<title>Pineapple Control Center</title>
<script  type="text/javascript" src="jquery.min.js"></script>
</head>
<body bgcolor="black" text="white" alink="green" vlink="green" link="green">

<?php require('navbar.php'); ?>
<pre>

<?php
$filename =$_POST['filename'];
$newdata = $_POST['newdata'];

if ($newdata != "") { $newdata = ereg_replace(13,  "", $newdata);
 $fw = fopen($filename, 'w') or die('Could not open file! Empty?');
 $fb = fwrite($fw,stripslashes($newdata)) or die('Could not write to file');
 fclose($fw);
 echo "Updated " . $filename . "<br /><br />";
} 


if ($sshonboot != ""){
echo "SSH on boot is currently <font color=\"lime\"><b>enabled</b></font>.&nbsp; | <a href=\"ssh.php?disable&disablekeepalive\"><b>Disable</b></a><br />";
} else { echo "SSH on boot is currently <font color=\"red\"><b>disabled</b></font>. | <a href=\"ssh.php?enable\"><b>Enable</b></a><br />"; }
if (exec("grep ssh-keepalive.sh /etc/crontabs/root") == "") {
echo "SSH Persist is currently <font color='red'><b>disabled</b></font>. | <a href='ssh.php?enablekeepalive&enable'><b>Enable</b></a><br />";
} else { echo "SSH Persist is currently <font color='lime'><b>enabled</b></font>.&nbsp; | <a href='ssh.php?disablekeepalive'><b>Disable</b></a><br />"; }


// debug: echo "<font color='pink'>" . exec("ps aux | grep [s]sh | grep -v -e ssh.php") . "</font>";
// 
// section currently disabled since autossh does a fine job of maintaining persistent connections. The ssh-keepalive.sh cron job isn't necessary.
// 

if (exec("ps aux | grep [s]sh | grep -v -e ssh.php") == "") {
	 echo "SSH session currently <font color=\"red\"><b>disconnected</b></font> | <a href=\"ssh.php?connect\"><b>Connect</b></a><br /><br />";
} else {
	echo "SSH session currently <font color=\"lime\"><b>connected</b></font>. &nbsp; | <a href=\"ssh.php?disconnect\"><b>Disconnect</b></a><br /><br />";
}


$filename = "/www/pineapple/ssh-connect.sh";
  $fh = fopen($filename, "r") or die("Could not open file! Empty?");
  $data = fread($fh, filesize($filename)) or die("Could not read file!");
  fclose($fh);
 echo "<b>SSH Connection Command:</b><form action='$_SERVER[php_self]' method= 'post' ><input type='hidden' name='filename' value='/www/pineapple/ssh-connect.sh'>
<input type='text' name='newdata' size='85' style='font-family:courier; font-weight:bold; background-color:black; color:white; border-style:dotted;' value='$data' /><input type='submit' value='Save'></form>";


echo "<br /><b>Public Key:</b> &nbsp; &nbsp; <font color='gray'><small>This usually goes in %h/.ssh/authorized_keys on the remote host</small></font><br /><br />";
	echo "<textarea rows='7' cols='89' style='background-color:black; color:white; border-style:dashed;'>";
	$cmd="dropbearkey -f /etc/dropbear/id_rsa -y";
	exec ($cmd, $output);
	foreach($output as $outputline) {
	echo ("$outputline\n");}
	echo "</textarea>";
?>
<br /><br />No key? <a href="ssh.php?generatekey"><b>Generate</b></a> one</a> | <a href="ssh.php?deletekey"><b>Delete</b></a> existing RSA SSH key pair</a><br />
<?php

$filename = "/root/.ssh/known_hosts";
$fh = fopen($filename, "r") or die("Could not open file!");
$data = fread($fh, filesize($filename)) or die("Could not read file!");
fclose($fh);
echo "<b>Known Hosts:</b>
<form action='$_SERVER[php_self]' method= 'post' >
<textarea name='newdata' cols='90' rows='8' style='background-color:black; color:white; border-style:dashed;'>$data</textarea>
<input type='hidden' name='filename' value='/root/.ssh/known_hosts'>
<input type='submit' value='Update Known Hosts'>
</form>";
?>       
<b>Help:</b>
<font color='green'>-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-</font>

<b>On the local host (this pineapple)</b>
 - Generate an RSA key pair. The private key will be stored in /etc/dropbear id_rsa
 - Note the RSA public key presented above. You'll need the from "ssh-rsa" to "root@Pineapple"     
 - Add the ssh-rsa key (not the public key above) to ~/.ssh/known_hosts      
   - This is most easily accomplished by issuing 'ssh user@host' and pressing 'y' when prompted to save the key
   - This must be done interactively (via a shell on this device) as AutoSSH does not pass the '-y' option.
                                                              
<b>On the remote host (the server)</b>
 - Append the above noted RSA public key to the authorized_keys file. This is typically located in ~/.ssh/
 - The following are helpful opensshd configuration options. The conf file is typically /etc/ssh/sshd_config
        AllowTcpForwarding   yes
        GatewayPorts         yes
        RSAAuthentication    yes
        PubkeyAuthentication yes
        
<b>Example Usage</b>

<b>Simple Relay Server</b>
With the above key exchange and SSH configuration complete create an SSH session through a relay server
 - Pineapple's SSH command: autossh -M 20000 -f -N -R 4255:localhost:22 user@relayserver -i /etc/dropbear/id_rsa
 - 3rd party SSH command: ssh pineappleuser@relayserver -p 4255
   - The pineapple user is typically root
   - If the relay server does not support remote port forwarding first SSH to the relay server as usual then:
     ssh pineappleuser@localhost -p 4255

<b>SSH</b>
Uage: ssh [options] [user@]host[/port] [command]
Options are:
-p &lt;remoteport&gt;
-l &lt;username&gt;
-t    Allocate a pty
-T    Don't allocate a pty
-N    Don't run a remote command
-f    Run in background after auth
-y    Always accept remote host key if unknown
-s    Request a subsystem (use for sftp)
-i &lt;identityfile&gt;   (multiple allowed)
-A    Enable agent auth forwarding
-L <[listenaddress:]listenport:remotehost:remoteport> Local port forwarding
-g    Allow remote hosts to connect to forwarded ports
-R <[listenaddress:]listenport:remotehost:remoteport> Remote port forwarding
-W &lt;receive_window_buffer&gt; (default 24576, larger may be faster, max 1MB)
-K &lt;keepalive&gt;  (0 is never, default 0)
-I &lt;idle_timeout&gt;  (0 is never, default 0)
-J &lt;proxy_program&gt; Use program pipe rather than TCP connection

<b>AutoSSH</b>
usage: autossh [-V] [-M monitor_port[:echo_port]] [-f] [SSH_OPTIONS]

    -M specifies monitor port. May be overridden by environment
       variable AUTOSSH_PORT. 0 turns monitoring loop off.
       Alternatively, a port for an echo service on the remote
       machine may be specified. (Normally port 7.)
    -f run in background (autossh handles this, and does not
       pass it to ssh.)
    -V print autossh version and exit.

Environment variables are:
    AUTOSSH_GATETIME    - how long must an ssh session be established
                          before we decide it really was established
                          (in seconds)
    AUTOSSH_LOGFILE     - file to log to (default is to use the syslog
                          facility)
    AUTOSSH_LOGLEVEL    - level of log verbosity
    AUTOSSH_MAXLIFETIME - set the maximum time to live (seconds)
    AUTOSSH_MAXSTART    - max times to restart (default is no limit)
    AUTOSSH_MESSAGE     - message to append to echo string (max 64 bytes)
    AUTOSSH_PATH        - path to ssh if not default
    AUTOSSH_PIDFILE     - write pid to this file
    AUTOSSH_POLL        - how often to check the connection (seconds)
    AUTOSSH_FIRST_POLL  - time before first connection check (seconds)
    AUTOSSH_PORT        - port to use for monitor connection
    AUTOSSH_DEBUG       - turn logging to maximum verbosity and log to
                          stderr

</pre></body></html>
