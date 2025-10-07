<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>LifeLink — Forgot Password</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/forgot.css"/>
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

      <form class="forgot-form" action="php/forgot_process.php" method="post" autocomplete="email">
        <label class="label" for="email">Registered Email</label>

        <input id="email" name="email" type="email" placeholder="Enter your registered email" required class="input-large" />

        <button type="submit" class="btn-cta">Get OTP</button>

        <p class="small-note">
          Remembered your password?<br>
          <a href="../html/Log In.php">Click here to sign in</a>
        </p>
      </form>
    </section>
  </main>
</body>
</html>
