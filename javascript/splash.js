const splashDuration = 5000; 
setTimeout(() => {
  // Option 1: Redirect to another page
  window.location.href = "../html/Log in.html"; 

  const splash = document.querySelector(".splash");
  splash.style.opacity = "0";
  setTimeout(() => {
    splash.style.display = "none";
    document.querySelector(".main").style.display = "block";
  }, 1000);
}, splashDuration);
