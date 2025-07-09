<?php 
if($accountType == "patient"){
    include "assets/pages/_patient.php";
}else if($accountType == "doctor"){
    include "assets/pages/_doctor.php";
}else if($accountType == "student"){
    include "assets/pages/_student.php";
}
?>