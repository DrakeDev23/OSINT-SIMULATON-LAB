<?php
session_start();
if(!isset($_SESSION['username'])) header('Location: index.php');

$level = (int)($_GET['level'] ?? 1);
$max_level = 5;

$cleared = $_SESSION['cleared'] ?? [];
$locked = false;
if($level > 1 && !in_array('OSINT_'.($level-1), $cleared)) {
    $locked = true;
}

$questions = [
    1 => "Who developed this game?",
    2 => "Find the hidden ",
    3 => "its easy right?, can you still find the hidden flag?",
    4 => "goodluck",
    5 => "final boss"
];

$flags = [
    1 => 'flag{drake}',      
    2 => 'flag{green}',      
    3 => 'flag{monospace}',  
    4 => 'flag{score}',      
    5 => 'flag{ctfcomplete}' 
];

if(!isset($questions[$level])) die("Invalid level.");

$question = $questions[$level];
$flag_correct = $flags[$level];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>OSINT Level <?php echo $level; ?></title>
<link rel="stylesheet" href="style.css">
<style>
body { 
    background:#0d0d0d; 
    color:#00ff41; 
    font-family:monospace; 
    margin:0; padding:0;
    display:flex; flex-direction:column; align-items:center; justify-content:flex-start; min-height:100vh;
}
.hud { 
    font-weight:bold; 
    margin:20px; 
}
.container {
    width: 90%; max-width: 600px; text-align:center; margin-top:50px;
    background:#111; padding:30px; border:2px solid #00ff41; border-radius:12px;
}
input, button { 
    background:#0d0d0d; 
    border:1px solid #00ff41; 
    color:#00ff41; padding:10px; font-family:monospace; font-size:16px;
    margin-top:10px;
}
button:hover { 
    background:#004400; cursor:pointer;
}
button:disabled, input:disabled { opacity:0.5; cursor:not-allowed; }
.modal { 
    display:none;
    position:fixed; 
    top:0; left:0;
    width:100%; height:100%; 
    background:rgba(0,0,0,0.9); 
    justify-content:center; 
    align-items:center; 
    color:#00ff41;
}
.modal-content { 
    background:#0d0d0d; 
    border:2px solid #00ff41; 
    padding:25px; 
    border-radius:10px; 
    text-align:center;
    max-width:400px;
}
</style>
</head>
<body>
<div class="hud">
USER: <?php echo $_SESSION['username']; ?> | SCORE: <?php echo $_SESSION['score']; ?>
</div>

<div class="container">
    <h1>OSINT Level <?php echo $level; ?></h1>
    <p><?php echo $question; ?></p>

    <form id="flagForm">
        <input type="text" id="flagInput" placeholder="Enter Flag" required <?php echo $locked ? 'disabled' : ''; ?>>
        <button type="submit" <?php echo $locked ? 'disabled' : ''; ?>>Submit</button>
    </form>

    <?php if($locked): ?>
        <p style="margin-top:15px; color:#ff4444; font-weight:bold;">
            This level is locked! Complete the previous level first.
        </p>
    <?php endif; ?>
</div>

<div id="modal" class="modal">
  <div class="modal-content">
    <p id="modalMessage"></p>
    <button id="nextLevel">Next Level</button>
  </div>
</div>

<?php
if(!$locked) {
    if($level==2):
        echo "<!--Open Source Intelligence (OSINT) refers to the practice of collecting, analyzing, and utilizing publicly available information to gain insights, solve problems, or support decision-making.  This information can come from a wide range of sources, including social media platforms, news articles, government reports, online forums, and public databases.flag{green} OSINT is widely used in fields such as cybersecurity, law enforcement, journalism, and business intelligence because it allows investigators and analysts to gather valuable data without the need for covert operations or classified access.  By leveraging analytical tools and research techniques, OSINT practitioners can identify trends, detect threats, verify facts, and uncover hidden connections, making it an essential skill for modern intelligence and research activities.-->";
    elseif($level==3):
        echo "<script>var hiddenFlag='flag{monospace}'; console.log('Level 3 hint: Check hiddenFlag variable');</script>";
    elseif($level==4):
        echo "<div id='hiddenScore' style='display:none;'>flag{score}</div>";
    elseif($level==5):
        echo "<script>
            var part1='flag{';
            var part2='ctf';
            var part3='complete}';
            console.log('Combine parts: '+part1+part2+part3);
            </script>";
    endif;
}
?>

<script>
const correctFlag = "<?php echo $flag_correct; ?>";
const form = document.getElementById('flagForm');
const input = document.getElementById('flagInput');
const modal = document.getElementById('modal');
const message = document.getElementById('modalMessage');
const nextBtn = document.getElementById('nextLevel');

if(!<?php echo $locked ? 'true' : 'false'; ?>){
form.addEventListener('submit', function(e){
    e.preventDefault();
    const val = input.value.trim();
    if(val === correctFlag){
        fetch('submit_flag.php', {
            method:'POST',
            headers:{'Content-Type':'application/x-www-form-urlencoded'},
            body:'level=OSINT_<?php echo $level; ?>&flag='+encodeURIComponent(val)
        }).then(()=>{
            message.textContent = "Correct! Flag found!";
            modal.style.display = "flex";
        });
    } else {
        message.textContent = "Incorrect! Inspect the page carefully (source, console, elements).";
        modal.style.display = "flex";
    }
});
}

nextBtn.addEventListener('click', ()=>{
    modal.style.display = "none";
    const nextLevel = <?php echo $level; ?> + 1;
    if(nextLevel <= <?php echo $max_level; ?>){
        window.location.href = "challenge.php?level="+nextLevel;
    } else {
        alert("Congratulations! You finished all levels!");
    }
});
</script>
</body>
</html>