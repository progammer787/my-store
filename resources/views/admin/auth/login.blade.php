<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login</title>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'DM Sans',sans-serif;background:#0d0d12;min-height:100vh;display:flex;align-items:center;justify-content:center;overflow:hidden}
.bg{position:fixed;inset:0;z-index:0}
canvas{display:block;width:100%;height:100%}
.login-wrap{position:relative;z-index:10;width:100%;max-width:420px;padding:24px}
.login-card{background:rgba(255,255,255,0.05);backdrop-filter:blur(24px);border:1px solid rgba(255,255,255,0.1);border-radius:20px;padding:44px 40px;box-shadow:0 32px 80px rgba(0,0,0,0.6)}
.login-logo{text-align:center;margin-bottom:32px}
.login-logo h1{font-family:'Syne',sans-serif;font-size:28px;font-weight:700;color:white;letter-spacing:-0.5px}
.login-logo span{color:#c8a96e}
.login-logo p{font-size:13px;color:rgba(255,255,255,0.4);margin-top:6px;letter-spacing:0.5px;text-transform:uppercase}
.form-group{margin-bottom:18px}
.form-group label{display:block;font-size:12px;font-weight:600;color:rgba(255,255,255,0.5);margin-bottom:8px;letter-spacing:0.5px;text-transform:uppercase}
.form-group .input-wrap{position:relative}
.form-group input{width:100%;background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);border-radius:10px;padding:13px 14px 13px 44px;color:white;font-size:14px;font-family:'DM Sans',sans-serif;outline:none;transition:all 0.25s}
.form-group input::placeholder{color:rgba(255,255,255,0.25)}
.form-group input:focus{border-color:rgba(200,169,110,0.6);background:rgba(255,255,255,0.08);box-shadow:0 0 0 3px rgba(200,169,110,0.1)}
.form-group .icon{position:absolute;left:14px;top:50%;transform:translateY(-50%);color:rgba(255,255,255,0.3);font-size:15px}
.error-msg{background:rgba(224,82,82,0.15);border:1px solid rgba(224,82,82,0.3);border-radius:8px;padding:12px 14px;font-size:13px;color:#f87171;margin-bottom:18px;display:flex;align-items:center;gap:8px}
.btn-login{width:100%;padding:14px;background:linear-gradient(135deg,#c8a96e,#a8893e);border:none;border-radius:10px;color:white;font-family:'Syne',sans-serif;font-size:15px;font-weight:600;cursor:pointer;transition:all 0.25s;letter-spacing:0.3px;margin-top:6px}
.btn-login:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(200,169,110,0.35)}
.remember-row{display:flex;align-items:center;gap:8px;margin-bottom:6px}
.remember-row label{font-size:13px;color:rgba(255,255,255,0.4);cursor:pointer}
.remember-row input[type=checkbox]{width:15px;height:15px;accent-color:#c8a96e;cursor:pointer}
</style>
</head>
<body>
<div class="bg"><canvas id="c"></canvas></div>

<div class="login-wrap">
    <div class="login-card">
        <div class="login-logo">
            <h1>Admin <span>Panel</span></h1>
            <p>Store Management System</p>
        </div>

        @if(session('error'))
            <div class="error-msg"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="error-msg"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>
        @endif

        <form action="{{ route('admin.login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Email Address</label>
                <div class="input-wrap">
                    <i class="fas fa-envelope icon"></i>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@store.com" required autofocus>
                </div>
            </div>
            <div class="form-group">
                <label>Password</label>
                <div class="input-wrap">
                    <i class="fas fa-lock icon"></i>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>
            </div>
            <div class="remember-row">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember">Remember me</label>
            </div>
            <button type="submit" class="btn-login">Sign In <i class="fas fa-arrow-right"></i></button>
        </form>
    </div>
</div>

<script>
const canvas = document.getElementById('c'), ctx = canvas.getContext('2d');
let W, H, nodes = [];
function resize(){ W = canvas.width = innerWidth; H = canvas.height = innerHeight; }
function init(){
    nodes = [];
    for(let i=0;i<80;i++) nodes.push({x:Math.random()*W,y:Math.random()*H,vx:(Math.random()-.5)*.4,vy:(Math.random()-.5)*.4,r:Math.random()*1.5+.5});
}
function draw(){
    ctx.clearRect(0,0,W,H);
    nodes.forEach(n=>{ n.x+=n.vx; n.y+=n.vy; if(n.x<0||n.x>W)n.vx*=-1; if(n.y<0||n.y>H)n.vy*=-1; });
    nodes.forEach((a,i)=>{ nodes.slice(i+1).forEach(b=>{
        const d=Math.hypot(a.x-b.x,a.y-b.y);
        if(d<130){ ctx.beginPath(); ctx.moveTo(a.x,a.y); ctx.lineTo(b.x,b.y); ctx.strokeStyle=`rgba(200,169,110,${.15*(1-d/130)})`; ctx.lineWidth=.5; ctx.stroke(); }
    }); });
    nodes.forEach(n=>{ ctx.beginPath(); ctx.arc(n.x,n.y,n.r,0,Math.PI*2); ctx.fillStyle='rgba(200,169,110,0.5)'; ctx.fill(); });
    requestAnimationFrame(draw);
}
window.addEventListener('resize',()=>{resize();init()});
resize(); init(); draw();
</script>
</body>
</html>