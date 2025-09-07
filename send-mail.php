<?php
// Разрешить CORS и указать JSON-ответ
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Проверка метода запроса
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Метод не разрешен']);
    exit;
}

// Получение данных формы
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$service = isset($_POST['service']) ? trim($_POST['service']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';
$consent = isset($_POST['consent']) ? 'Да' : 'Нет';

// Проверка обязательных полей
if ($name === '' || $phone === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Заполните обязательные поля']);
    exit;
}

// Формирование письма
$to = 'roma.aseo123@gmail.com';
$subject = "Заявка на ремонт от $name";
$body = "Имя: $name\nТелефон: $phone\nEmail: $email\nТип ремонта: $service\nСообщение: $message\nСогласие на обработку: $consent";
$headers = "From: info@mirremonta.ru\r\nReply-To: $email\r\nContent-Type: text/plain; charset=UTF-8";

// Отправка письма
if (mail($to, $subject, $body, $headers)) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Ошибка отправки письма']);
}
