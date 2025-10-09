<?php
session_start();

$sessionToken = $_SESSION['reset_token'] ?? null;
$getToken     = $_GET['token'] ?? null;

if (empty($sessionToken) && empty($getToken)) {
    echo "<script>alert('Unauthorized access. Please use the link sent to your email.'); window.location.href='forgot.php';</script>";
    exit;
}

$hiddenToken = $sessionToken ?? $getToken;
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>LifeLink — Forgot Password</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/forgot2.css"/>
    <link rel="icon" href="../image/logo2.png" type="image/png" />
    </head>
    <body>
    <header class="site-header">
        <div class="brand">
        <img src="../image/logo2.png" alt="LifeLink logo" class="brand-logo" />
        <span class="brand-name">LifeLink</span>
        </div>
    </header>

    <main class="page">
        <section class="card" role="dialog" aria-labelledby="forgotTitle">
        <h1 id="forgotTitle" class="card-title">Forgot Password</h1>

        <form class="forgot-form" action="../php/reset_password_process.php" method="post" autocomplete="email">
            <label class="label" for="password">New Password</label>
            <div class="input-box">
                <input type="password" id="email" name="password" placeholder="Enter new password" required />
            </div>

            <label class="label" for="confirm">Confirm Password</label>
            <div class="input-box">
                <input type="password" id="email" name="confirm" placeholder="Confirm new password" required />
            </div>
            <input type="hidden" name="reset_token" value="<?php echo htmlspecialchars($hiddenToken); ?>">
            <div class="cta-wrap">
                <button type="submit" class="btn-cta">Reset Password</button>
            </div>
        </form>
        </section>
    </main>
    <script src="../javascript/alert-handler.js"></script>
      <script>
      document.getElementById('resetForm').addEventListener('submit', function(e) {
      const p = document.getElementById('password').value;
      const c = document.getElementById('confirm').value;

      if (p !== c) {
        e.preventDefault();
        alert('Passwords do not match.');
        return;
      }
      if (p.length < 6) {
        e.preventDefault();
        alert('Password must be at least 6 characters long.');
        return;
      }
      // allow submit
    });
  </script> 
</body>
</html>
