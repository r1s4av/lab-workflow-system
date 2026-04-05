<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Lab Workflow System</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

/* GLOBAL */
body{
    margin:0;
    font-family:'Segoe UI',sans-serif;
    background: radial-gradient(circle at top right, #1e3a8a, #0f172a);
    color:white;
    overflow-x:hidden;
}

/* BACKGROUND GLOW */
body::before{
    content:"";
    position:fixed;
    width:500px;
    height:500px;
    background:rgba(59,130,246,0.3);
    filter:blur(120px);
    top:-100px;
    right:-100px;
    z-index:0;
}

/* NAVBAR */
.navbar{
    padding:20px 60px;
    position:relative;
    z-index:2;
}

.navbar-brand{
    font-weight:700;
    font-size:22px;
}

.navbar a{
    border-radius:10px;
}

/* HERO */
.hero{
    min-height:85vh;
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:60px 80px;
    position:relative;
    z-index:2;
}

.hero-text{
    max-width:600px;
    animation:fadeIn 1s ease;
}

.hero h1{
    font-size:52px;
    font-weight:800;
    line-height:1.2;
}

.hero p{
    margin-top:15px;
    font-size:18px;
    color:#cbd5f5;
}

/* CTA BUTTON */
.btn-main{
    margin-top:25px;
    background: linear-gradient(135deg, #22c55e, #16a34a);
    padding:14px 28px;
    border-radius:12px;
    font-weight:600;
    border:none;
    color:white;
}

.btn-main:hover{
    transform:scale(1.05);
}

/* IMAGE FLOAT */
.hero img{
    animation:float 5s ease-in-out infinite;
}

/* FEATURES */
.features{
    padding:80px 40px;
}

.feature-box{
    background:rgba(255,255,255,0.06);
    backdrop-filter: blur(12px);
    padding:30px;
    border-radius:18px;
    transition:0.3s;
}

.feature-box:hover{
    transform:translateY(-10px);
    background:rgba(255,255,255,0.12);
}

/* WORKFLOW */
.workflow{
    background:white;
    color:#111;
    padding:80px 20px;
    text-align:center;
}

.step{
    background:#f1f5f9;
    padding:20px;
    border-radius:12px;
    position:relative;
}

/* CONNECTOR LINE */
.step::after{
    content:"→";
    position:absolute;
    right:-15px;
    top:50%;
    transform:translateY(-50%);
    font-weight:bold;
    color:#9ca3af;
}

.step:last-child::after{
    display:none;
}

/* FOOTER */
.footer{
    text-align:center;
    padding:20px;
    color:#cbd5f5;
}

/* ANIMATIONS */
@keyframes float{
    0%{transform:translateY(0);}
    50%{transform:translateY(-15px);}
    100%{transform:translateY(0);}
}

@keyframes fadeIn{
    from{opacity:0; transform:translateY(20px);}
    to{opacity:1; transform:translateY(0);}
}

/* TYPING */
.typing{
    color:#22c55e;
    font-weight:600;
}

/* SCROLL ANIMATION */
.reveal{
    opacity:0;
    transform:translateY(30px);
    transition:0.6s;
}
.reveal.active{
    opacity:1;
    transform:translateY(0);
}

</style>

</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="container-fluid">
        <span class="navbar-brand text-white">🧪 Lab Workflow</span>
        <a href="login.php" class="btn btn-light">Login</a>
    </div>
</nav>

<!-- HERO -->
<section class="hero">

<div class="hero-text">
    <h1>
        Smart <span class="typing" id="typing"></span>
    </h1>

    <p>
        A modern DBMS-powered platform to track samples, manage tests,
        and ensure complete traceability in laboratory workflows.
    </p>

    <a href="login.php" class="btn-main">Explore System</a>
</div>

<div>
    <img src="https://cdn-icons-png.flaticon.com/512/2966/2966480.png" width="320">
</div>

</section>

<!-- FEATURES -->
<section class="features container reveal">

<h2 class="text-center mb-5">Why This System?</h2>

<div class="row g-4">

<div class="col-md-4">
<div class="feature-box">
<h5>🔗 Traceability</h5>
<p>Track every sample across all stages seamlessly.</p>
</div>
</div>

<div class="col-md-4">
<div class="feature-box">
<h5>🧠 Smart DB Design</h5>
<p>Built using ER models, normalization, and relationships.</p>
</div>
</div>

<div class="col-md-4">
<div class="feature-box">
<h5>⚡ Fast Workflow</h5>
<p>Efficient role-based operations for lab staff.</p>
</div>
</div>

</div>

</section>

<!-- WORKFLOW -->
<section class="workflow reveal">

<h2 class="mb-4">Workflow Overview</h2>

<div class="container">
<div class="row justify-content-center g-3">

<div class="col-md-2 step">Patient</div>
<div class="col-md-2 step">Sample</div>
<div class="col-md-2 step">Test</div>
<div class="col-md-2 step">Result</div>
<div class="col-md-2 step">Report</div>

</div>
</div>

</section>

<!-- FOOTER -->
<div class="footer">
    © 2026 Lab Workflow System | DBMS Project
</div>

<!-- TYPING SCRIPT -->
<script>
const words = ["Laboratory Workflow", "Sample Tracking", "DBMS System"];
let i = 0, j = 0, isDeleting = false;

function type() {
    const display = document.getElementById("typing");
    const word = words[i];

    if (isDeleting) j--;
    else j++;

    display.textContent = word.substring(0, j);

    let speed = isDeleting ? 50 : 100;

    if (!isDeleting && j === word.length){
        isDeleting = true;
        speed = 1200;
    }
    else if (isDeleting && j === 0){
        isDeleting = false;
        i = (i + 1) % words.length;
        speed = 300;
    }

    setTimeout(type, speed);
}
type();

/* SCROLL REVEAL */
function reveal(){
    document.querySelectorAll('.reveal').forEach(el=>{
        const top = el.getBoundingClientRect().top;
        if(top < window.innerHeight - 100){
            el.classList.add('active');
        }
    });
}
window.addEventListener('scroll', reveal);
</script>

</body>
</html>