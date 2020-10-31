<?php

use Dotenv\Dotenv;

require '../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
$base_url = $_ENV['BASE_URL'];

if (empty($_POST)){
    //header("Location: ".$base_url."error.html");
    //exit();
}
else {
    $db_name = $_ENV['DB_NAME'];
    $db_user = $_ENV['DB_USER'];
    $db_pass = $_ENV['DB_PASS'];

    $timezone = new DateTimeZone($_POST['timezone']);
    $date = new DateTime('now', $timezone);
    $db = new PDO('mysql:host=localhost;dbname='.$db_name, $db_user, $db_pass);

    $stmt = $db->prepare('INSERT INTO queue (event_id, firstname, lastname, address, birthday, mobile, email, timestamp) VALUES (:event_id, :firstname, :lastname, :address, :birthday, :mobile, :email, :timestamp)');
    $stmt->execute(array(
        'event_id' => $_POST['event_id'],
        'firstname' => $_POST['firstname'],
        'lastname' => $_POST['lastname'],
        'address' => $_POST['address'],
        'birthday' => $_POST['birthday'],
        'mobile' => $_POST['mobile'],
        'email' => $_POST['email'],
        'timestamp' => $date->format('Y-m-d H:i:s')));

    if (!$stmt){
        header("Location: $base_url.error.html");
        exit();
    }
}
?>
<!doctype html>
<html lang="de">
<head>
    <base href="<?= $base_url ?>" />

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Ahoy! // Thank you!</title>

    <meta name="theme-color" content="#ffffff">

    <!-- CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <!-- inject:css -->
    <!-- endinject -->
</head>

<body>
<!--[if IE]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
<![endif]-->

<div class="container">
    <div class="py-3 text-center">
        <img class="d-block mx-auto my-4" src="assets/ahoy.svg" alt="" width="48" height="48" />
        <h1 class="ahoy_title" data-bind="i18n: 'thankyou_title'">Thank you!</h1>
        <p class="lead" data-bind="i18n: 'thankyou_subtitle'">
            You have successfully registered for the event
        </p>
    </div>

    <div class="row justify-content-md-center">
        <div class="col-md-8">
            <p data-bind="i18n: 'thankyou_description_1'">
                Your data will be sent to the organizer of this event. After the data has been transmitted to the organiser of the event, your data is deleted from the server.
            </p>

            <p data-bind="i18n: 'thankyou_description_2'">
                Your data will be deleted automatically by the app after 14 days. Your data will also not be passed on to third parties or used in any other way.
            </p>
        </div>
    </div>

</div>

<footer class="pt-5 text-muted text-center text-small">
    <p class="mb-2">&copy; 2020 Apollo29</p>
    <ul class="list-inline">
        <li class="list-inline-item"><a href="privacy.html" target="_blank">Privacy</a></li>
        <li class="list-inline-item"><a href="terms.html" target="_blank">Terms</a></li>
        <li class="list-inline-item"><a href="disclaimer.html" target="_blank">Disclaimer</a></li>
        <li class="list-inline-item"><span data-bind="i18n: 'language'"></span>: <a class="btn-link" data-bind="click: setLanguage('de'), class: isCurrent('de')">deutsch</a>, <a class="btn-link" data-bind="click: setLanguage('en'), class: isCurrent('en')">english</a></li>
    </ul>
</footer>

<script src="https://cdn.jsdelivr.net/npm/knockout@3.5.1/build/output/knockout-latest.min.js"></script>
<script
        src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
        crossorigin="anonymous"></script>
<!-- i18n:js -->
<!-- endinject -->
<!-- other:js -->
<!-- endinject -->
</body>

</html>
