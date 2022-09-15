<?php

// Подключение к БД, создание таблицы, проверка на присутствие таблицы в бд, внесение данных из формы
function DB() {

    $hostname = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'data_2';

    /* Вы должны включить отчёт об ошибках для mysqli, прежде чем пытаться установить соединение */
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $mysqli = mysqli_connect($hostname, $username, $password,  $database);

    if (!$mysqli) {
        die("Ошибка: " . mysqli_connect_error());
    }

    /* Установите желаемую кодировку после установления соединения */
    mysqli_set_charset($mysqli, 'utf8');
    printf("Успешно... %s\n", mysqli_get_host_info($mysqli));


    // Проверка на присутствие табицы в бд
    if (mysqli_num_rows(mysqli_query($mysqli,"SHOW TABLES LIKE 'users'")) > 0 ) {
        echo "Повторное создание таблицы";

    } else {
        /* Создание таблицы, не возвращает набор результатов */
        $sql = "CREATE TABLE Users (id INTEGER AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255), email VARCHAR(255),phone INTEGER);";
        if (mysqli_query($mysqli, $sql)) {
            echo "Таблица Users успешно создана";
        } else {
            echo "Ошибка: " . mysqli_error($mysqli);
        }
    }

    // add data in table
    if (
        isset($_POST["user_name"]) &&
        isset($_POST["user_email"]) &&
        isset($_POST["user_phone"])
    ){

        // mysqli_real_escape_string() - служит для экранизации символов в строке,
        $name = mysqli_real_escape_string($mysqli, $_POST["user_name"]);
        $email = mysqli_real_escape_string($mysqli, $_POST["user_email"]);
        $phone = mysqli_real_escape_string($mysqli, $_POST["user_phone"]);


        $sql = "INSERT INTO Users (name, email, phone) VALUES ('$name', '$email', $phone)";
        if(mysqli_query($mysqli, $sql)){
            echo "Данные успешно добавлены";
        } else{
            echo "Ошибка: " . mysqli_error($mysqli);
        }

        // Close Bd
        mysqli_close($mysqli);
    };

}


// Способ 2. Через JS
// Отправка данных на php
// !!!! НАСТРОИТЬ SMTP
function sendMail() {

    // decode для axios
    $_POST = json_decode(file_get_contents('php://input'), true);

    $name = $_POST['user_name'];
    $email = $_POST['user_email'];
    $phone = $_POST['user_phone'];

    $to = $email;
    $subject = 'Заявка с сайта test-site-name';
    $message = 'Имя '.$name.' Почта '. $email .' Телефон: '.$phone;

    // Отправка
    $success = mail($to, $subject, $message);

    if (!$success) {
        echo 'Ошибка при выполнении запроса';
    } else {
        echo 'Письмо успешно отправлено';
    }

}

DB();
// sendMail();
