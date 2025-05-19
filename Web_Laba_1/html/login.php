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

$login = trim($_POST['login'] ?? '');
$password = $_POST['password'] ?? '';

if ($login && $password) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE login = :login");
    $stmt->execute(['login' => $login]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {
        // Генерация простого токена (в реальности лучше JWT)
        $token = bin2hex(random_bytes(16));

        // Сохраняем токен в БД
        $update = $pdo->prepare("UPDATE users SET token = :token WHERE id = :id");
        $update->execute(['token' => $token, 'id' => $user['id']]);

        echo "Авторизация успешна.";
        // Здесь можно сохранять токен в куки или localStorage на клиенте
    } else {
        echo "Неверный логин или пароль.";
    }
} else {
    echo "Заполните все поля.";
}
?>
