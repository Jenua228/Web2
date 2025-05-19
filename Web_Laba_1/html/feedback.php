<?php
require_once 'db.php';

$service_id = (int)($_GET['id'] ?? 0);
if (!$service_id) {
    exit('Услуга не найдена.');
}

// Обработка отзыва
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = strip_tags(trim($_POST['username']));
    $comment = strip_tags(trim($_POST['comment']));

    if ($username && $comment) {
        $stmt = $pdo->prepare("INSERT INTO reviews (service_id, username, comment) VALUES (?, ?, ?)");
        $stmt->execute([$service_id, $username, $comment]);
        header("Location: feedback.php?id=$service_id"); // Обновить страницу
        exit;
    }
}

// Получаем услугу
$stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
$stmt->execute([$service_id]);
$service = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$service) {
    exit('Услуга не найдена.');
}

// Получаем отзывы
$stmt = $pdo->prepare("SELECT * FROM reviews WHERE service_id = ? ORDER BY created_at DESC");
$stmt->execute([$service_id]);
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Отзывы — <?= htmlspecialchars($service['name']) ?></title>
    <style>
        body { font-family: sans-serif; margin: 30px; text-align: center;}
        form { margin-top: 20px; }
        textarea { width: 30%; height: 100px; }
        button{height: 30px}
        .review { border-bottom: 1px solid #ccc; padding: 10px 0; }
    </style>
</head>
<body>
    <h1>Отзывы об услуге: <?= htmlspecialchars($service['name']) ?></h1>

    <form method="post">
        <p>
            <label>Ваше имя:<br>
            <input type="text" name="username" required></label>
        </p>
        <p>
            <label>Отзыв:<br>
            <textarea name="comment" required></textarea></label>
        </p>
        <button type="submit">Отправить отзыв</button>
        
    </form>

    <h2>Ранее оставленные отзывы:</h2>
    <?php if ($reviews): ?>
        <?php foreach ($reviews as $review): ?>
            <div class="review">
                <strong><?= htmlspecialchars($review['username']) ?></strong> <em>(<?= $review['created_at'] ?>)</em><br>
                <?= nl2br(htmlspecialchars($review['comment'])) ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Отзывов пока нет. Станьте первым!</p>
    <?php endif; ?>
</body>
</html>
