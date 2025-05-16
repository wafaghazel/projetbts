<?php

// Création de la socket
if(!($sock = socket_create (AF_INET, SOCK_STREAM, getprotobyname ("tcp"))))
{
    $errorcode = socket_last_error();   
    $errormsg = socket_strerror($errorcode);

    die ("Couldn't create socket: [$errorcode] $errormsg \n");
}

echo "Socket created \n";

//connect socket to remote server
if(!socket_connect($sock '192.168.1.116', 9090))
{
    $errorcode = socket_last_error ();
    $errormsg = socket_strerror ($errorco);

    die("Could not connect : [$errorcode] $errormsg \n");
}

echo "Connection established \n";

//Is this proper representation of the hexadecimal data
$message = '\x00\x01' ;

//Send the message to the server
if ( ! socket_send ( $sock, $message, strlen($message) , 0))
{
    $errorcode = socket_last_error ();
    $errormsg = socket_strerror ($errorco);

    die("Could not connect : [$errorcode] $errormsg \n");
}

echo "Message send successfully \n"

//now receive reply from server

if(socket_recv ( $sock , $buf , 2 , MSG_WAITALL ) === FALSE)
{
    $errorcode = socket_last_error ();
    $errormsg = socket_strerror ($errorco);
    
    die("Could not connect : [$errorcode] $errormsg \n");
}

// print the received message
echo $buf;

?>
