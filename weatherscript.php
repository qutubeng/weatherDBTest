<?php

include 'Database.php';

$database = new Database();

$csv = array_map("str_getcsv", file("resource/weather.csv",FILE_SKIP_EMPTY_LINES));
$keys = array_shift($csv);

foreach ($csv as $i=>$row) {
    $csv[$i] = array_combine($keys, $row);
}

$monthHighAvg = 0;
$monthLowAvg = 0;
$counter = 0;

for($c = 1; $c <= count($csv); $c++) {
    if($c != count($csv) && date("M",strtotime($csv[$c]['Date'])) === date("M",strtotime($csv[$c-1]['Date']))) {
        $monthHighAvg += $csv[$c-1]['High'];
        $monthLowAvg += $csv[$c-1]['Low'];
        $counter++;
    }
    else {
        $monthHighAvg += $csv[$c-1]['High'];
        $monthLowAvg += $csv[$c-1]['Low'];
        $counter++;

        $monthHighAvg = $monthHighAvg/$counter;
        $monthLowAvg = $monthLowAvg/$counter;

        $weatherData['Month'] =  date("M",strtotime($csv[$c-1]['Date']));
        $weatherData['HighAvg'] = $monthHighAvg;
        $weatherData['LowAvg'] = $monthLowAvg;

        $database->Insert($weatherData, 'summaryWeather');

        $monthHighAvg = 0;
        $monthLowAvg = 0;
        $counter = 0;
    }
}


$idWarm = $database->getWarmestMonthId();
$idCold = $database->getColdestMonthId();

for($j = 0; $j < count($idWarm); $j++) {
    $database->updateWarmestMonth($idWarm[$j]['Id']);
}

for($j = 0; $j < count($idCold); $j++) {
    $database->updateColdestMonth($idCold[$j]['Id']);
}


echo 'Weather Summary Table has been created with the average high and low temperatures by month. <br>
It is also marked by value 1 in the table which month was the coldest and the warmest.<br><br>';

echo 'data from the table:<br>';

print_r($database->getSummaryWeather());
