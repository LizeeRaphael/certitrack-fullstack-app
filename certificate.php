<?php
include 'db.php';

$certificateNo = $_GET['id'] ?? '';

if (!$certificateNo) die("Invalid certificate ID");

$stmt = $conn->prepare("SELECT * FROM certificates WHERE certificate_number = ? LIMIT 1");
$stmt->bind_param("s", $certificateNo);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

if (!$result) die("Certificate not found");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Certificate - <?php echo htmlspecialchars($result['full_name']); ?></title>

</head>
<body>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Certificate</title>

<style>
body{
    margin:0;
    background:#eee;
    font-family: "Georgia", serif;
}

.certificate{
    width:900px;
    height:1000px;
    margin:40px auto;
    background:#fff;
    position:relative;
    padding:60px;
    box-sizing:border-box;
}

/* ================= BORDER SYSTEM ================= */

/* OUTER LINES */
.border{
    position:absolute;
    inset:20px;
    pointer-events:none;
}

.border::before,
.border::after{
    content:"";
    position:absolute;
    inset:0;
    border:2px solid #f39c12;
}

/* INNER LINES */
.inner{
    position:absolute;
    inset:8px;
    border:1.5px solid #f39c12;
}

/* CUT THE SIDES (creates corner-only look) */
.cut{
    position:absolute;
    background:#fff;
}

.cut.top    { top:-3px; left:80px; right:80px; height:12px; }
.cut.bottom { bottom:-3px; left:80px; right:80px; height:12px; }
.cut.left   { left:-3px; top:80px; bottom:80px; width:12px; }
.cut.right  { right:-3px; top:80px; bottom:80px; width:12px; }

/* ================= CONTENT ================= */

.content{
    text-align:center;
}

.logo{
    font-size:13px;
    letter-spacing:2px;
    color:#1f3c88;
    font-weight:bold;
}

.title{
    font-size:36px;
    margin-top:30px;
}

.subtitle{
    font-style:italic;
    margin-top:8px;
}

.name{
    margin-top:35px;
    font-size:26px;
    font-weight:bold;
}

.line{
    width:60%;
    margin:10px auto;
    border-bottom:2px solid #000;
}

.text{
    margin-top:20px;
    line-height:1.6;
}

.date{
    margin-top:20px;
}

.congrats{
    margin-top:25px;
    font-size:40px;
}
.footer {
   
    left: 0;
    right: 0;
    display: flex;
    justify-content: space-between; /* Seal left, signature right */
    align-items: flex-end;          /* Aligns both divs at bottom */
                  /* Horizontal padding */
}

.signature-right {
    text-align: right; /* Align text and image to the right */
}

.signature-right img {
    display: block;
    margin-left: auto; /* Push image fully to the right */
}

/* SEAL */
.seal{
    width:90px;
    height:90px;
  
    position:relative;
}



/* SIGNATURE */
.sign{
    text-align:center;
}

.sign .sline{
    width:200px;
    border-bottom:1.5px solid #000;
    margin-bottom:5px;
}

.id{
   
    bottom:10px;
    left:30px;
    font-size:11px;
}
</style>
</head>

<body>

<div class="certificate">

    <!-- BORDER -->
    <div class="border">
        <div class="inner"></div>

        <!-- CUTS -->
        <div class="cut top"></div>
        <div class="cut bottom"></div>
        <div class="cut left"></div>
        <div class="cut right"></div>
    </div>

    <!-- CONTENT -->
    <div class="content">
        <div class="logo"><img src="logo.png" style="width:200px; height: 100px;"></div>

        <div class="title"><b style="font-size:50px;">Certificate of Participation</b></div>
        <div class="subtitle">This Certificate is Proudly Presented to:</div>

        <div class="name"><?php echo htmlspecialchars(strtoupper($result['full_name'])); ?></div>
        <div class="line"></div>

        <div class="text">
            For His/Her Immersive Participation in the<br>
            <strong><?php echo $result['program']=='AI' ? 'AI For Productivity and Profit Training' : 'Code Camp Training'; ?></strong><br>
            Organized by TECHPATRIATE SERVICES in Conjunction<br>
            with BREKETE FAMILY ACADEMY
        </div>

        <div class="date">Presented on <?php 
$date = new DateTime($result['program_date']);
echo $date->format("F j, Y"); // Output: January 21, 2026
?>
</div>
        <div class="congrats"><b>Congratulations</b></div>
    </div>
<br><br><br>
<style>
  .signature-left {
    text-align: left;   /* aligns text and inline images to left */
    display: inline-block; /* keeps the div only as wide as content */
    vertical-align: top;   /* optional: aligns with seal image if needed */
}

</style>
 <div class="footer">
    <div class="seal">
        <img src='tag.png' style="width:150px; height:200px;">
    </div>

    <div class="signature-right">
      
        <img src='signature.png'>
        <strong> <center>UYI JOACHIM EBHUOMA</center>
        <center>Director</center></strong>
    </div>
</div>


    <br><br><br><br><br><br><br>
    <div class="id" style="font-size:16px;"><b> <?php echo $result['certificate_number']; ?></b></div>

</div>
 <center> <button class="print-btn" onclick="window.print()">Print Certificate</button></center>
</body>
</html>
