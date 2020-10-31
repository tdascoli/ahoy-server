<?php

use Dotenv\Dotenv;

require '../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
$base_url = $_ENV['BASE_URL'];

if (!array_key_exists("event", $_GET) || empty($_GET['event'])){
    header("Location: ".$base_url."error.html");
    exit();
}
?>
<!doctype html>
<html lang="de">
<head>
    <base href="<?= $base_url ?>" />

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Ahoy! // Registration</title>

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
        <h1 class="ahoy_title">Ahoy!</h1>
        <p class="lead" data-bind="visible: loaded">
            <span data-bind="i18n: 'register_subtitle', i18n-options: { value: event().title }"></span>
        </p>
    </div>

    <div class="row justify-content-md-center">
        <div class="col-md-8">
            <p data-bind="i18n: 'register_description'">
                To register for this event, the following data must be entered. The data will be transmitted to the organiser of the event and then deleted on the server. The data will not be forwarded to third parties or used in any other way.
            </p>

            <form method="post" action="thankyou.php" id="register">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label data-bind="i18n: 'register_firstname'" for="firstname">First name</label>
                        <input data-bind="textInput: queue().firstname" type="text" class="form-control" id="firstname" name="firstname" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label data-bind="i18n: 'register_lastname'" for="lastname">Last name</label>
                        <input data-bind="textInput: queue().lastname" type="text" class="form-control" id="lastname" name="lastname" required>
                    </div>
                </div>

                <div class="mb-3 w-50 pr-3">
                    <label data-bind="i18n: 'register_birthday'" for="birthday">Birthday</label>
                    <input data-bind="textInput: queue().birthday" type="date" class="form-control" id="birthday" name="birthday" required>
                </div>

                <div class="mb-3">
                    <label data-bind="i18n: 'register_address'" for="address">Address</label>
                    <textarea data-bind="textInput: queue().address" class="form-control" id="address" rows="2" name="address" required></textarea>
                </div>

                <div class="mb-3">
                    <label data-bind="i18n: 'register_email'" for="email">Email</label>
                    <input data-bind="textInput: queue().email" type="email" class="form-control" id="email" name="email" placeholder="you@example.com" required>
                </div>

                <div class="mb-3">
                    <label data-bind="i18n: 'register_mobile'" for="mobile">Mobile</label>
                    <input data-bind="textInput: queue().mobile" type="text" class="form-control" id="mobile" name="mobile" required>
                </div>

                <!-- event id and user timezone-->
                <input type="hidden" name="event_id" data-bind="textInput: queue().event_id" />
                <input type="hidden" name="timezone" data-bind="textInput: timezone" />

                <p class="mb-4" data-bind="i18n: 'register_disclaimer'">
                    By clicking on "Register", you agree that your data will be sent to the organiser of this event. Your data will be deleted automatically after 14 days. Your data will also not be passed on to third parties or used in any other way.
                </p>

                <hr class="mb-4">
                <p>
                    <a href="cookies.html" target="_blank" data-bind="i18n: 'register_cookies_1'">Ahoy! may use cookies</a> <span data-bind="i18n: 'register_cookies_2'">to improve the experience on this site. To prevent this you can do so by not selecting the option "Save this information for next time".</span>
                </p>

                <button data-bind="click: proceed, i18n: 'register_button'" class="btn btn-primary mr-4" type="submit">Register</button>

                <div class="custom-control custom-checkbox custom-control-inline mt-4">
                    <input data-bind="checked: remember" type="checkbox" class="custom-control-input" id="save-info">
                    <label class="custom-control-label" for="save-info" data-bind="i18n: 'register_remember'">Save this information for next time</label>
                </div>
            </form>
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
</div>

<script src="https://cdn.jsdelivr.net/npm/knockout@3.5.1/build/output/knockout-latest.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/knockout.mapping@2.4.3/knockout.mapping.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dayjs@1.9.4/dayjs.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dayjs@1.9.4/plugin/timezone.js"></script>
<script src="https://cdn.jsdelivr.net/npm/js-cookie@rc/dist/js.cookie.min.js"></script>
<script
        src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
        crossorigin="anonymous"></script>
<!-- i18n:js -->
<!-- endinject -->
<!-- inject:js -->
<!-- endinject -->
</body>

</html>
