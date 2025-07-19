<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Собираем данные из формы
    $name = htmlspecialchars($_POST['name']);
    $phone = htmlspecialchars($_POST['phone']);
    $email = 'test@test.mail';
    $ip = $_SERVER['REMOTE_ADDR']; // Получаем IP пользователя
    $country = 'KZ'; // Замените на актуальную страну
    $language = 'ru'; // Язык лидов
    $link_id = 17; // ID ссылки
    $funnel = 'kapitalkaspi1'; // Название воронки
    $source = 'form_submission'; // Источник

    // Убедитесь, что все обязательные поля заполнены
    if (empty($name) || empty($phone) || empty($email)) {
        die('Имя, Телефон и Email обязательны для заполнения.');
    }

    // Данные для отправки
    $formData = [
        'fname' => $name,
        'fullphone' => $phone,
        'email' => $email,
        'ip' => $ip,
        'country' => $country,
        'language' => $language,
        'link_id' => $link_id,
        'source' => $source,
        'funnel' => $funnel
    ];

    // URL и API-токен
    $apiUrl = 'https://tracking.l0teamcrm.digital/api/v3/integration';
    $apiToken = 'iN7D0Haqx8x17484CHHNBUMQf47SuOrSQRpCwf2OstkVh9qHONKANLdWBhvF'; // Замените на ваш токен API

    // Отправка запроса через cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl . '?api_token=' . $apiToken);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($formData));

    // Получение ответа
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Проверка ответа
    if ($httpCode === 200) {
        $result = json_decode($response, true);
        if (isset($result['success']) && $result['success'] === true) {
            header('Location: thanks.php');
            exit();
        } else {
            $errorMessage = isset($result['message']) ? $result['message'] : 'Неизвестная ошибка.';
            header('Location: error.php?message=' . urlencode($errorMessage));
            exit();
        }
    } else {
        header('Location: error.php?message=Ошибка соединения с API. Код: ' . $httpCode);
        exit();
    }
} else {
    die('Некорректный метод запроса.');
}
?>