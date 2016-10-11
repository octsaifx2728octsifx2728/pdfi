<?php
	$conn=mysqli_connect("10.79.98.101","root","p2a0e0&2","oprys");
	if (mysqli_connect_errno())
    {
        echo "Error al conectar a la base de datos: " . mysqli_connect_error();
    }else{
    	echo "CONEXIÓN EXITOSA";
    }
?>