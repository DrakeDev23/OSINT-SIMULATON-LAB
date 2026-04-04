<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}

$categories = ['OSINT'];
$levels_per_category = 5;

$score = $_SESSION['score'];
if ($score >= 100) $rank = "Elite Hacker";
elseif ($score >= 50) $rank = "Pro Hacker";
else $rank = "Script Kiddie";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dashboard</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="hud">
    USER: <?php echo $_SESSION['username']; ?> | 
    SCORE: <?php echo $_SESSION['score']; ?> | 
    RANK: <?php echo $rank; ?>
</div>

<h1>CTF Dashboard</h1>

<div class="categories">
<?php foreach($categories as $cat): ?>
    <div class="category">
        <h2><?php echo $cat; ?></h2>
        <ul>
        <?php for($i=1;$i<=$levels_per_category;$i++): 
            $level_id = $cat."_".$i;
            $locked = false;
            if($i > 1 && !in_array($cat."_".($i-1), $_SESSION['cleared'])) {
                $locked = true;
            }
        ?>
            <li class="<?php echo $locked ? 'locked' : 'unlocked'; ?>">
                <?php if(!$locked): ?>
                    <a href="challenge.php?cat=<?php echo $cat; ?>&level=<?php echo $i; ?>">
                        Level <?php echo $i; ?>
                    </a>
                <?php else: ?>
                    Level <?php echo $i; ?>
                <?php endif; ?>
            </li>
        <?php endfor; ?>
        </ul>
    </div>
<?php endforeach; ?>
</div>

</body>
</html>