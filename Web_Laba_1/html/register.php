<?php
$host = 'localhost';
$db = 'web3';
$user = 'postgres';
$pass = 'A22332233';

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$db", $user, $pass);
} catch (PDOException $e) {
    die("Ошибка подключения к БД: " . $e->getMessage());
}

// Получаем данные из формы
$login = trim($_POST['login'] ?? '');
$password = $_POST['password'] ?? '';
$phone = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');

// Проверка
if ($login && $password && $phone && $email) {
    // Проверка на существующего пользователя
    $stmt = $pdo->prepare("SELECT * FROM users WHERE login = :login");
    $stmt->execute(['login' => $login]);

    if ($stmt->rowCount() > 0) {
        die("Пользователь уже существует.");
    }

    // Хеширование пароля
    $hash = password_hash($password, PASSWORD_DEFAULT);

    // Вставка в БД
    $stmt = $pdo->prepare("INSERT INTO users (login, password_hash, phone, email) VALUES (:login, :password_hash, :phone, :email)");
    $stmt->execute([
        'login' => $login,
        'password_hash' => $hash,
        'phone' => $phone,
        'email' => $email
    ]);

    echo "Регистрация успешна.";
} else {
    echo "Все поля обязательны для заполнения.";
}
?>
