<?php
// Basic PHP contact form handler

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: index.html#contact');
  exit;
}

// Simple sanitization
function sanitize($value) {
  return trim(filter_var($value, FILTER_SANITIZE_STRING));
}

$name    = sanitize($_POST['name'] ?? '');
$email   = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
$subject = sanitize($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

if (!$name || !$email || !$subject || !$message) {
  $error = 'Please fill in all fields correctly.';
} else {
  $to      = 'ghorasiaakash25@gmail.com';
  $mailSubject = 'Portfolio contact: ' . $subject;
  $body    = "Name: {$name}\nEmail: {$email}\n\nMessage:\n{$message}\n";
  $headers = "From: {$email}\r\nReply-To: {$email}\r\n";

  if (!mail($to, $mailSubject, $body, $headers)) {
    $error = 'Sorry, there was a problem sending your message.';
  }
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact | Aakash</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/responsive.css" />
  </head>
  <body>
    <header id="top">
      <h2><a href="index.html" class="logotext">Aakash</a></h2>
      <nav>
        <a href="index.html"><span>Home</span></a>
        <a href="index.html#about"><span>About</span></a>
        <a href="index.html#projects"><span>Projects</span></a>
        <a href="index.html#skills"><span>Skills</span></a>
        <a href="index.html#contact"><span>Contact</span></a>
      </nav>
    </header>

    <main>
      <section class="contact-section">
        <h2 class="section-title">Contact Status</h2>
        <div class="contact-container">
          <div class="contact-info">
            <div class="contact-card">
              <?php if (isset($error)): ?>
                <h3>Something went wrong</h3>
                <p><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
              <?php else: ?>
                <h3>Thank you!</h3>
                <p>Your message has been sent successfully.</p>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <div style="text-align: center; margin-top: 20px;">
          <a href="index.html#contact" class="submit-btn">Back to Contact</a>
        </div>
      </section>
    </main>
  </body>
</html>

