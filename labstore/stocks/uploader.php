<?php
$url ='http://biof-test.colorado.edu/dna_cat/labstore/stocks/';
$link = mysql_connect('localhost', 'alex', 'qwe');
if (!$link) {
    die('Could not connect: ' . mysql_error());
}
echo 'Connected successfully  ';
mysql_select_db("updata"); 



$target_path = "/var/www/html/dna_cat/labstore/stocks/uploads/";
$sql="INSERT into upload_data (`USER_ID`,`FILE_NAME`,`FILE_SIZE`,`FILE_TYPE`) VALUES('$user_id','$file_name','$file_size','$file_type'); ";
$target_path = $target_path . basename( $_FILES['uploadedfile']['name']); 

if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
    echo "The file ". basename( $_FILES['uploadedfile']['name']). " has been uploaded";

if(mysql_query($sql)) {
	echo "Success!";
}
} else{
    echo "There was an error uploading the file, please try again!";
}

?>
