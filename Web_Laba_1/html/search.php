<?php
require_once 'db.php';

$search_q = $_POST['search_q'] ?? '';
$search_q = trim(strip_tags(mb_strtolower($search_q)));

?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Результаты поиска</title>
  <link rel="stylesheet" href="../css/pages.css">
  <style>
    body { font-family: sans-serif; padding: 20px; }

    h2 {
      margin-bottom: 20px;
    }

    .card-grid {
  display: flex;
  flex-wrap: wrap;
  justify-content: center; /* Центрирует карточки */
  gap: 20px;
  max-width: 1000px;
  margin: 0 auto;
  padding: 20px 0;
}


    .card {
  width: 300px; /* Фиксированная ширина */
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
      margin: 5px 0;
    }

    .card .price {
      font-weight: bold;
      margin: 10px 0;
    }

    .card .buttons {
      display: flex;
      justify-content: space-between;
      margin-top: 10px;
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

    @media (max-width: 1024px) {
      .card-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (max-width: 600px) {
      .card-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>

<h2>Результаты поиска по запросу: <em><?= htmlspecialchars($search_q) ?></em></h2>

<?php
$stmt = $pdo->prepare("SELECT * FROM services WHERE LOWER(name) LIKE :q OR LOWER(description) LIKE :q");
$stmt->execute(['q' => "%$search_q%"]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($results):
?>
<div class="card-grid">
  <?php foreach ($results as $service): ?>
    <div class="card">
      <img src="../img/services/<?= $service['id'] ?>.jpg" alt="<?= htmlspecialchars($service['name']) ?>">
      <h3><?= htmlspecialchars($service['name']) ?></h3>
      <p><?= htmlspecialchars($service['description']) ?></p>
      <p class="price">Цена: <?= htmlspecialchars($service['price']) ?> ₽</p>
      <div class="buttons">
        <a href="feedback.php?id=<?= $service['id'] ?>"><button>Отзывы</button></a>
        <a href="service_detail.php?id=<?= $service['id'] ?>"><button>Подробнее</button></a>
      </div>
    </div>
  <?php endforeach; ?>
</div>
<?php else: ?>
  <p>Услуги, соответствующие запросу, не найдены.</p>
<?php endif; ?>

</body>
</html>
