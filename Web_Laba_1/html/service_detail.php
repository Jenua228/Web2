<?php
require_once 'db.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT s.*, c.name AS category FROM services s LEFT JOIN categories c ON s.category_id = c.id WHERE s.id = ?");
$stmt->execute([$id]);
$service = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$service) {
    exit("Услуга не найдена");
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($service['name']) ?></title>
  <link rel="stylesheet" href="../css/pages.css">
  <style>
    .service-detail {
      max-width: 900px;
      margin: 40px auto;
      padding: 20px;
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .service-detail img {
      width: 100%;
      height: 500px;
      object-fit: cover;
      border-radius: 8px;
    }
    .service-detail h1 {
      margin-top: 20px;
    }
    .service-detail .price {
      font-size: 20px;
      color: #0a8;
      margin: 10px 0;
    }
    .section {
      margin-top: 30px;
    }

    button{
      height: 30px;
    }
  </style>
</head>
<body>
  <div class="service-detail">
    <img src="../img/services/<?= htmlspecialchars($service['image']) ?>" alt="<?= htmlspecialchars($service['name']) ?>">
    <h1><?= htmlspecialchars($service['name']) ?></h1>
    <div class="price">Цена: <?= htmlspecialchars($service['price']) ?></div>
    <div class="category">Категория: <?= htmlspecialchars($service['category']) ?></div>

    <div class="section">
      <?= nl2br(htmlspecialchars($service['detailed_description'])) ?>
    </div>

    <div style="margin-top: 30px;">
      <a href="feedback.php?id=<?= $service['id'] ?>"><button>Читать отзывы</button></a>
    </div>
  </div>
</body>
</html>
