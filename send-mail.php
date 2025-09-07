<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require 'vendor/autoload.php'; // путь до автозагрузчика Composer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Метод не разрешен']);
    exit;
}

$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$service = isset($_POST['service']) ? trim($_POST['service']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';
$consent = isset($_POST['consent']) ? 'Да' : 'Нет';

if ($name === '' || $phone === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Заполните обязательные поля']);
    exit;
}

$mail = new PHPMailer(true);

try {
    // Настройки SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'roma.aseo123@gmail.com'; // Ваш Gmail
    $mail->Password = 'kloz sket qpmg hwie'; // Пароль приложения Gmail
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // От кого и кому
    $mail->setFrom('roma.aseo123@gmail.com', 'Мир Ремонта');
    $mail->addAddress('romaaseo@yandex.ru'); // Куда отправлять

    // Ответить на email пользователя
    if ($email) {
        $mail->addReplyTo($email, $name);
    }

    // Контент письма
    $mail->Subject = "Заявка на ремонт от $name";
    $mail->Body = "Имя: $name\nТелефон: $phone\nEmail: $email\nТип ремонта: $service\nСообщение: $message\nСогласие на обработку: $consent";

    $mail->send();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Ошибка отправки: ' . $mail->ErrorInfo]);
} // ← ДОБАВЬТЕ ЭТУ СКОБКУ