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
            text-align: right; /* 右詰め */
        }

        .error-message {
            color: red;
            font-size: 12px; /* フォントサイズを14pxから12pxに変更 */
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <div class="bg-gray-200 flex justify-center items-center h-screen flex-col">
        <h1 class="text-3xl font-bold mb-6">YSEレジシステム</h1>
        <div class="calculator bg-white rounded p-8 shadow-md">
            <div class="error-message" id="error-message">
                <?php
                // フォームが送信されたかどうかをチェック
                if (isset($_POST['submit'])) {
                    // ユーザーが入力した値を取得
                    $price = $_POST['price'];
                    
                    // 入力値の検証
                    if (empty($price)) {
                        // 値が入力されていない場合、エラーメッセージを表示
                        echo "※値を入力してください";
                    } else {
                        // 入力が正常な場合はデータベースに挿入する処理を実行
                        require_once "update.php";
                    }
                }
                ?>
            </div>
            <div class="d-flex w-full mt-3 mb-3">
                <form action="" method="post" onsubmit="return validateForm()">
                    <input type="text" id="display" name="price" class="w-full mb-4 px-2 py-1 custom-display" readonly>
                    <div>
                        <button type="submit" class="custom-btn" name="submit">計上</button>
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
        var displayMemory = "";
        const TAX_RATE = 0.1;

        function addToDisplay(value) {
            clearErrorMessage();
            memory += value;
            displayMemory += value;
            updateDisplay();
        }

        function calculate(operator) {
            if (displayMemory === "") {
                showError("※値を入力してください");
                return;
            }
            // 直前が演算子または displayMemory が空の場合、処理を中止
            if (displayMemory.slice(-1).match(/[+\-*×]/)) {
                return;
            }
            clearErrorMessage();
            if (operator === '*') {
                memory += '*';
                displayMemory += '×';
            } else {
                memory += operator;
                displayMemory += operator;
            }
            updateDisplay();
        }

        function clearAll() {
            memory = "";
            displayMemory = "";
            updateDisplay();
            clearErrorMessage();
        }

        function updateDisplay() {
            document.getElementById('display').value = displayMemory;
        }

        function clearErrorMessage() {
            document.getElementById('error-message').innerText = "";
        }

        function showError(message) {
            document.getElementById('error-message').innerText = message;
        }

        function calculateTax() {
            if (displayMemory === "") {
                showError("※値を入力してください");
                return;
            }
            if (displayMemory.match(/[+\-*×]/)) {
                showError("※計算が完了していません");
                return; // 途中に演算子が含まれている場合、処理を中止
            }
            clearErrorMessage();
            memory = parseFloat(memory) * (1 + TAX_RATE);
            memory = Math.round(memory);
            displayMemory = memory.toString();
            updateDisplay();
        }

        function calculateTotal() {
            if (displayMemory === "") {
                showError("※値を入力してください");
                return;
            }
            if (displayMemory.slice(-1).match(/[+\-*×]/)) {
                return; // 直前に演算子がある場合、処理を中止
            }
            clearErrorMessage();
            try {
                memory = eval(memory.replace(/×/g, '*')).toString(); // × を * に置き換え
                displayMemory = memory;
                updateDisplay();
            } catch {
                memory = "Error";
                displayMemory = "Error";
                updateDisplay();
            }
        }

        function validateForm() {
            var display = document.getElementById('display').value;
            var errorMessage = document.getElementById('error-message');

            if (display === "") {
                errorMessage.innerText = "※値を入力してください";
                return false; // フォームの送信を中止
            } else if (display.match(/[+\-*×]$/)) {
                errorMessage.innerText = "※計算が完了していません";
                return false; // フォームの送信を中止
            }
            return true; // フォームの送信を許可
        }
    </script>
</body>

</html>
