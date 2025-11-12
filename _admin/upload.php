<?php

if(!empty($_FILES))
{
	$path = 'upload/' . $_FILES['file']['name'];
	if (move_uploaded_file($_FILES['file']['tmp_name'], $path))
	{
		echo 'Imagen subida con exito';
	}
}
else
{
	echo 'Algun Error';
}
?>