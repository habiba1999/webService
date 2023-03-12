<?php

require_once ("./vendor/autoload.php");
require_once("./function.php");
$citiesList = file_get_contents("./city.list.json");
$json_cities = json_decode($citiesList, true); 
$egyptCities = array_filter($json_cities,"getEgypt");
$appiKey = "d4bce2ada78f5e6750517b2c7fd0f517";

if(!empty($_POST)){
    if(isset($_POST["submit"])){
        $cityId = $_POST["city"];
        $ApiUrl = "https://api.openweathermap.org/data/2.5/weather?id=" . $cityId . "&appid=" . $appiKey;
        $client=new \GuzzleHttp\Client();
        $response = $client->get($ApiUrl);
        $data = json_decode($response->getBody(), true);
        $currentTime = date("d-m-y h:i:sa");
        $icon = "https://openweathermap.org/img/wn/". $data["weather"][0]["icon"] . "@2x.png";        
        if(!empty($data)){
            die('<body>
            <div style=
                width:50%;
                margin:8vh auto;
                padding:3%;
                background-color: white;
                border-radius: 20px;">'
                . '<center><h2 style="color:#30534c;">' . $data["name"] . ' Weather Status</h2></br>'
                . "<p> Today is: " . $current_time . "</p>"
                . "<p> Description:  " . $data["weather"][0]["description"] . "</p>"
                . '<img src="' . $icon . '" alt="">'
                ."<p> Min_Temp:  ".$data["main"]["temp_min"]."&degF</p>"
                ."<p> Max_Temp:  ".$data["main"]["temp_min"]."&degF</p>"
                ."<p> Humidity:  ".$data["main"]["humidity"]."%</p>"
                ."<p> Wind:  ".($data["wind"]["speed"])." Km/h</p>"
                . '</center>' .
                '</div> </body>');
        }
    }
}
require_once("./weather.php");

?>