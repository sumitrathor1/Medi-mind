<?php 
if($accountType == "patient"){
    include "assets/pages/patient.php";
}else if($accountType == "doctor"){
    include "assets/pages/doctor.php";
}else if($accountType == "admin"){
    include "assets/pages/admin.php";
}
?>