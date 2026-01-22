<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $query = trim($_POST['query']);

    $stmt = $conn->prepare("
        SELECT certificate_number FROM certificates
        WHERE certificate_number = ? OR full_name LIKE CONCAT('%', ?, '%')
        LIMIT 1
    ");
    $stmt->bind_param("ss", $query, $query);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();

    if ($res) {
        // Redirect to certificate page
        header("Location: certificate.php?id=" . urlencode($res['certificate_number']));
        exit;
    } else {
        $error = "Certificate not found!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Track Certificate</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{
  --gold:#d4af37;
  --white:#ffffff;
}
body{font-family:'Poppins',sans-serif;background:linear-gradient(135deg,#050509,#0d1325,#050509);color:var(--white);min-height:100vh;}
.track-container{max-width:900px;margin:60px auto;padding:0 24px;}
.track-box{background:linear-gradient(180deg,rgba(255,255,255,0.12),rgba(255,255,255,0.03));backdrop-filter:blur(14px);border-radius:20px;padding:40px;border:1px solid rgba(255,255,255,0.15);box-shadow:0 20px 60px rgba(0,0,0,0.6);}
.track-box h3{color:var(--gold);margin-bottom:10px;}
.track-box p{color:#d6dbff;margin-bottom:25px;}
.track-box input{width:100%;padding:16px 18px;border-radius:12px;border:none;background:#0e1535;color:#fff;margin-bottom:18px;font-size:15px;}
.track-box input::placeholder{color:#8f98ff;}
.track-box button{width:100%;padding:16px;font-size:16px;border-radius:14px;border:none;background:linear-gradient(135deg,var(--gold),#f5d76e);color:#111;font-weight:600;cursor:pointer;transition:all .3s ease;}
.track-box button:hover{transform:translateY(-2px);box-shadow:0 12px 30px rgba(212,175,55,0.45);}
.error-msg{color:#ff8a8a;margin-bottom:15px;}
</style>
</head>
<body>
<div class="track-container">
  <div class="track-box">
    <h3>Track Certificate</h3>
    <p>Enter certificate number or holder name below</p>

    <?php if(!empty($error)) echo '<div class="error-msg">'.$error.'</div>'; ?>

    <form method="POST">
      <input type="text" name="query" placeholder="Certificate Number or Full Name" required />
      <button type="submit">Track Certificate</button>
    </form>
  </div>
</div>
</body>
</html>
