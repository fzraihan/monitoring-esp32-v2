<?php
// include "koneksi.php";
$page = isset($_GET['page']) ? $_GET['page'] : 'beranda';
$supabaseUrl = "https://fykjfyieanedfjrctrdi.supabase.co/rest/v1/data_sensor";
$supabaseKey = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImZ5a2pmeWllYW5lZGZqcmN0cmRpIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzIzMDYxMDksImV4cCI6MjA4Nzg4MjEwOX0.-_HYcQXNrj065l7GO-uRkTviTPv0caYaqNZsfl1aDfQ";

// FUNCTION GET DATA
function getSupabaseData($url,$key){
    $opts = [
        "http" => [
            "method" => "GET",
            "header" => "apikey: $key\r\nAuthorization: Bearer $key\r\n"
        ]
    ];
    $context = stream_context_create($opts);
    $result = file_get_contents($url,false,$context);
    return json_decode($result,true);
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Sistem Monitoring Agroklimat</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
body{
    font-family:'Segoe UI',sans-serif;
    background: linear-gradient(-45deg,#0f2027,#203a43,#2c5364,#1e3c72);
    background-size: 400% 400%;
    animation: gradientMove 12s ease infinite;
    color:white;
}
html, body{
    margin:0;
    padding:0;
    height:100%;
}

@keyframes gradientMove{
    0%{background-position:0% 50%;}
    50%{background-position:100% 50%;}
    100%{background-position:0% 50%;}
}
.sidebar{height:100vh;background:#0b1120;padding:25px;}
.sidebar a{display:block;padding:10px 0;text-decoration:none;color:#9ca3af;transition:0.3s;}
.sidebar a:hover{color:#38bdf8;}
.aktif{color:#38bdf8!important;font-weight:bold;text-shadow:0 0 10px rgba(56,189,248,0.8);}
.card-custom{background:rgba(255,255,255,0.05);border:none;border-radius:15px;padding:20px;transition:0.4s;}
.card-custom:hover{transform:translateY(-6px);box-shadow:0 10px 25px rgba(0,0,0,0.4);}
.big-number{font-size:28px;font-weight:600;}
.no-data-box{background:rgba(255,255,255,0.08);border-radius:15px;padding:40px;text-align:center;}
.alert-custom{position:fixed;top:20px;right:20px;background:#ef4444;padding:15px 25px;border-radius:10px;display:none;}


/* ===== FUTURISTIC AI STYLE ===== */

.chart-card{
    position:relative;
    background: linear-gradient(145deg, rgba(15,23,42,0.7), rgba(30,58,138,0.4));
    border:1px solid rgba(56,189,248,0.2);
    box-shadow: 0 0 30px rgba(56,189,248,0.15);
    backdrop-filter: blur(15px);
    overflow:hidden;
}

/* Animated scanning line */
.chart-card::before{
    content:"";
    position:absolute;
    top:-100%;
    left:0;
    width:100%;
    height:100%;
    background: linear-gradient(
        to bottom,
        transparent,
        rgba(56,189,248,0.15),
        transparent
    );
    animation: scan 6s linear infinite;
}

@keyframes scan{
    0%{top:-100%;}
    100%{top:100%;}
}
    
/* ================= LOADING SCREEN PERFECT CENTER ================= */
#loadingScreen{
    position:fixed;
    inset:0; /* top:0; left:0; right:0; bottom:0 */
    width:100%;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background: linear-gradient(-45deg,#0f2027,#203a43,#2c5364,#1e3c72);
    background-size:400% 400%;
    animation: gradientMove 12s ease infinite;
    z-index:99999;
    transition:opacity 0.6s ease, visibility 0.6s ease;
}

/* ===== SIDEBAR ICON STYLE ===== */
.sidebar i{
    font-size:18px;
    transition:0.3s;
}

.sidebar a:hover i{
    transform:translateX(4px);
}

.aktif i{
    color:#38bdf8;
    text-shadow:0 0 8px rgba(56,189,248,0.8);
}

.loader-content{
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
    text-align:center;
}

.loader-spinner{
    width:70px;
    height:70px;
    border:6px solid rgba(255,255,255,0.15);
    border-top:6px solid #38bdf8;
    border-radius:50%;
    animation: spin 1s linear infinite;
    margin-bottom:25px;
}

@keyframes spin{
    0%{transform:rotate(0deg);}
    100%{transform:rotate(360deg);}
}

.fade-out{
    opacity:0;
    visibility:hidden;
}

/* ===== TABEL DARK MENYATU ===== */
.table-dark-custom {
    border-collapse: separate;
    border-spacing: 0 10px;
    color: white;
}

.table-dark-custom thead th {
    background: rgba(255,255,255,0.08);
    border: none;
    padding: 14px;
    font-size: 14px;
    letter-spacing: 1px;
    text-transform: uppercase;
}

.table-dark-custom tbody tr {
    background: rgba(255,255,255,0.05);
    transition: 0.3s ease;
}

.table-dark-custom tbody tr:hover {
    background: rgba(56,189,248,0.15);
    transform: scale(1.01);
}

.table-dark-custom td {
    border: none;
    padding: 14px;
}

.table-dark-custom tbody tr td:first-child {
    border-top-left-radius: 12px;
    border-bottom-left-radius: 12px;
}

.table-dark-custom tbody tr td:last-child {
    border-top-right-radius: 12px;
    border-bottom-right-radius: 12px;
}

/* ===== AI MODERN DESIGN ===== */

.ai-card{
    background: linear-gradient(135deg, rgba(56,189,248,0.08), rgba(99,102,241,0.08));
    backdrop-filter: blur(10px);
}

.ai-box{
    background: rgba(255,255,255,0.05);
    padding:20px;
    border-radius:14px;
    transition:0.3s ease;
    border:1px solid rgba(255,255,255,0.08);
}

.ai-box:hover{
    transform:translateY(-5px);
    box-shadow:0 10px 30px rgba(0,0,0,0.3);
}

.ai-label{
    font-size:14px;
    opacity:0.8;
    margin-bottom:8px;
}

.ai-value{
    font-size:26px;
    font-weight:700;
    color:#38bdf8;
}

.ai-sub{
    font-size:12px;
    opacity:0.6;
    margin-top:6px;
}

.ai-status-badge{
    padding:8px 18px;
    border-radius:20px;
    font-weight:600;
    background:rgba(34,197,94,0.2);
    color:#22c55e;
    transition:0.3s;
}

/* ===== MODERN LAPORAN TABLE ===== */

.laporan-card{
    background: linear-gradient(135deg, rgba(99,102,241,0.08), rgba(56,189,248,0.08));
    backdrop-filter: blur(10px);
}

.laporan-table thead th{
    font-size:13px;
    letter-spacing:1px;
    text-transform:uppercase;
    opacity:0.7;
    border-bottom:1px solid rgba(255,255,255,0.1);
}

.laporan-table tbody tr{
    background:rgba(255,255,255,0.04);
    border-radius:12px;
    transition:0.3s;
}

.laporan-table tbody tr:hover{
    background:rgba(56,189,248,0.1);
    transform:scale(1.01);
}

.laporan-table td{
    padding:16px 12px;
}

.id-badge{
    background:rgba(255,255,255,0.1);
    padding:6px 12px;
    border-radius:20px;
    font-weight:600;
}

.time-text{
    font-size:13px;
    opacity:0.7;
}

.laporan-table tbody tr{
    background:rgba(255,255,255,0.04);
}

.laporan-table td{
    padding:14px;
}

/* ===== DATE RANGE PRO ===== */

.date-range-box{
    display:flex;
    align-items:center;
    gap:8px;
    flex-wrap:wrap;
}

.date-input{
    background: rgba(255,255,255,0.08);
    border:1px solid rgba(255,255,255,0.15);
    color:white;
    padding:6px 12px;
    border-radius:10px;
    backdrop-filter: blur(10px);
    transition:0.3s;
}

.date-input:focus{
    outline:none;
    border-color:#38bdf8;
    box-shadow:0 0 0 2px rgba(56,189,248,0.2);
}

.date-input::-webkit-calendar-picker-indicator{
    filter: invert(1);
    cursor:pointer;
}

.quick-filter-box{
    display:flex;
    gap:8px;
}

.quick-btn{
    background: rgba(255,255,255,0.08);
    border:1px solid rgba(255,255,255,0.15);
    color:white;
    padding:6px 12px;
    border-radius:20px;
    transition:0.3s;
}

.quick-btn:hover{
    background: rgba(56,189,248,0.2);
    border-color:#38bdf8;
}

.filter-active{
    box-shadow:0 0 12px rgba(56,189,248,0.4);
}

.trend-up{
    color:#22c55e;
    font-weight:600;
}

.trend-down{
    color:#ef4444;
    font-weight:600;
}

.trend-stable{
    color:#facc15;
    font-weight:600;
}
.apexcharts-canvas{
    margin: 0 auto;
}

.chart-card{
    padding:25px;
}

.chart-wrapper{
    position:relative;
    height:450px;   /* ini yang bikin normal */
    width:100%;
}

.live-badge{
    font-size:12px;
    color:#22c55e;
    animation:pulse 1.5s infinite;
}

@keyframes pulse{
    0%{opacity:1;}
    50%{opacity:0.4;}
    100%{opacity:1;}
}

.ai-grid{
    position:fixed;
    inset:0;
    background-image:
        linear-gradient(rgba(56,189,248,0.05) 1px, transparent 1px),
        linear-gradient(90deg, rgba(56,189,248,0.05) 1px, transparent 1px);
    background-size: 40px 40px;
    pointer-events:none;
    z-index:-1;
}

.ai-clock{
    font-size:28px;
    font-weight:700;
    text-align:right;
    color:#38bdf8;
    text-shadow:
        0 0 5px #38bdf8,
        0 0 10px #38bdf8,
        0 0 20px #0ea5e9;
    letter-spacing:2px;
    margin-bottom:10px;
}

    #tsparticles{
    position:fixed;
    inset:0;
    z-index:-2;
}

</style>
</head>

<body>
    <script>
// HIDE LOADING SETELAH HALAMAN SIAP
window.addEventListener("load", function(){
    const loader = document.getElementById("loadingScreen");
    setTimeout(()=>{
        loader.classList.add("fade-out");
    }, 800); // delay supaya smooth
});

        tsParticles.load("tsparticles", {
    background: { color: "transparent" },
    particles: {
        number: { value: 40 },
        color: { value: "#38bdf8" },
        opacity: { value: 0.2 },
        size: { value: 2 },
        move: {
            enable: true,
            speed: 0.6
        }
    }
});
</script>

<!-- LOADING SCREEN -->
<div id="loadingScreen">
    <div class="loader-content">
        <div class="loader-spinner"></div>
        <h4>Harap Tunggu...</h4>
        <p>Memuat Sistem Monitoring Agroklimat</p>
    </div>
</div>

<div class="alert-custom" id="alertBox">
⚠️ PERINGATAN! Kondisi lingkungan tidak normal!
</div>

<div class="container-fluid">
<div class="row">

<!-- SIDEBAR -->
<div class="col-md-2 sidebar">
<h5>SISTEM AGROKLIMAT</h5>
<hr>
<a href="index.php?page=beranda" class="<?= $page=='beranda'?'aktif':'' ?>">
    <i class="bi bi-speedometer2 me-2"></i> Beranda
</a>

<a href="index.php?page=analisis" class="<?= $page=='analisis'?'aktif':'' ?>">
    <i class="bi bi-bar-chart-line me-2"></i> Analisis Data
</a>

<a href="index.php?page=laporan" class="<?= $page=='laporan'?'aktif':'' ?>">
    <i class="bi bi-file-earmark-text me-2"></i> Laporan
</a>

</div>

<!-- MAIN -->
<div class="col-md-10 p-4">

<?php
/* ======================================================
   HALAMAN BERANDA
====================================================== */
if($page=='beranda'): 

$data = getSupabaseData($supabaseUrl."?select=*&order=created_at.desc&limit=1",$supabaseKey);

if(empty($data)):
?>

<div class="no-data-box">
<h4>⚠ Belum Ada Data Sensor</h4>
<p>Sistem belum menerima data dari ESP32.</p>
</div>

<?php else:

$latest = $data[0];
?>

<h3 class="mb-4">Dashboard Pemantauan Agroklimat</h3>

<div class="row mb-4">

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card-custom text-center h-100">
            <h6>🌡 Suhu Udara</h6>
            <div id="gaugeSuhu"></div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card-custom text-center h-100">
            <h6>💧 Kelembaban Udara</h6>
            <div id="gaugeKelembaban"></div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card-custom text-center h-100">
            <h6>🌱 Kadar Air Tanah</h6>
            <div id="gaugeTanah"></div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card-custom text-center h-100">
            <h6>🌬 Kecepatan Angin</h6>
            <div id="gaugeAngin"></div>
        </div>
    </div>

</div>

<!-- ================= GRAFIK FULL WIDTH ================= -->
<div class="row">
    <div class="col-12">
        <div class="card-custom mb-4 chart-card">
            <div class="ai-clock" id="aiClock"></div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                    <h5 class="mb-1">📊 Grafik Parameter Real-Time</h5>
                    <small id="lastUpdate" style="opacity:0.6;"></small>
                </div>
                <span class="live-badge">● Live</span>
            </div>

            <div class="chart-wrapper">
                <canvas id="chartMulti"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="text-end" style="opacity:0.5; font-size:12px;">
    Data diambil otomatis dari ESP32 melalui Supabase Cloud setiap 10 detik
</div>

<!-- =================AI SUMMARY VISUAL================= -->
    <div class="card-custom mb-4">
    <h5 class="mb-3">AI Environmental Index</h5>

    <div class="progress mb-3" style="height:20px;">
        <div id="indexBar" class="progress-bar bg-info" style="width:0%"></div>
    </div>

    <div id="indexLabel"></div>
</div>
    

<script>
document.addEventListener("DOMContentLoaded", function(){
    
    const canvas = document.getElementById("chartMulti");
    if(!canvas){
        console.log("Canvas tidak ditemukan");
        return;
    }
    const ctx = document.getElementById("chartMulti").getContext("2d");

    const gradientSuhu = ctx.createLinearGradient(0,0,0,400);
    gradientSuhu.addColorStop(0,"rgba(239,68,68,0.4)");
    gradientSuhu.addColorStop(1,"rgba(239,68,68,0)");

    const gradientKelembaban = ctx.createLinearGradient(0,0,0,400);
    gradientKelembaban.addColorStop(0,"rgba(34,197,94,0.4)");
    gradientKelembaban.addColorStop(1,"rgba(34,197,94,0)");

    const gradientTanah = ctx.createLinearGradient(0,0,0,400);
    gradientTanah.addColorStop(0,"rgba(59,130,246,0.4)");
    gradientTanah.addColorStop(1,"rgba(59,130,246,0)");

    const gradientAngin = ctx.createLinearGradient(0,0,0,400);
    gradientAngin.addColorStop(0,"rgba(250,204,21,0.4)");
    gradientAngin.addColorStop(1,"rgba(250,204,21,0)");

    let chart = new Chart(ctx,{
        type:"line",
        data:{
            labels:[],
            datasets:[
                {
                    label:"🌡 Suhu",
                    data:[],
                    borderColor:"#ef4444",
                    backgroundColor:gradientSuhu,
                    fill:true,
                    tension:0.4,
                    pointRadius:4,
                    pointHoverRadius:7
                },
                {
                    label:"💧 Kelembaban",
                    data:[],
                    borderColor:"#22c55e",
                    backgroundColor:gradientKelembaban,
                    fill:true,
                    tension:0.4,
                    pointRadius:4,
                    pointHoverRadius:7
                },
                {
                    label:"🌱 Tanah",
                    data:[],
                    borderColor:"#3b82f6",
                    backgroundColor:gradientTanah,
                    fill:true,
                    tension:0.4,
                    pointRadius:4,
                    pointHoverRadius:7
                },
                {
                    label:"🌬 Angin",
                    data:[],
                    borderColor:"#facc15",
                    backgroundColor:gradientAngin,
                    fill:true,
                    tension:0.4,
                    pointRadius:4,
                    pointHoverRadius:7
                }
            ]
        },
      
    options:{
    responsive:true,
    maintainAspectRatio:false,

    animation:{
        duration: 900,
        easing: 'easeOutQuart'
    },

    interaction:{
        mode:"index",
        intersect:false
    },

    plugins:{
        legend:{
            position:"top",
            labels:{
                color:"white",
                padding:20
                }
            },
            tooltip:{
                backgroundColor:"#0f172a",
                borderColor:"#38bdf8",
                borderWidth:1,
                padding:12,
                displayColors:true
            }
        },
        scales:{
            x:{
                ticks:{color:"#94a3b8"},
                grid:{color:"rgba(255,255,255,0.05)"}
            },
            y:{
                ticks:{color:"#94a3b8"},
                grid:{color:"rgba(255,255,255,0.05)"}
            
            }
        }
    }
});

    function loadData(){
        console.log("Ambil data...");
        fetch("ambil_data_supabase.php?t=" + new Date().getTime())
        .then(res=>res.json())
        .then(data=>{

            if(!data || data.length===0) return;

            data.reverse();

            chart.data.labels = data.map(d=> 
                new Date(d.created_at).toLocaleTimeString()
            );

            chart.data.datasets[0].data = data.map(d=>d.suhu_udara);
            chart.data.datasets[1].data = data.map(d=>d.kelembaban_udara);
            chart.data.datasets[2].data = data.map(d=>d.kadar_air_tanah);
            chart.data.datasets[3].data = data.map(d=>d.kecepatan_angin);

            if(chart.data.labels.length > 50){
    chart.data.labels.shift();
    chart.data.datasets.forEach(ds => ds.data.shift());
}
            chart.update('active');
            

if(!data || data.length === 0){     console.log("Data kosong");     return; }  const last = data[data.length-1];

let status = "NORMAL";
let color = "#22c55e";

if(last.suhu_udara > 32){
    status = "OVERHEAT";
    color = "#ef4444";
}

document.getElementById("lastUpdate").innerHTML =
    `<span style="color:${color}">
        AI Status: ${status} | ${new Date().toLocaleTimeString()}
     </span>`;
            
        });
    }

    loadData();
    setInterval(loadData,10000);

    function updateClock(){
    const now = new Date();
    document.getElementById("aiClock").innerText =
        now.toLocaleTimeString();
        
}
setInterval(updateClock,1000);
updateClock();

function calculateIndex(){

    let avgTemp = <?= round($stat['rata_suhu'],2) ?>;
    let score = 100 - Math.abs(avgTemp - 28) * 5;

    if(score < 0) score = 0;
    if(score > 100) score = 100;

    document.getElementById("indexBar").style.width = score+"%";
    document.getElementById("indexBar").innerText = Math.round(score)+"%";

    let status = "OPTIMAL";
    let color = "bg-success";

    if(score < 60){
        status = "WARNING";
        color = "bg-warning";
    }

    if(score < 40){
        status = "CRITICAL";
        color = "bg-danger";
    }

    document.getElementById("indexBar").className = "progress-bar "+color;
    document.getElementById("indexLabel").innerHTML =
        "<strong>Status:</strong> "+status;
}

document.addEventListener("DOMContentLoaded", calculateIndex);

    
});
</script>

<?php endif; ?>


<?php
/* ======================================================
   HALAMAN ANALISIS
====================================================== */
 elseif($page=='analisis'):



$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;

$dataArr = getSupabaseData(
    $supabaseUrl."?select=*&order=created_at.desc&limit=$limit",
    $supabaseKey
);

if(empty($dataArr)):
?>

<div class="no-data-box">
    <h4>⚠ Belum Ada Data Untuk Dianalisis</h4>
</div>

<?php else:

$dataArr = array_reverse($dataArr);

// HITUNG STATISTIK
$suhu = array_column($dataArr,'suhu_udara');
$kelembaban = array_column($dataArr,'kelembaban_udara');
$tanah = array_column($dataArr,'kadar_air_tanah');
$angin = array_column($dataArr,'kecepatan_angin');

$stat = [
'rata_suhu'=>array_sum($suhu)/count($suhu),
'max_suhu'=>max($suhu),
'min_suhu'=>min($suhu),
'rata_k'=>array_sum($kelembaban)/count($kelembaban),
'rata_soil'=>array_sum($tanah)/count($tanah),
'rata_angin'=>array_sum($angin)/count($angin)
];
?>

<h3 class="mb-4">Analisis Statistik Lingkungan</h3>

<!-- FILTER -->
<div class="card-custom mb-4">
    <form method="GET">
        <input type="hidden" name="page" value="analisis">
        <label class="me-2">Tampilkan Data:</label>
        <select name="limit" onchange="this.form.submit()" class="form-select w-25 d-inline-block">
            <option value="20" <?= $limit==20?'selected':'' ?>>20 Data</option>
            <option value="50" <?= $limit==50?'selected':'' ?>>50 Data</option>
            <option value="100" <?= $limit==100?'selected':'' ?>>100 Data</option>
        </select>
    </form>
</div>

<!-- RINGKASAN STATISTIK -->
<div class="card-custom mb-4">
<h5 class="mb-4">Ringkasan Statistik</h5>

<div class="stat-grid">

    <div class="stat-item">
        <span class="stat-label">Rata-rata Suhu Udara   :</span>
        <span class="stat-value"><?= round($stat['rata_suhu'],2) ?> °C</span>
    </div>

    <div class="stat-item">
        <span class="stat-label">Suhu Maksimum :</span>
        <span class="stat-value"><?= $stat['max_suhu'] ?> °C</span>
    </div>

    <div class="stat-item">
        <span class="stat-label">Suhu Minimum :</span>
        <span class="stat-value"><?= $stat['min_suhu'] ?> °C</span>
    </div>

    <div class="stat-item">
        <span class="stat-label">Rata-rata Kelembaban Udara :</span>
        <span class="stat-value"><?= round($stat['rata_k'],2) ?> %</span>
    </div>

    <div class="stat-item">
        <span class="stat-label">Rata-rata Kadar Air Tanah  :</span>
        <span class="stat-value"><?= round($stat['rata_soil'],2) ?> %</span>
    </div>

    <div class="stat-item">
        <span class="stat-label">Rata-rata Kecepatan Angin  :</span>
        <span class="stat-value"><?= round($stat['rata_angin'],2) ?> m/s</span>
    </div>

    <div class="card-custom mb-4 ai-card">
    <h5 class="mb-4">
        <i class="bi bi-cpu"></i> AI Forecast & Anomaly Detection
    </h5>

    <div class="row text-center">

        <div class="col-md-3">
            <div class="ai-box">
                <div class="ai-label">🌡 Suhu</div>
                <div id="predSuhu" class="ai-value">-</div>
                <div class="ai-sub">Prediksi 1 Jam</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="ai-box">
                <div class="ai-label">💧 Kelembaban</div>
                <div id="predKelembaban" class="ai-value">-</div>
                <div class="ai-sub">Prediksi 1 Jam</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="ai-box">
                <div class="ai-label">🌱 Kadar Tanah</div>
                <div id="predTanah" class="ai-value">-</div>
                <div class="ai-sub">Prediksi 1 Jam</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="ai-box">
                <div class="ai-label">🌬 Angin</div>
                <div id="predAngin" class="ai-value">-</div>
                <div class="ai-sub">Prediksi 1 Jam</div>
            </div>
        </div>

    </div>

    <div class="mt-4 text-center">
        <span id="anomalyStatus" class="ai-status-badge">Status: -</span>
    </div>
</div>

</div>
</div>

<script>

const dataAnalisis = <?= json_encode($dataArr ?? []) ?>;

function linearRegression(y){
    let n = y.length;
    let sumX=0,sumY=0,sumXY=0,sumXX=0;

    for(let i=0;i<n;i++){
        sumX += i;
        sumY += y[i];
        sumXY += i*y[i];
        sumXX += i*i;
    }

    let slope = (n*sumXY - sumX*sumY)/(n*sumXX - sumX*sumX);
    let intercept = (sumY - slope*sumX)/n;

    return {slope,intercept};
}

function runAI(){

    if(!dataAnalisis || dataAnalisis.length < 3){
        console.log("Data kurang");
        return;
    }

    let suhu = dataAnalisis.map(d=>parseFloat(d.suhu_udara));
    let kelembaban = dataAnalisis.map(d=>parseFloat(d.kelembaban_udara));
    let tanah = dataAnalisis.map(d=>parseFloat(d.kadar_air_tanah));
    let angin = dataAnalisis.map(d=>parseFloat(d.kecepatan_angin));

    function predict(data){
        let model = linearRegression(data);
        return model.intercept + model.slope*(data.length+60);
    }

    document.getElementById("predSuhu").innerText = predict(suhu).toFixed(2)+" °C";
    document.getElementById("predKelembaban").innerText = predict(kelembaban).toFixed(2)+" %";
    document.getElementById("predTanah").innerText = predict(tanah).toFixed(2)+" %";
    document.getElementById("predAngin").innerText = predict(angin).toFixed(2)+" m/s";
}

document.addEventListener("DOMContentLoaded", function(){
    runAI();
});

</script>



<!-- Tren Data -->
<div class="card-custom mb-4">
<h5 class="mb-4">Ringkasan Kondisi Terkini</h5>

<div class="row">

<?php
$last = end($dataArr);
$prev = prev($dataArr);

function trendInfo($now,$before){

    $diff = $now - $before;

    if($before == 0){
        $percent = 0;
    } else {
        $percent = ($diff / $before) * 100;
    }

    if($diff > 0){
        return "<span class='trend-up'>
                    📈 Naik +".round($diff,2)." (".round($percent,1)."%) 
                </span>";
    }
    elseif($diff < 0){
        return "<span class='trend-down'>
                    📉 Turun ".round($diff,2)." (".round($percent,1)."%) 
                </span>";
    }
    else{
        return "<span class='trend-stable'>
                    ➖ Stabil
                </span>";
    }
}
?>

<div class="col-md-3">
<div class="p-3 bg-dark rounded">
<h6>Suhu</h6>
<h3><?= $last['suhu_udara'] ?> °C</h3>
<div><?= trendInfo($last['suhu_udara'],$prev['suhu_udara']) ?></div>
</div>
</div>

<div class="col-md-3">
<div class="p-3 bg-dark rounded">
<h6>Kelembaban</h6>
<h3><?= $last['kelembaban_udara'] ?> %</h3>
<div><?= trendInfo($last['kelembaban_udara'],$prev['kelembaban_udara']) ?></div>
</div>
</div>

<div class="col-md-3">
<div class="p-3 bg-dark rounded">
<h6>Kadar Air Tanah</h6>
<h3><?= $last['kadar_air_tanah'] ?> %</h3>
<div><?= trendInfo($last['kadar_air_tanah'],$prev['kadar_air_tanah']) ?></div>
</div>
</div>

<div class="col-md-3">
<div class="p-3 bg-dark rounded">
<h6>Kecepatan Angin</h6>
<h3><?= $last['kecepatan_angin'] ?> m/s</h3>
<div><?= trendInfo($last['kecepatan_angin'],$prev['kecepatan_angin']) ?> Trend</div>
</div>
</div>

</div>
</div>


<?php
// include "koneksi.php";
$page = isset($_GET['page']) ? $_GET['page'] : 'beranda';
$supabaseUrl = "https://fykjfyieanedfjrctrdi.supabase.co/rest/v1/data_sensor";
$supabaseKey = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImZ5a2pmeWllYW5lZGZqcmN0cmRpIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzIzMDYxMDksImV4cCI6MjA4Nzg4MjEwOX0.-_HYcQXNrj065l7GO-uRkTviTPv0caYaqNZsfl1aDfQ";

?>

<?php endif; ?>

<?php
/* ======================================================
   HALAMAN LAPORAN
====================================================== */
elseif($page=='laporan'):  

$result = getSupabaseData(
    $supabaseUrl."?select=*&order=created_at.desc&limit=20",
    $supabaseKey
);

if(empty($result)):
?>

<div class="no-data-box">
    <h4>⚠ Tidak Ada Data Laporan</h4>
</div>

<script src="https://cdn.jsdelivr.net/npm/jspdf/dist/jspdf.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jspdf-autotable"></script>

<script>
async function exportPDF(){

    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    doc.text("Laporan Monitoring Agroklimat", 14, 15);

    doc.autoTable({
        html: '.laporan-table',
        startY: 20
    });

    doc.save("laporan_monitoring.pdf");
}
</script>

<?php else:
?>
<div class="card-custom laporan-card">
    <h5 class="mb-4">
        <i class="bi bi-clipboard-data"></i> Laporan Data Pengamatan
    </h5>

<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">

    <div class="date-range-box">

        <label>📅 From</label>
        <input type="date" id="dateFrom" class="date-input">

        <label>To</label>
        <input type="date" id="dateTo" class="date-input">

        <button class="btn btn-sm btn-outline-light" onclick="applyDateFilter()">
            Terapkan
        </button>

        <button class="btn btn-sm btn-outline-light" onclick="resetFilter()">
            Reset
        </button>

    </div>

    <div class="quick-filter-box">

        <button class="quick-btn" onclick="filterToday()">Hari Ini</button>
        <button class="quick-btn" onclick="filter7Days()">7 Hari</button>

    </div>

    <div>
        <button class="btn btn-outline-light btn-sm" onclick="exportExcel()">Excel</button>
        <button class="btn btn-outline-light btn-sm" onclick="exportPDF()">PDF</button>
    </div>

</div>
<script>
const searchInput = document.getElementById("searchInput");
if(searchInput)
    searchInput.addEventListener("keyup", function(){
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll(".laporan-table tbody tr");

    rows.forEach(row => {
        row.style.display =
            row.innerText.toLowerCase().includes(filter)
            ? "" : "none";
    });

});
</script>

    <div class="table-responsive">
        <table class="table table-borderless align-middle laporan-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>🌡 Suhu</th>
                    <th>💧 Kelembaban</th>
                    <th>🌱 Tanah</th>
                    <th>🌬 Angin</th>
                    <th>🕒 Waktu</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($result as $row): ?>
                <tr>
                    <td><span class="id-badge">#<?= $row['id'] ?></span></td>
                    <td><span class="badge bg-danger-subtle text-danger"><?= $row['suhu_udara'] ?> °C</span></td>
                    <td><span class="badge bg-success-subtle text-success"><?= $row['kelembaban_udara'] ?> %</span></td>
                    <td><span class="badge bg-primary-subtle text-primary"><?= $row['kadar_air_tanah'] ?> %</span></td>
                    <td><span class="badge bg-warning-subtle text-warning"><?= $row['kecepatan_angin'] ?> m/s</span></td>
                    <td class="time-text">
                        <?= date('d M Y H:i:s', strtotime($row['created_at'])) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>

// Ambil semua tanggal unik dari tabel
function populateDateFilter(){

    let rows = document.querySelectorAll(".laporan-table tbody tr");
    let dateSet = new Set();

    rows.forEach(row => {

        let dateCell = row.cells[5].innerText; 
        let dateOnly = dateCell.split(',')[0]; // ambil tanggal saja

        dateSet.add(dateOnly);
    });

    let dropdown = document.getElementById("dateFilter");

    dateSet.forEach(date => {
        let option = document.createElement("option");
        option.value = date;
        option.textContent = date;
        dropdown.appendChild(option);
    });

}



document.getElementById("datePicker").addEventListener("change", function(){

    let selectedDate = this.value; // format YYYY-MM-DD
    let rows = document.querySelectorAll(".laporan-table tbody tr");

    rows.forEach(row => {

        let rawDate = row.cells[5].innerText;
        let rowDate = new Date(rawDate).toISOString().split('T')[0];

        if(rowDate === selectedDate){
            row.style.display = "";
        } else {
            row.style.display = "none";
        }

    });

});

function resetFilter(){

    document.getElementById("datePicker").value = "";

    let rows = document.querySelectorAll(".laporan-table tbody tr");
    rows.forEach(row => row.style.display = "");

}

populateDateFilter();
</script>
// Filter berdasarkan tanggal
<div class="d-flex justify-content-between align-items-center mb-3">

    <div class="date-filter-box">
        <label class="me-2">📅 Pilih Tanggal:</label>
        <input type="date" id="datePicker" class="date-input">
        <button class="btn btn-sm btn-outline-light ms-2" onclick="resetFilter()">Reset</button>
    </div>

    <div>
        <button class="btn btn-outline-light btn-sm" onclick="exportExcel()">Excel</button>
        <button class="btn btn-outline-light btn-sm" onclick="exportPDF()">PDF</button>
    </div>

</div>

<?php endif; ?>
<?php endif; ?>
</div>
</div>
</div>

<script>
window.addEventListener("load", function(){
    const loader = document.getElementById("loadingScreen");
    setTimeout(()=>{
        loader.classList.add("fade-out");
    }, 800);
});
</script>


<script>

// ===== EXPORT EXCEL =====
function exportExcel(){

    let table = document.querySelector(".laporan-table");
    if(!table){
        alert("Tabel tidak ditemukan");
        return;
    }

    let wb = XLSX.utils.table_to_book(table, {sheet:"History"});
    XLSX.writeFile(wb, "history_monitoring.xlsx");

}


// ===== EXPORT PDF =====
async function exportPDF(){

    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    doc.text("History Monitoring Agroklimat", 14, 15);

    doc.autoTable({
        html: '.laporan-table',
        startY: 20,
        theme: 'grid'
    });

    doc.save("history_monitoring.pdf");

}

</script>



<script>

function getRowDate(row){
    let raw = row.cells[5].innerText;
    return new Date(raw);
}

function applyDateFilter(){

    let from = document.getElementById("dateFrom").value;
    let to = document.getElementById("dateTo").value;

    if(!from || !to) return;

    let fromDate = new Date(from);
    let toDate = new Date(to);
    toDate.setHours(23,59,59,999);

    let rows = document.querySelectorAll(".laporan-table tbody tr");

    rows.forEach(row => {

        let rowDate = getRowDate(row);

        if(rowDate >= fromDate && rowDate <= toDate){
            row.style.display = "";
        } else {
            row.style.display = "none";
        }

    });

    activateGlow();
}

function filterToday(){

    let today = new Date();
    today.setHours(0,0,0,0);

    let rows = document.querySelectorAll(".laporan-table tbody tr");

    rows.forEach(row => {
        let rowDate = getRowDate(row);
        row.style.display =
            rowDate >= today ? "" : "none";
    });

    activateGlow();
}

function filter7Days(){

    let today = new Date();
    let past = new Date();
    past.setDate(today.getDate() - 7);

    rows = document.querySelectorAll(".laporan-table tbody tr");

    rows.forEach(row => {
        let rowDate = getRowDate(row);
        row.style.display =
            rowDate >= past ? "" : "none";
    });

    activateGlow();
}

function resetFilter(){

    document.getElementById("dateFrom").value = "";
    document.getElementById("dateTo").value = "";

    let rows = document.querySelectorAll(".laporan-table tbody tr");
    rows.forEach(row => row.style.display = "");

    document.querySelector(".laporan-card").classList.remove("filter-active");
}

function activateGlow(){
    document.querySelector(".laporan-card").classList.add("filter-active");
}

</script>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
document.addEventListener("DOMContentLoaded", function(){

    if(!document.querySelector("#gaugeSuhu")) return;

    const suhu = <?= $latest['suhu_udara'] ?? 0 ?>;
    const kelembaban = <?= $latest['kelembaban_udara'] ?? 0 ?>;
    const tanah = <?= $latest['kadar_air_tanah'] ?? 0 ?>;
    const angin = <?= $latest['kecepatan_angin'] ?? 0 ?>;

    function renderGauge(id, value, unit, color){

        var options = {
            series: [value],
            chart: {
                height: 230,
                type: 'radialBar'
            },
            plotOptions: {
                radialBar: {
                    hollow: { size: '70%' },
                    dataLabels: {
                        name: { show: false },
                        value: {
                            fontSize: '22px',
                            color: '#fff',
                            formatter: function(val){
                                return val + " " + unit;
                            }
                        }
                    }
                }
            },
            colors: [color],
            stroke: { lineCap: 'round' }
        };

        var chart = new ApexCharts(document.querySelector(id), options);
        chart.render();
    }

    renderGauge("#gaugeSuhu", suhu, "°C", "#22c55e");
    renderGauge("#gaugeKelembaban", kelembaban, "%", "#3b82f6");
    renderGauge("#gaugeTanah", tanah, "%", "#facc15");
    renderGauge("#gaugeAngin", angin, "m/s", "#ef4444");

});


</script>


<div class="ai-grid"></div> 

<script src="https://cdn.jsdelivr.net/npm/tsparticles@2/tsparticles.bundle.min.js"></script>
    <div id="tsparticles"></div>

    
</body>
</html>
