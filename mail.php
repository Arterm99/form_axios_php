<?php

// Способ 2. Через JS
// Отправка данных на php
// !!!! НАСТРОИТЬ SMTP

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