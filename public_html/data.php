<?php
//for read data from mysql
include('../db.php');

try {
    $pdo = new PDO("mysql:host=$db[host];dbname=$db[dbname];port=$db[port];charset=$db[charset]", $db['username'], $db['password']);
} catch(PDOException $e) {
    echo "Database connection failed";
    exit;
}

$year = date('Y');
$month = date('m');

//load events
//取出id, title, start_time在依日期, 時間ASC排序
$sql = 'SELECT * FROM events WHERE year=:year AND month=:month ORDER BY `date`, start_time ASC';
$statement = $pdo->prepare($sql);
$statement->bindValue(':year', $year, PDO::PARAM_INT);
$statement->bindValue(':month', $month, PDO::PARAM_INT);
$statement->execute();
$events = $statement->fetchAll(PDO::FETCH_ASSOC);

//10:00:00 -> 10:00
foreach ($events as $key => $value) {
    $events[$key]['start_time'] = substr($value['start_time'], 0, 5);
}



$days = ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'];
$dates = [];
for($i = 1; $i <= 31; $i++){
    $dates[] = $i; //this is the push syntax
}
$dates[] = null;
$dates[] = null;
$dates[] = null;
$dates[] = null;
?>

<script>
    var events = <?= json_encode($events, JSON_NUMERIC_CHECK) ?>;//JSON_NUMERIC_CHECK保留數字格式避免變成字串
</script>