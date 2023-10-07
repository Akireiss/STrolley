<?php 
// Include the database config file 
include_once 'dbconfig.php';

if(isset($_POST['regionId']) && !empty($_POST["regionId"])){ 
    // Fetch province data based on the specific country 
    $query = "SELECT * FROM province WHERE regCode = ".$_POST['regionId']; 
    $result = $con->query($query); 
     
    // Generate HTML of Province options list 
    if($result->num_rows > 0){ 
        echo '<option value="" selected disabled>Select Province</option>'; 
        while($row = $result->fetch_assoc()){  
            echo '<option value="'.$row['provCode'].'">'.$row['provDesc'].'</option>'; 
        } 
    }else{
        echo '<option value="" selected disabled>Select Province</option>';
        echo '<option value="allProvince">ALL PROVINCE</option>'; 
    } 
}else if(isset($_POST['provinceId']) && !empty($_POST["provinceId"])){ 
    // Fetch city data based on the specific state 
    $query = "SELECT * FROM municipality WHERE provCode = ".$_POST['provinceId']; 
    $result = $con->query($query); 
     
    // Generate HTML of city options list 
    if($result->num_rows > 0){ 
        echo '<option value="" selected disabled>Select City</option>'; 
        while($row = $result->fetch_assoc()){  
            echo '<option value="'.$row['citymunCode'].'">'.$row['citymunDesc'].'</option>'; 
        } 
    }else{ 
        echo '<option value="" selected disabled>Select City/municipality</option>';
        echo '<option value="allLGU">ALL CITY/MUNICIPALITY</option>'; 
    } 
}else if(isset($_POST['cityId']) && !empty($_POST["cityId"])){ 
    // Fetch city data based on the specific state 
    $query = "SELECT * FROM barangay WHERE citymunCode = ".$_POST['cityId']; 
    $result = $con->query($query); 
     
    // Generate HTML of city options list 
    if($result->num_rows > 0){ 
        echo '<option value="" selected disabled>Select Barangay</option>'; 
        while($row = $result->fetch_assoc()){  
            echo '<option value="'.$row['brgyCode'].'">'.$row['brgyDesc'].'</option>'; 
        } 
    }else{ 
        echo '<option value="">Barangay not available</option>'; 
    }
}
?>