<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>LifeLink — Sign In</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="../css/register.css" />
  <link rel="icon" href="../image/logo2.png" type="image/png" />
  <script src="../javascript/register.js"></script>

</head>
<body>
  <main class="page-wrapper">

    <section class="panel left-panel">
      <div class="left-inner">
        <h1 class="signin-title">Sign Up</h1>

            <form class="signin-form" action="../php/register.php" method="post" autocomplete="on">
            <label for="fullname" class="field-label">Full Name</label>
            <div class="input-box">
                <input id="fullname" name="fullname" type="text" placeholder="Enter your fullname" required autocomplete="name" />
            </div>

            <label for="username" class="field-label">Username</label>
            <div class="input-box">
                <input id="username" name="username" type="text" placeholder="Enter your username" required autocomplete="username" />
            </div>

            <label for="email" class="field-label">Email</label>
            <div class="input-box">
                <input id="email" name="email" type="email" placeholder="Enter your email" required autocomplete="off" />
            </div>

            <label for="role" class="field-label">Role</label>
            <div class="input-box">
                <select id="role" name="role" required>
                    <option value="" disabled selected>Select your role</option>
                    <option value="donor">Donor</option>
                    <option value="doctor">Doctor</option>
                    <option value="Admin">Admin</option>
                </select>
            </div>

            <label for="password" class="field-label">Password</label>
            <div class="input-box">
                <input id="password" name="password" type="password" placeholder="Enter your password" 
                required autocomplete="current-password" minlength="8" />
            </div>

            <label for="cpassword" class="field-label">Confirm Password</label>
            <div class="input-box">
                <input id="cpassword" name="cpassword" type="password" placeholder="Enter your password" 
                required autocomplete="current-password" minlength="8"  />
            </div>

          <div class="cta-wrap">
            <button type="submit" class="btn-primary">Sign In</button>
            <p class="signup-note">
              Already have an account? <a href="Log In.php">Click here</a></p>
          </div>


        </form>
      </div>
    </section>


    <aside class="panel right-panel">
      <div class="right-inner">
        <div class="brand">
          <img src="../image/logo2.png" alt="LifeLink logo" class="brand-logo" />
          <span class="brand-name">LifeLink</span>
        </div>

        <h2 class="welcome-heading">WELCOME TO LIFELINK!</h2>

        <p class="welcome-copy">
Take the first step toward making a difference. By signing up, you become part of a secure and compassionate platform that connects donors with medical professionals, ensuring every match brings hope and saves lives.
        </p>
      </div>
    </aside>


  </main>
</body>
</html>
