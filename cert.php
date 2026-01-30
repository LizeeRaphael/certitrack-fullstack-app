<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>CertiTrack – Certificate Generator</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    :root{
      --black:#0b0b0d;
      --gold:#d4af37;
      --blue:#1f4fd8;
      --white:#ffffff;
      --glass: rgba(255,255,255,0.08);
    }

    *{box-sizing:border-box;margin:0;padding:0}

    body{
      font-family:'Poppins',sans-serif;
      background:linear-gradient(135deg,#050509,#0d1325,#050509);
      color:var(--white);
      min-height:100vh;
    }

    header{
      display:flex;
      justify-content:space-between;
      align-items:center;
      padding:24px 60px;
    }

    .logo{
      font-size:24px;
      font-weight:700;
      color:var(--gold);
      letter-spacing:1px;
    }

    nav a{
      color:#cfd8ff;
      margin-left:28px;
      text-decoration:none;
      font-weight:500;
    }

    nav a:hover{color:var(--gold)}

    .hero{
      display:grid;
      grid-template-columns:1.2fr 1fr;
      gap:60px;
      padding:80px 60px;
      align-items:center;
    }

    .hero-text h1{
      font-size:52px;
      line-height:1.15;
      margin-bottom:20px;
    }

    .hero-text h1 span{color:var(--gold)}

    .hero-text p{
      color:#c7ccff;
      font-size:17px;
      max-width:520px;
      margin-bottom:40px;
    }

    .track-box{
      background:linear-gradient(180deg,rgba(255,255,255,0.12),rgba(255,255,255,0.03));
      backdrop-filter:blur(14px);
      border-radius:20px;
      padding:40px;
      box-shadow:0 20px 60px rgba(0,0,0,0.6);
      border:1px solid rgba(255,255,255,0.15);
    }

    .track-box h3{
      font-size:24px;
      margin-bottom:10px;
      color:var(--gold);
    }

    .track-box p{
      font-size:14px;
      color:#d6dbff;
      margin-bottom:25px;
    }

    .track-box input,
    .track-box select{
      width:100%;
      padding:16px 18px;
      border-radius:12px;
      border:none;
      outline:none;
      font-size:15px;
      background:#0e1535;
      color:#fff;
      margin-bottom:18px;
    }

    .track-box input::placeholder{color:#8f98ff}

    .track-box button{
      width:100%;
      padding:16px;
      font-size:16px;
      border-radius:14px;
      border:none;
      background:linear-gradient(135deg,var(--gold),#f5d76e);
      color:#111;
      font-weight:600;
      cursor:pointer;
      transition:all .3s ease;
    }

    .track-box button:hover{
      transform:translateY(-2px);
      box-shadow:0 12px 30px rgba(212,175,55,0.45);
    }

    #certResult{
      margin-top:20px;
      font-size:14px;
      line-height:1.6;
    }

    .features{
      display:grid;
      grid-template-columns:repeat(3,1fr);
      gap:30px;
      padding:0 60px 80px;
    }

    .feature{
      background:var(--glass);
      border-radius:18px;
      padding:30px;
      border:1px solid rgba(255,255,255,0.12);
      transition:.3s;
    }

    .feature:hover{
      transform:translateY(-6px);
      box-shadow:0 20px 50px rgba(0,0,0,0.5);
    }

    .feature h4{
      color:var(--gold);
      margin-bottom:12px;
      font-size:18px;
    }

    .feature p{
      font-size:14px;
      color:#cbd2ff;
    }

    footer{
      text-align:center;
      padding:30px;
      font-size:13px;
      color:#9aa3ff;
      border-top:1px solid rgba(255,255,255,0.1);
    }

    @media(max-width:900px){
      .hero{grid-template-columns:1fr;padding:50px 24px}
      header{padding:20px 24px}
      .features{grid-template-columns:1fr;padding:0 24px 60px}
    }
  </style>
</head>
<body>

<header>
  <div class="logo">CertiTrack</div>
  <nav>
    <a href="index.php">Home</a>
    <a href="cert.php">Generate Certificate</a>
    
  </nav>
</header>

<section class="hero">
  <div class="hero-text">
    <h1>Generate<br><span>Certificates Instantly</span></h1>
    <p>
      Create secure and verifiable certificates with unique certificate numbers
      for multiple training programs.
    </p>
  </div>

  <div class="track-box">
    <h3>Generate Certificate</h3>
    <p>Fill in the details below</p>

    <form id="certForm">
      <input type="text" name="full_name" placeholder="Full Name" required>

      <select name="program" required>
        <option value="">Select Program Type</option>
        <option value="AI">AI For Productivity and Profit Training</option>
        <option value="BCC">Code Camp Training</option>
        <option value="BWD">Web Design Training</option>
      </select>

      <input type="date" name="program_date" required>

      <button type="submit">Generate Certificate</button>
    </form>

    <div id="certResult"></div>
  </div>
</section>

<section class="features">
  <div class="feature">
    <h4>🔐 Secure</h4>
    <p>Each certificate has a unique, traceable number.</p>
  </div>
  <div class="feature">
    <h4>⚡ Fast</h4>
    <p>Generate certificates instantly without page reload.</p>
  </div>
  <div class="feature">
    <h4>🌍 Professional</h4>
    <p>Clean modern UI suitable for organizations and institutions.</p>
  </div>
</section>

<footer>
  © <span id="year"></span> CertiTracker Platform. All rights reserved.
</footer>

<script>
// ✅ Auto-update footer year
document.getElementById("year").textContent = new Date().getFullYear();

document.getElementById('certForm').addEventListener('submit', function(e){
  e.preventDefault();

  const result = document.getElementById('certResult');
  result.innerHTML = "Processing...";

  fetch('generate_certificate.php', {
    method: 'POST',
    body: new FormData(this)
  })
  .then(res => res.json())
  .then(data => {
    if(data.success){
      // Redirect to certificate display page
      window.location.href = 'certificate.php?id=' + encodeURIComponent(data.certificate_number);
    } else {
      result.innerHTML = `<div style="color:#ff8f8f">${data.message}</div>`;
    }
  })
  .catch(() => {
    result.innerHTML = `<div style="color:#ff8f8f">Server error occurred</div>`;
  });
});
</script>

</body>
</html>
