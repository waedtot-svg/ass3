<?php

declare(strict_types=1);
header('Content-Type: text/html; charset=UTF-8');

$students = [
    ['stdNo'=>'20003','stdName'=>'Ahmed Ali','stdEmail'=>'ahmed@gmail.com','stdGAP'=>88.7],
    ['stdNo'=>'30304','stdName'=>'Mona Khalid','stdEmail'=>'mona@gmail.com','stdGAP'=>78.5],
    ['stdNo'=>'10002','stdName'=>'Bilal Hmaza','stdEmail'=>'bilal@gmail.com','stdGAP'=>98.7],
    ['stdNo'=>'10005','stdName'=>'Said Ali','stdEmail'=>'said@gmail.com','stdGAP'=>98.7],
    ['stdNo'=>'10007','stdName'=>'Mohammed Ahmed','stdEmail'=>'mohamed@gmail.com','stdGAP'=>98.7],
];

function calcGrade(float $gpa): string {
    if ($gpa >= 90) return 'A+';
    if ($gpa >= 80) return 'B';
    if ($gpa >= 70) return 'C';
    if ($gpa >= 60) return 'D';
    return 'F';
}

// Read filters
$qNo = isset($_GET['q']) ? trim($_GET['q']) : '';
$qGrade = isset($_GET['grade']) ? trim($_GET['grade']) : 'all';

// Apply filters
$filtered = array_values(array_filter($students, function($st) use ($qNo, $qGrade) {
    $okNo = $qNo === '' ? true : (strpos($st['stdNo'], $qNo) !== false);
    $grade = calcGrade((float)$st['stdGAP']);
    $okGrade = ($qGrade === 'all') ? true : ($grade === $qGrade);
    return $okNo && $okGrade;
}));

$total = count($filtered);
?>
<!doctype html>
<html lang="ar" dir="rtl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Students Table</title>
<style>
  :root{
    --radius: 16px;
  }
  *{box-sizing:border-box}
body{
    margin:0;
    font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Noto Sans Arabic", Arial, sans-serif;
    background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%);

    min-height:100vh;
    padding:32px 18px;
}

  .wrap{max-width:980px;margin:0 auto;}
  .controls{
    display:flex; gap:14px; align-items:center; margin-bottom:18px;
    background:rgba(255,255,255,.35); padding:10px; border-radius:999px; backdrop-filter: blur(6px);
  }
  .controls input[type="text"], .controls select{
    flex:1; border:none; outline:none; background:#ffffffd9; padding:12px 16px;
    border-radius:999px; font-size:15px;
  }
  .controls .btn{
    border:none; padding:12px 22px; border-radius:999px; cursor:pointer; font-weight:600;
    background: linear-gradient(90deg,#7c63ff,#b257ff);
    color:#fff;
  }
  .controls .btn.secondary{ background:#ffffffd9; color:#333; }
  .card{
    background:#fff; border-radius:24px; box-shadow:0 20px 40px rgba(0,0,0,.12);
    padding: 6px 6px 14px; overflow:hidden;
  }
  table{width:100%; border-collapse:collapse; background:#fff; border-radius:16px; overflow:hidden;}
  th,td{padding:14px; border-bottom:1px solid #eee; text-align:right; font-size:15px}
  th{background:#fafafa; font-weight:700}
  tr:hover td{background:#fcfcff}
  tfoot td{font-weight:800; text-align:center}
  .toolbar{display:flex; justify-content:flex-end; margin:10px 4px 14px}
  .print{border:none; padding:10px 16px; border-radius:999px; background:#111; color:#fff; cursor:pointer}
  @media print{
    body{background:#fff}
    .controls,.toolbar{display:none !important}
    .card{box-shadow:none}
    th,td{border-color:#ccc}
  }
</style>
</head>
<body>
  <div class="wrap">

    <!-- Filters -->
    <form class="controls" method="get" action="">
      <input type="text" name="q" placeholder="Student No" value="<?php echo htmlspecialchars($qNo, ENT_QUOTES,'UTF-8'); ?>">
      <select name="grade" aria-label="Grade filter">
        <?php
          $grades = ['all'=>'All Grades','A+'=>'A+','B'=>'B','C'=>'C','D'=>'D','F'=>'F'];
          foreach($grades as $val=>$label){
            $sel = ($qGrade === (string)$val) ? 'selected' : '';
            $v = htmlspecialchars((string)$val, ENT_QUOTES, 'UTF-8');
            echo "<option value=\"$v\" $sel>".htmlspecialchars($label,ENT_QUOTES,'UTF-8')."</option>";
          }
        ?>
      </select>
      <button class="btn" type="submit">Search</button>
      <a class="btn secondary" href="<?php echo strtok($_SERVER['REQUEST_URI'], '?'); ?>">Reset</a>
    </form>

    <div class="card">
      <div class="toolbar">
        <button class="print" onclick="window.print()">Print as PDF</button>
      </div>

      <table aria-label="Students table">
        <thead>
          <tr>
            <th style="width:70px">#</th>
            <th>Student No</th>
            <th>Name</th>
            <th>Email</th>
            <th>GPA</th>
            <th>Grade</th>
          </tr>
        </thead>
        <tbody>
          <?php $i=1; foreach($filtered as $st): $grade = calcGrade((float)$st['stdGAP']); ?>
            <tr>
              <td><?php echo $i++; ?></td>
              <td><?php echo htmlspecialchars($st['stdNo'], ENT_QUOTES,'UTF-8'); ?></td>
              <td><?php echo htmlspecialchars($st['stdName'], ENT_QUOTES,'UTF-8'); ?></td>
              <td><?php echo htmlspecialchars($st['stdEmail'], ENT_QUOTES,'UTF-8'); ?></td>
              <td><?php echo htmlspecialchars((string)$st['stdGAP'], ENT_QUOTES,'UTF-8'); ?></td>
              <td><strong><?php echo $grade; ?></strong></td>
            </tr>
          <?php endforeach; if(!$filtered): ?>
            <tr><td colspan="6" style="text-align:center;color:#666">No matching results found  </td></tr>
          <?php endif; ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="6">Students Number: <?php echo $total; ?></td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</body>
</html>
