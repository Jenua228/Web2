<?php
require_once 'db.php';

// Получаем все категории
$categories = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Наши услуги</title>
  <link rel="stylesheet" href="../css/pages.css">
  <style>
    .card-grid {
      max-width: 1300px;
      margin: 0 auto;
      padding: 20px;
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 20px;
      justify-content: center;
    }

    .card {
      border: 1px solid #ccc;
      padding: 15px;
      border-radius: 8px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      background: #fff;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .card img {
      width: 100%;
      height: 180px;
      object-fit: cover;
      border-radius: 5px;
    }

    .card h3 {
      margin: 10px 0 5px;
    }

    .card p {
      flex-grow: 1;
    }

    .card .buttons {
      display: flex;
      justify-content: space-between;
      margin-top: 15px;
    }

    .card button {
      padding: 8px 12px;
      background: #2a8;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    .card button:hover {
      background: #1a6;
    }

    main{
      text-align: center;
    }

    h2.category-title {
      text-align: center;
      margin-top: 40px;
      font-size: 26px;
      color: #333;
      border-bottom: 2px solid #ddd;
      display: inline-block;
      padding-bottom: 5px;
    }
  </style>
</head>
<body>

<header>
  <div class="logo"><img src="../img/1.png" alt="Логотип"></div>
  <div class="title">ПИРАМИДА</div>
</header>

<main>
  <h1 style="text-align:center;">Наши услуги</h1>

  <?php foreach ($categories as $category): ?>
    <h2 class="category-title"><?= htmlspecialchars($category['name']) ?></h2>

    <?php
    $stmt = $pdo->prepare("SELECT * FROM services WHERE category_id = ?");
    $stmt->execute([$category['id']]);
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <div class="card-grid">
      <?php foreach ($services as $service): ?>
        <div class="card">
          <img src="../img/services/<?= $service['id'] ?>.jpg" alt="<?= htmlspecialchars($service['name']) ?>">
          <h3><?= htmlspecialchars($service['name']) ?></h3>
          <p><?= htmlspecialchars($service['description']) ?></p>
          <p><strong>Цена:</strong> <?= htmlspecialchars($service['price']) ?> ₽</p>
          <div class="buttons">
            <a href="feedback.php?id=<?= $service['id'] ?>"><button>Отзывы</button></a>
            <a href="service_detail.php?id=<?= $service['id'] ?>"><button>Подробнее</button></a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endforeach; ?>

</main>

</body>
</html>
