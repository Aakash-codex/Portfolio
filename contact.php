<?php
// PHP Contact Form Handler with Confirmation
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.html#contact');
    exit;
}

// Function to sanitize input
function sanitize($value) {
    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}

$name    = sanitize($_POST['name'] ?? '');
$email   = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
$subject = sanitize($_POST['subject'] ?? '');
$message = sanitize($_POST['message'] ?? '');

$is_success = false;
$error_msg = "";

if (!$name || !$email || !$subject || !$message) {
    $error_msg = 'すべての項目を正しく入力してください。 (Please fill in all fields correctly.)';
} else {
    // --- DBngin (MySQL) 保存設定 ---
    $db_host = '127.0.0.1'; // DBnginのデフォルトホスト
    $db_user = 'root';      // デフォルトユーザー
    $db_pass = '';          // DBnginのデフォルトパスワード（設定している場合は変更してください）
    $db_name = 'portfolio_db'; // DBnginで作ったデータベース名

    try {
        // 1. まずはデータベースを指定せずに接続
        $pdo = new PDO("mysql:host=$db_host;charset=utf8", $db_user, $db_pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // 2. データベースがなければ作成
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        
        // 3. 作成したデータベースを選択
        $pdo->exec("USE `$db_name` ");

        // 4. テーブルが存在しない場合に作成
        $pdo->exec("CREATE TABLE IF NOT EXISTS contacts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255),
            email VARCHAR(255),
            subject VARCHAR(255),
            message TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        $stmt = $pdo->prepare("INSERT INTO contacts (name, email, subject, message) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $subject, $message]);
        
        $is_success = true;
    } catch (PDOException $e) {
        $error_msg = 'データベース接続エラー: ' . $e->getMessage();
    }
    // ----------------------------

    // メール送信（オプション）
    $to = 'ghorasiaakash25@gmail.com';
    $mailSubject = 'ポートフォリオからのメッセージ: ' . $subject;
    $body = "名前: {$name}\nメールアドレス: {$email}\n件名: {$subject}\n\nメッセージ:\n{$message}\n";
    $headers = "From: webmaster@example.com\r\nReply-To: {$email}\r\nContent-Type: text/plain; charset=UTF-8\r\n";
    @mail($to, $mailSubject, $body, $headers); // ローカルでは失敗することが多いため@でエラーを抑制
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>送信完了 | Aakash Portfolio</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/responsive.css">
    <style>
        .confirmation-section {
            padding: 120px 20px 80px;
            background: linear-gradient(135deg, #000000 0%, #1a1a1a 100%);
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .confirmation-container {
            max-width: 600px;
            width: 100%;
            background: linear-gradient(145deg, #1a1a1a, #000000);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(2, 183, 243, 0.2);
            border: 1px solid rgba(2, 183, 243, 0.2);
            text-align: center;
        }
        .status-icon {
            font-size: 50px;
            margin-bottom: 20px;
        }
        .status-success { color: #02b7f3; }
        .status-error { color: #ff4d4d; }
        
        .confirmation-title {
            font-size: 2rem;
            color: #d4d2cd;
            margin-bottom: 20px;
        }
        .submitted-data {
            text-align: left;
            background: rgba(255, 255, 255, 0.05);
            padding: 20px;
            border-radius: 10px;
            margin: 25px 0;
            color: #b0b0b0;
        }
        .data-item {
            margin-bottom: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding-bottom: 5px;
        }
        .data-label {
            font-weight: 600;
            color: #02b7f3;
            margin-right: 10px;
        }
        .back-home-btn {
            display: inline-block;
            padding: 12px 30px;
            background: #02b7f3;
            color: white;
            text-decoration: none;
            border-radius: 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .back-home-btn:hover {
            background: #0099d6;
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="lang-en">
    <header id="top">
      <div class="logo-container">
        <h2><a href="index.html" class="logotext">Aakash</a></h2>
      </div>
      
      <nav>
        <a href="index.html"><span data-lang="en">Home</span><span data-lang="ja">ホーム</span></a>
        <a href="index.html#about"><span data-lang="en">About</span><span data-lang="ja">自己紹介</span></a>
        <a href="index.html#news"><span data-lang="en">News</span><span data-lang="ja">ニュース</span></a>
        <a href="index.html#projects"><span data-lang="en">Projects</span><span data-lang="ja">プロジェクト</span></a>
        <a href="index.html#skills"><span data-lang="en">Skills</span><span data-lang="ja">スキル</span></a>
        <a href="index.html#contact"><span data-lang="en">Contact</span><span data-lang="ja">お問い合わせ</span></a>
      </nav>

      <div class="header-controls">
        <!-- Language Toggle -->
        <label class="lang-switch" for="langCheckbox">
          <input type="checkbox" id="langCheckbox" />
          <div class="slider">
            <span class="en icon">EN</span>
            <span class="jp icon">日本語</span>
          </div>
        </label>

        <!-- Theme Toggle -->
        <label class="theme-switch" for="checkbox">
          <input type="checkbox" id="checkbox" />
          <div class="slider">
            <span class="icon">🌙</span>
            <span class="icon">☀️</span>
          </div>
        </label>
      </div>
    </header>

    <main>
        <section class="confirmation-section">
            <div class="confirmation-container">
                <?php if ($is_success): ?>
                    <div class="status-icon status-success">✓</div>
                    <h1 class="confirmation-title">送信が完了しました</h1>
                    <p>お問い合わせありがとうございます。以下の内容で送信いたしました。</p>
                    
                    <div class="submitted-data">
                        <div class="data-item"><span class="data-label">お名前:</span> <?php echo $name; ?></div>
                        <div class="data-item"><span class="data-label">メール:</span> <?php echo $email; ?></div>
                        <div class="data-item"><span class="data-label">件名:</span> <?php echo $subject; ?></div>
                        <div class="data-item" style="border:none;"><span class="data-label">メッセージ:</span><br><?php echo nl2br($message); ?></div>
                    </div>
                    
                    <p style="margin-bottom: 20px; color: #888;">内容を確認後、担当者よりご連絡いたします。</p>
                <?php else: ?>
                    <div class="status-icon status-error">✕</div>
                    <h1 class="confirmation-title">エラーが発生しました</h1>
                    <p><?php echo $error_msg; ?></p>
                <?php endif; ?>
                
                <a href="index.html" class="back-home-btn">ホームに戻る</a>
            </div>
        </section>
    </main>

    <footer class="footer" style="margin-top: 0;">
      <div class="footer-bottom">
        <p class="copyright">© 2024 Aakash. All rights reserved.</p>
      </div>
    </footer>

    <script>
      const toggleSwitch = document.querySelector('.theme-switch input[type="checkbox"]');
      const currentTheme = localStorage.getItem('theme');

      if (currentTheme) {
          document.body.classList.add(currentTheme);
          if (currentTheme === 'light-theme') {
              toggleSwitch.checked = true;
          }
      }

      function switchTheme(e) {
          if (e.target.checked) {
              document.body.classList.add('light-theme');
              localStorage.setItem('theme', 'light-theme');
          } else {
              document.body.classList.remove('light-theme');
              localStorage.setItem('theme', 'dark-theme');
          }    
      }

      toggleSwitch.addEventListener('change', switchTheme, false);
    </script>
</body>
</html>
