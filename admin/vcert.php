<?php
session_start();
include 'db.php';
if(!isset($_SESSION['admin'])){
    header("Location: admin_login.php");
    exit;
}

// Handle delete request
if(isset($_GET['delete_id'])){
    $id = intval($_GET['delete_id']);
    mysqli_query($conn, "DELETE FROM certificates WHERE id=$id");
    header("Location: view_certificates.php");
    exit;
}

// Pagination settings
$limit = 20;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

// Search filter
$search = trim($_GET['search'] ?? '');

// Build query
$where = "WHERE 1";
if($search !== ''){
    $where .= " AND full_name LIKE '%".mysqli_real_escape_string($conn, $search)."%'";
}

// Get total count
$totalRes = mysqli_query($conn, "SELECT COUNT(*) as total FROM certificates $where");
$totalRows = mysqli_fetch_assoc($totalRes)['total'];
$totalPages = ceil($totalRows / $limit);

// Fetch certificates with limit & search
$result = mysqli_query($conn, "SELECT id, certificate_number, full_name, program, program_date, created_at 
                               FROM certificates $where 
                               ORDER BY created_at DESC 
                               LIMIT $offset, $limit");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>View Certificates | CertiTrack</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{--black:#0b0b0d;--dark:#0f1433;--blue:#1f4fd8;--gold:#d4af37;--white:#fff}
*{box-sizing:border-box}
body{margin:0;font-family:Poppins,sans-serif;background:linear-gradient(135deg,#050509,#0d1325);color:#fff}
.sidebar{width:240px;height:100vh;position:fixed;background:#070711;padding:25px 20px}
.sidebar h2{color:var(--gold);text-align:center;margin-bottom:35px}
.sidebar a{display:block;padding:14px 16px;margin-bottom:12px;color:#cfd8ff;text-decoration:none;border-radius:10px;font-weight:500}
.sidebar a:hover{background:rgba(31,79,216,.25);color:#fff}
.main{margin-left:240px;padding:35px}
.topbar{display:flex;justify-content:space-between;align-items:center;margin-bottom:30px}
.profile{background:rgba(255,255,255,.08);padding:10px 18px;border-radius:30px;font-size:14px}
table{width:100%;border-collapse:collapse;margin-top:20px}
th,td{padding:12px;text-align:left;border-bottom:1px solid rgba(255,255,255,0.1)}
th{color:var(--gold)}
button.download{
    padding:6px 12px;
    border:none;
    color:#fff;
    border-radius:6px;
    cursor:pointer;
    background:var(--blue);
    margin-right:5px;
}
button.download:hover{
    background:#1738b8;
}

button.delete{
    padding:6px 12px;
    border:none;
    color:#fff;
    border-radius:6px;
    cursor:pointer;
    background:#ff4d4d;
}
button.delete:hover{
    background:#cc0000;
}
input.search{padding:8px 12px;margin-bottom:15px;width:250px;border-radius:6px;border:none;background:#0e1535;color:#fff}
.pagination{margin-top:20px}
.pagination a{padding:6px 12px;margin-right:5px;background:#1f4fd8;color:#fff;text-decoration:none;border-radius:6px}
.pagination a.active{background:#d4af37;color:#000;font-weight:600}
.pagination a:hover{background:#1738b8}
@media(max-width:900px){.sidebar{position:relative;width:100%;height:auto}.main{margin-left:0}}
</style>
</head>
<body>

<div class="sidebar">
  <h2>CertiTrack</h2>
  <a href="admin_dashboard.php">Dashboard</a>
  <a href="upload.php">Upload Certificate</a>
  <a href="view_certificates.php" style="background:rgba(31,79,216,.25);color:#fff">View Certificates</a>
  <a href="#">Settings</a>
  <a href="logout.php" style="color:#ffb3b3">Logout</a>
</div>

<div class="main">
  <div class="topbar">
    <h1>View Certificates</h1>
    <div class="profile">Admin: <?php echo $_SESSION['admin']; ?></div>
  </div>

  <!-- SEARCH FORM -->
  <form method="GET" style="margin-bottom:15px;">
    <input type="text" name="search" class="search" placeholder="Search by Name" value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit">Search</button>
  </form>

  <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Certificate Number</th>
          <th>Holder Name</th>
          <th>Course</th>
          <th>Issue Date</th>
          <th>Actions</th>
        </tr>
      </thead>
   <tbody>
<?php
$i = $offset + 1;
while($row = mysqli_fetch_assoc($result)){
    $formattedDate = date("F j, Y", strtotime($row['program_date']));
    echo '<tr>';
    echo '<td>'.$i.'</td>';
    echo '<td>'.$row['certificate_number'].'</td>';
    echo '<td>'.$row['full_name'].'</td>';
    echo '<td>'.$row['program'].'</td>';
    echo '<td>'.$formattedDate.'</td>';
echo '<td>
        <a href="certificate.php?id='.urlencode($row['certificate_number']).'" target="_blank">
            <button class="download">View</button>
        </a>
        <a href="?delete_id='.$row['id'].'" onclick="return confirm(\'Are you sure to delete this certificate?\')">
            <button class="delete">Delete</button>
        </a>
      </td>';

    echo '</tr>';
    $i++;
}
?>
</tbody>
  </table>

  <!-- PAGINATION -->
  <div class="pagination">
    <?php
    for($p=1;$p<=$totalPages;$p++){
        $active = ($p == $page) ? 'active' : '';
        $link = 'view_certificates.php?page='.$p;
        if($search!=='') $link .= '&search='.urlencode($search);
        echo '<a class="'.$active.'" href="'.$link.'">'.$p.'</a>';
    }
    ?>
  </div>

</div>
</body>
</html>
