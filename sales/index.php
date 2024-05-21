<?php
// データベースに接続
require_once "../db.php";

// 売上一覧をすべて取得
$sql = "SELECT * FROM sales ORDER BY created_at DESC;";
// SQLを実行
$stmt = $pdo->query($sql);

// PHP配列に変換
$sales = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $sales[] = $row;
}
$total_sales = array_sum(array_column($sales, 'price'));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YSEレジ</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .highlight-message {
            font-size: 1.5em;
            color: red;
            background-color: yellow;
            padding: 10px;
            text-align: center;
            margin-top: 20px;
            border: 2px solid red;
        }
    </style>
</head>

<body>
    <h2 class="text-2xl text-center">売上一覧</h2>
    <div class="p-3">
        <a href="../">戻る</a>
    </div>
    <?php if (count($sales) === 0): ?>
        <div class="highlight-message">
            売上が0です
        </div>
    <?php else: ?>
        <h3 class="text-xl text-center p-3">
            <label for="">総売上</label>
            <span><?= number_format($total_sales) ?></span>
        </h3>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">売上日時</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">売上高</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($sales as $sale) : ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap"><?= $sale['created_at'] ?></td>
                        <td class="px-6 py-4 whitespace-nowrap"><?= number_format($sale['price']) ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    <?php endif ?>
</body>

</html>