<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YSEレジ</title>
    <!-- Tailwind CSSのCDNリンク -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .custom-btn {
            background-color: #DDDDDD; /* グレーの背景色 */
            color: black; /* テキストの色 */
            border: 1px solid black; /* 黒い枠線 */
            padding: 10px 20px; /* パディング */
            cursor: pointer; /* カーソルをポインターにする */
            border-radius: 3px; /* 角を丸くする */
            font-weight: bold; /* 太字にする */
        }

        .custom-btn:hover {
            background-color: #718096; /* ホバー時の色 */
        }

        .custom-display {
            background-color: black; /* 黒い背景色 */
            color: white; /* テキストの色 */
            border: 2px solid #00FF00; /* 緑の枠線 */
            padding: 10px; /* パディング */
        }

        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <div class="bg-gray-200 flex justify-center items-center h-screen">
        <div class="calculator bg-white rounded p-8 shadow-md">
            <div class="error-message">
                <?php
                // フォームが送信されたかどうかをチェック
                if(isset($_POST['submit'])) {
                    // ユーザーが入力した値を取得
                    $price = $_POST['price'];
                    
                    // 入力値の検証
                    if(empty($price)) {
                        // 値が入力されていない場合、エラーメッセージを表示
                        echo "値を入力してください";
                    } else {
                        // 入力が正常な場合はデータベースに挿入する処理を実行
                        require_once "update.php";
                    }
                }
                ?>
            </div>
            <div class="d-flex w-full mt-3 mb-3">
                <form action="" method="post">
                    <input type="text" id="display" name="price" class="w-full mb-4 px-2 py-1 custom-display" readonly>
                    <div>
                        <button class="custom-btn" name="submit">計上</button>
                        <a class="custom-btn" href="sales/">売上</a>
                    </div>
                </form>
            </div>
            <div class="grid grid-cols-4 gap-4">
                <button class="custom-btn" onclick="addToDisplay('7')">7</button>
                <button class="custom-btn" onclick="addToDisplay('8')">8</button>
                <button class="custom-btn" onclick="addToDisplay('9')">9</button>
                <button class="custom-btn" onclick="clearAll()">AC</button>
                <button class="custom-btn" onclick="addToDisplay('4')">4</button>
                <button class="custom-btn" onclick="addToDisplay('5')">5</button>
                <button class="custom-btn" onclick="addToDisplay('6')">6</button>
                <button class="custom-btn" onclick="calculate('+')">+</button>
                <button class="custom-btn" onclick="addToDisplay('1')">1</button>
                <button class="custom-btn" onclick="addToDisplay('2')">2</button>
                <button class="custom-btn" onclick="addToDisplay('3')">3</button>
                <button class="custom-btn" onclick="calculate('*')">x</button>
                <button class="custom-btn" onclick="addToDisplay('0')">0</button>
                <button class="custom-btn" onclick="addToDisplay('00')">00</button>
                <button class="custom-btn" onclick="calculateTax()">税込み</button>
                <button class="custom-btn" onclick="calculateTotal()">=</button>
            </div>
        </div>
    </div>

    <script>
        var memory = "";
        const TAX_RATE = 0.1;

        function addToDisplay(value) {
            memory += value;
            updateDisplay();
        }

        function calculate(value) {
            memory += value;
        }

        function clearAll() {
            memory = "";
            updateDisplay();
        }

        function updateDisplay() {
            document.getElementById('display').value = memory;
        }

        function calculateTax() {
            memory *= (1 + TAX_RATE);
            memory = Math.round(memory);
            updateDisplay();
        }

        function calculateTotal() {
            memory = eval(memory);
            updateDisplay();
        }
        function updateDisplay() {
            document.getElementById('display').value = memory;
        }
    </script>
</body>

</html>
