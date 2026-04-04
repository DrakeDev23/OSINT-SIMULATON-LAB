<?php
session_start();
if(!isset($_SESSION['username'])){
    header('Location: index.php');
    exit();
}

$cat = $_GET['cat'] ?? '';
$lvl = (int)($_GET['lvl'] ?? 1);

$flags = [
    'OSINT'=>['flag1','flag2','flag3','flag4','flag5'],
    'Crypto'=>['flag1','flag2','flag3','flag4','flag5'],
    'XSS'=>['<script>alert()</script>','xss2','xss3','xss4','xss5']
];

$unlocked = $lvl == 1 || in_array($lvl-1, $_SESSION['cleared']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?= $cat ?> Level <?= $lvl ?></title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="hud">
    USER: <?= $_SESSION['username'] ?> | SCORE: <?= $_SESSION['score'] ?> | RANK: Novice
</div>

<div class="level-container">
<?php if(!$unlocked): ?>
    <p>This level is locked. Complete previous levels first.</p>
<?php else: ?>
    <?php if($cat !== 'XSS'): ?>
        <h2><?= $cat ?> Level <?= $lvl ?></h2>
        <form method="POST" action="submit_flag.php">
            <input type="hidden" name="cat" value="<?= $cat ?>">
            <input type="hidden" name="lvl" value="<?= $lvl ?>">
            <input type="text" name="flag" placeholder="Enter Flag" required>
            <button type="submit">Submit</button>
        </form>
    <?php else: ?>
        <h2>XSS Level <?= $lvl ?> - Vulnerable Input</h2>
        <form id="xssForm">
            <input type="text" id="xssInput" placeholder="Inject Payload" required>
            <button type="submit">Run</button>
        </form>
        <div id="output"></div>
        <script>
        const originalAlert = window.alert;
        window.alert = function(msg){
            fetch('submit_flag.php', {
                method:'POST',
                headers:{'Content-Type':'application/x-www-form-urlencoded'},
                body:'cat=XSS&lvl=<?= $lvl ?>&flag=<?= urlencode($flags['XSS'][$lvl-1]) ?>'
            }).then(()=>alert('Flag Awarded!'));
            originalAlert(msg);
        };

        document.getElementById('xssForm').addEventListener('submit', e=>{
            e.preventDefault();
            document.getElementById('output').innerHTML = document.getElementById('xssInput').value;
        });
        </script>
    <?php endif; ?>
<?php endif; ?>
</div>
</body>
</html>