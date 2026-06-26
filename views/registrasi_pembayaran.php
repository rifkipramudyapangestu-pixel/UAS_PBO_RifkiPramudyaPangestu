<?php
// =========================================================
//  View   : registrasi_pembayaran.php
//  Tahap  : 6 — Sistem Registrasi Pembayaran Kuliah
//  Nama   : Rifki Pramudya Pangestu | TRPL 1A
// =========================================================

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Mahasiswa.php';
require_once __DIR__ . '/../models/MahasiswaMandiri.php';
require_once __DIR__ . '/../models/MahasiswaBidikmisi.php';
require_once __DIR__ . '/../models/MahasiswaPrestasi.php';

// ─── Helper: format rupiah ────────────────────────────────
function fmt(float $n): string {
    return 'Rp ' . number_format($n, 0, ',', '.');
}

// ─── Ambil data dari database ─────────────────────────────
$error        = null;
$mandiriList  = [];
$bidikmisiList= [];
$prestasiList = [];
$totalAll     = 0;

try {
    $pdo = Database::getConnection();

    // Mandiri
    $st = $pdo->query("SELECT * FROM tabel_mahasiswa WHERE jenis_pembayaran='Mandiri' ORDER BY id_mahasiswa");
    foreach ($st->fetchAll() as $r) {
        $mandiriList[] = new MahasiswaMandiri(
            (int)$r['id_mahasiswa'], $r['nama_mahasiswa'], $r['nim'],
            (int)$r['semester'], (float)$r['tarif_ukt_nominal'],
            $r['golongan_ukt'], $r['nama_wali']
        );
    }

    // Bidikmisi
    $st = $pdo->query("SELECT * FROM tabel_mahasiswa WHERE jenis_pembayaran='Bidikmisi' ORDER BY id_mahasiswa");
    foreach ($st->fetchAll() as $r) {
        $bidikmisiList[] = new MahasiswaBidikmisi(
            (int)$r['id_mahasiswa'], $r['nama_mahasiswa'], $r['nim'],
            (int)$r['semester'], (float)$r['tarif_ukt_nominal'],
            $r['nomor_kip_kuliah'], (float)$r['dana_saku_subsidi']
        );
    }

    // Prestasi
    $st = $pdo->query("SELECT * FROM tabel_mahasiswa WHERE jenis_pembayaran='Prestasi' ORDER BY id_mahasiswa");
    foreach ($st->fetchAll() as $r) {
        $prestasiList[] = new MahasiswaPrestasi(
            (int)$r['id_mahasiswa'], $r['nama_mahasiswa'], $r['nim'],
            (int)$r['semester'], (float)$r['tarif_ukt_nominal'],
            $r['nama_instansi_beasiswa'], (float)$r['minimal_ipk_syarat']
        );
    }

    $totalAll = count($mandiriList) + count($bidikmisiList) + count($prestasiList);

} catch (PDOException $e) {
    $error = $e->getMessage();
}

// Tentukan tab aktif dari query string
$activeTab = $_GET['tab'] ?? 'mandiri';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registrasi Pembayaran Kuliah | UAS PBO</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />

    <style>
        /* ── Root Variables ── */
        :root {
            --sidebar-w    : 265px;
            --primary      : #4361ee;
            --primary-dark : #3a0ca3;
            --accent-green : #2ec4b6;
            --accent-orange: #ff9f1c;
            --accent-pink  : #e63946;
            --bg-page      : #f0f4ff;
            --bg-card      : #ffffff;
            --text-main    : #1a1a2e;
            --text-muted   : #6c7293;
            --sidebar-bg   : linear-gradient(160deg, #3a0ca3 0%, #4361ee 60%, #4cc9f0 100%);
            --radius       : 14px;
            --shadow       : 0 4px 24px rgba(67,97,238,.13);
            --transition   : .25s ease;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family     : 'Poppins', sans-serif;
            background      : var(--bg-page);
            color           : var(--text-main);
            min-height      : 100vh;
            display         : flex;
        }

        /* ══════════════════════════════
           SIDEBAR
        ══════════════════════════════ */
        .sidebar {
            width      : var(--sidebar-w);
            min-height : 100vh;
            background : var(--sidebar-bg);
            position   : fixed;
            top        : 0; left: 0;
            display    : flex;
            flex-direction: column;
            z-index    : 1000;
            box-shadow : 4px 0 24px rgba(58,12,163,.18);
            transition : var(--transition);
        }

        .sidebar-brand {
            padding    : 28px 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,.15);
        }
        .sidebar-brand .brand-icon {
            width: 46px; height: 46px;
            background: rgba(255,255,255,.2);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px; color: #fff;
            margin-bottom: 10px;
            backdrop-filter: blur(6px);
        }
        .sidebar-brand h5 {
            color: #fff; font-weight: 700; font-size: .95rem; line-height: 1.3;
            margin: 0;
        }
        .sidebar-brand span {
            color: rgba(255,255,255,.65); font-size: .72rem; font-weight: 400;
        }

        .sidebar-section-label {
            padding: 18px 24px 6px;
            color: rgba(255,255,255,.5);
            font-size: .68rem;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .sidebar-nav { padding: 8px 16px; flex: 1; }
        .sidebar-nav .nav-item { margin-bottom: 4px; }

        .sidebar-nav .nav-link {
            display       : flex;
            align-items   : center;
            gap           : 12px;
            padding       : 11px 14px;
            border-radius : 10px;
            color         : rgba(255,255,255,.75);
            font-size     : .85rem;
            font-weight   : 500;
            transition    : var(--transition);
            text-decoration: none;
        }
        .sidebar-nav .nav-link i {
            font-size: 1.05rem;
            width: 22px; text-align: center;
        }
        .sidebar-nav .nav-link:hover {
            background: rgba(255,255,255,.15);
            color: #fff;
        }
        .sidebar-nav .nav-link.active {
            background: rgba(255,255,255,.22);
            color: #fff;
            font-weight: 600;
            box-shadow: 0 2px 12px rgba(0,0,0,.12);
        }
        .sidebar-nav .nav-link .badge-pill {
            margin-left: auto;
            background: rgba(255,255,255,.25);
            color: #fff;
            font-size: .68rem;
            padding: 2px 9px;
            border-radius: 20px;
        }

        .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid rgba(255,255,255,.12);
        }
        .sidebar-footer .user-card {
            display: flex; align-items: center; gap: 10px;
        }
        .sidebar-footer .avatar {
            width: 36px; height: 36px;
            background: rgba(255,255,255,.25);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: .9rem; font-weight: 700;
        }
        .sidebar-footer .user-info small {
            display: block; color: rgba(255,255,255,.55); font-size: .68rem;
        }
        .sidebar-footer .user-info strong {
            color: #fff; font-size: .78rem;
        }

        /* ══════════════════════════════
           MAIN CONTENT
        ══════════════════════════════ */
        .main-content {
            margin-left : var(--sidebar-w);
            flex        : 1;
            padding     : 0;
            min-height  : 100vh;
            display     : flex;
            flex-direction: column;
        }

        /* ── Topbar ── */
        .topbar {
            background    : #fff;
            padding       : 16px 32px;
            display       : flex;
            align-items   : center;
            justify-content: space-between;
            border-bottom : 1px solid #e8eaf0;
            box-shadow    : 0 2px 12px rgba(67,97,238,.06);
            position      : sticky; top: 0; z-index: 900;
        }
        .topbar .page-title { font-size: 1.05rem; font-weight: 700; color: var(--text-main); }
        .topbar .page-title span { color: var(--primary); }
        .topbar .breadcrumb  { font-size: .75rem; color: var(--text-muted); margin: 0; }
        .topbar-right { display: flex; align-items: center; gap: 14px; }
        .topbar-right .btn-icon {
            width: 38px; height: 38px;
            border-radius: 10px;
            border: 1px solid #e8eaf0;
            background: #fff;
            display: flex; align-items: center; justify-content: center;
            color: var(--text-muted); font-size: 1rem;
            cursor: pointer; transition: var(--transition);
            text-decoration: none;
        }
        .topbar-right .btn-icon:hover { background: var(--primary); color: #fff; border-color: var(--primary); }

        /* ── Page Body ── */
        .page-body { padding: 28px 32px; flex: 1; }

        /* ── Section Header ── */
        .section-header { margin-bottom: 24px; }
        .section-header h4 { font-size: 1.2rem; font-weight: 700; }
        .section-header p  { color: var(--text-muted); font-size: .82rem; margin: 0; }

        /* ── Stat Cards ── */
        .stat-cards { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 28px; }

        .stat-card {
            background   : var(--bg-card);
            border-radius: var(--radius);
            padding      : 22px 20px;
            box-shadow   : var(--shadow);
            display      : flex;
            align-items  : center;
            gap          : 16px;
            transition   : var(--transition);
            border       : 1px solid transparent;
        }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 32px rgba(67,97,238,.18); }

        .stat-card .stat-icon {
            width: 52px; height: 52px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem; flex-shrink: 0;
        }
        .stat-card .stat-text .stat-val { font-size: 1.6rem; font-weight: 700; line-height: 1; }
        .stat-card .stat-text .stat-lbl { font-size: .75rem; color: var(--text-muted); margin-top: 4px; }
        .stat-card .stat-text .stat-sub { font-size: .7rem; color: var(--text-muted); margin-top: 2px; }

        .stat-blue   .stat-icon { background: #eef0ff; color: var(--primary); }
        .stat-green  .stat-icon { background: #e0faf7; color: var(--accent-green); }
        .stat-orange .stat-icon { background: #fff4e0; color: var(--accent-orange); }
        .stat-red    .stat-icon { background: #fff0f1; color: var(--accent-pink); }

        /* ── Tab Panel ── */
        .tab-panel-wrapper {
            background   : #fff;
            border-radius: var(--radius);
            box-shadow   : var(--shadow);
            overflow     : hidden;
        }

        .custom-tabs {
            display        : flex;
            border-bottom  : 2px solid #f0f4ff;
            padding        : 0 20px;
            background     : #fafbff;
        }
        .custom-tab {
            display       : flex;
            align-items   : center;
            gap           : 8px;
            padding       : 16px 20px;
            font-size     : .85rem;
            font-weight   : 500;
            color         : var(--text-muted);
            cursor        : pointer;
            border-bottom : 3px solid transparent;
            margin-bottom : -2px;
            transition    : var(--transition);
            text-decoration: none;
            white-space   : nowrap;
        }
        .custom-tab .tab-dot {
            width: 10px; height: 10px; border-radius: 50%;
        }
        .custom-tab .tab-count {
            background: #eef0ff; color: var(--primary);
            font-size: .7rem; font-weight: 600;
            padding: 1px 8px; border-radius: 20px;
        }
        .custom-tab:hover { color: var(--primary); background: rgba(67,97,238,.04); }
        .custom-tab.tab-mandiri.active  { color: var(--primary);       border-color: var(--primary);       }
        .custom-tab.tab-bidikmisi.active{ color: var(--accent-green);  border-color: var(--accent-green);  }
        .custom-tab.tab-prestasi.active { color: var(--accent-orange); border-color: var(--accent-orange); }

        .custom-tab.tab-mandiri  .tab-dot { background: var(--primary); }
        .custom-tab.tab-bidikmisi.tab-dot,
        .custom-tab.tab-bidikmisi .tab-dot { background: var(--accent-green); }
        .custom-tab.tab-prestasi .tab-dot  { background: var(--accent-orange); }

        .tab-content-area { padding: 24px; }
        .tab-pane-custom  { display: none; }
        .tab-pane-custom.active { display: block; }

        /* ── Table ── */
        .table-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 16px;
        }
        .table-header h6 { font-weight: 700; font-size: .9rem; margin: 0; }
        .table-header .badge-type {
            font-size: .72rem; padding: 5px 14px; border-radius: 20px; font-weight: 600;
        }

        .modern-table { width: 100%; border-collapse: collapse; font-size: .83rem; }
        .modern-table thead th {
            padding: 12px 16px;
            font-size: .72rem; font-weight: 600;
            letter-spacing: .8px; text-transform: uppercase;
            color: var(--text-muted);
            background: #f8faff;
            border-bottom: 2px solid #eef0ff;
            white-space: nowrap;
        }
        .modern-table tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid #f0f4ff;
            vertical-align: middle;
        }
        .modern-table tbody tr:last-child td { border-bottom: none; }
        .modern-table tbody tr:hover td { background: #fafbff; }

        .avatar-cell {
            width: 36px; height: 36px;
            border-radius: 10px;
            display: inline-flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: .8rem; color: #fff;
            flex-shrink: 0;
        }
        .cell-name { font-weight: 600; color: var(--text-main); }
        .cell-sub  { font-size: .72rem; color: var(--text-muted); }

        .badge-sem {
            background: #f0f4ff; color: var(--primary);
            font-size: .7rem; padding: 3px 10px; border-radius: 20px; font-weight: 600;
        }
        .badge-golongan {
            background: #fff4e0; color: var(--accent-orange);
            font-size: .7rem; padding: 3px 10px; border-radius: 20px; font-weight: 600;
        }
        .badge-kip {
            background: #e0faf7; color: var(--accent-green);
            font-size: .7rem; padding: 3px 10px; border-radius: 20px; font-weight: 600;
        }
        .badge-instansi {
            background: #fff0f1; color: var(--accent-pink);
            font-size: .7rem; padding: 3px 10px; border-radius: 20px; font-weight: 600;
        }
        .tagihan-val { font-weight: 700; }
        .tagihan-gratis { color: var(--accent-green); font-weight: 700; }

        /* ── Error Alert ── */
        .alert-db {
            background: #fff0f1; border: 1px solid #fcc; border-radius: 10px;
            padding: 16px 20px; color: var(--accent-pink);
            display: flex; align-items: center; gap: 10px;
            font-size: .85rem;
        }

        /* ── Empty State ── */
        .empty-state {
            text-align: center; padding: 40px 20px; color: var(--text-muted);
        }
        .empty-state i { font-size: 2.5rem; opacity: .4; margin-bottom: 10px; }
        .empty-state p  { font-size: .85rem; }

        /* ── Avatar Colors ── */
        .av-blue   { background: linear-gradient(135deg, #4361ee, #4cc9f0); }
        .av-green  { background: linear-gradient(135deg, #2ec4b6, #80ed99); }
        .av-orange { background: linear-gradient(135deg, #ff9f1c, #ffbf69); }
        .av-purple { background: linear-gradient(135deg, #9b5de5, #c77dff); }
        .av-pink   { background: linear-gradient(135deg, #e63946, #ff6b6b); }

        /* ── Footer ── */
        .page-footer {
            padding: 16px 32px;
            border-top: 1px solid #e8eaf0;
            text-align: center;
            font-size: .75rem;
            color: var(--text-muted);
            background: #fff;
        }

        /* ── Responsive ── */
        @media (max-width: 992px) {
            :root { --sidebar-w: 220px; }
            .stat-cards { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 768px) {
            .sidebar   { transform: translateX(-100%); }
            .main-content { margin-left: 0; }
            .page-body { padding: 20px 16px; }
            .stat-cards { grid-template-columns: 1fr 1fr; gap: 12px; }
            .topbar { padding: 14px 16px; }
        }
    </style>
</head>
<body>

<!-- ══════════════════════════════════════
     SIDEBAR
══════════════════════════════════════ -->
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon"><i class="bi bi-mortarboard-fill"></i></div>
        <h5>Sistem Registrasi<br>Pembayaran Kuliah</h5>
        <span>UAS Pemrograman Berorientasi Objek</span>
    </div>

    <div class="sidebar-section-label">Main Menu</div>
    <nav class="sidebar-nav">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="registrasi_pembayaran.php">
                    <i class="bi bi-grid-fill"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="registrasi_pembayaran.php?tab=mandiri">
                    <i class="bi bi-person-fill"></i> Mahasiswa Mandiri
                    <span class="badge-pill"><?= count($mandiriList) ?></span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="registrasi_pembayaran.php?tab=bidikmisi">
                    <i class="bi bi-award-fill"></i> Mahasiswa Bidikmisi
                    <span class="badge-pill"><?= count($bidikmisiList) ?></span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="registrasi_pembayaran.php?tab=prestasi">
                    <i class="bi bi-star-fill"></i> Mahasiswa Prestasi
                    <span class="badge-pill"><?= count($prestasiList) ?></span>
                </a>
            </li>
        </ul>
    </nav>

    <div class="sidebar-section-label">Sistem</div>
    <nav class="sidebar-nav" style="flex:0">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="../database/db_uas_pbo_trpl1a_rifkipramudyapangestu.sql" download>
                    <i class="bi bi-database-fill-down"></i> Export Database
                </a>
            </li>
        </ul>
    </nav>

    <div class="sidebar-footer">
        <div class="user-card">
            <div class="avatar">RP</div>
            <div class="user-info">
                <strong>Rifki P. Pangestu</strong>
                <small>TRPL 1A &bull; 2401001</small>
            </div>
        </div>
    </div>
</aside>

<!-- ══════════════════════════════════════
     MAIN CONTENT
══════════════════════════════════════ -->
<div class="main-content">

    <!-- Topbar -->
    <header class="topbar">
        <div>
            <div class="page-title">Registrasi <span>Pembayaran Kuliah</span></div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item active">Registrasi Pembayaran</li>
                </ol>
            </nav>
        </div>
        <div class="topbar-right">
            <a href="registrasi_pembayaran.php" class="btn-icon" title="Refresh Data">
                <i class="bi bi-arrow-clockwise"></i>
            </a>
            <a href="../index.php" class="btn-icon" title="Kembali ke Index">
                <i class="bi bi-house-fill"></i>
            </a>
        </div>
    </header>

    <!-- Page Body -->
    <main class="page-body">

        <!-- Error Alert -->
        <?php if ($error): ?>
        <div class="alert-db mb-4">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            <div><strong>Koneksi Database Gagal</strong><br><?= htmlspecialchars($error) ?></div>
        </div>
        <?php endif; ?>

        <!-- Section Header -->
        <div class="section-header">
            <h4>📋 Data Mahasiswa Terdaftar</h4>
            <p>Menampilkan seluruh data registrasi pembayaran kuliah mahasiswa berdasarkan jenis pembiayaan.</p>
        </div>

        <!-- ─── Stat Cards ─── -->
        <div class="stat-cards">
            <div class="stat-card stat-blue">
                <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
                <div class="stat-text">
                    <div class="stat-val"><?= $totalAll ?></div>
                    <div class="stat-lbl">Total Mahasiswa</div>
                    <div class="stat-sub">Semua kategori</div>
                </div>
            </div>
            <div class="stat-card stat-orange">
                <div class="stat-icon"><i class="bi bi-person-fill"></i></div>
                <div class="stat-text">
                    <div class="stat-val"><?= count($mandiriList) ?></div>
                    <div class="stat-lbl">Mahasiswa Mandiri</div>
                    <div class="stat-sub">Pembayaran penuh</div>
                </div>
            </div>
            <div class="stat-card stat-green">
                <div class="stat-icon"><i class="bi bi-award-fill"></i></div>
                <div class="stat-text">
                    <div class="stat-val"><?= count($bidikmisiList) ?></div>
                    <div class="stat-lbl">Mahasiswa Bidikmisi</div>
                    <div class="stat-sub">Gratis UKT + Dana Saku</div>
                </div>
            </div>
            <div class="stat-card stat-red">
                <div class="stat-icon"><i class="bi bi-star-fill"></i></div>
                <div class="stat-text">
                    <div class="stat-val"><?= count($prestasiList) ?></div>
                    <div class="stat-lbl">Mahasiswa Prestasi</div>
                    <div class="stat-sub">Diskon 75% UKT</div>
                </div>
            </div>
        </div>

        <!-- ─── Tab Panel ─── -->
        <div class="tab-panel-wrapper">

            <!-- Tab Navigation -->
            <div class="custom-tabs">
                <a href="#" class="custom-tab tab-mandiri <?= $activeTab==='mandiri'?'active':'' ?>"
                   data-tab="mandiri" onclick="switchTab('mandiri');return false;">
                    <span class="tab-dot"></span>
                    Mahasiswa Mandiri
                    <span class="tab-count"><?= count($mandiriList) ?></span>
                </a>
                <a href="#" class="custom-tab tab-bidikmisi <?= $activeTab==='bidikmisi'?'active':'' ?>"
                   data-tab="bidikmisi" onclick="switchTab('bidikmisi');return false;">
                    <span class="tab-dot"></span>
                    Mahasiswa Bidikmisi
                    <span class="tab-count"><?= count($bidikmisiList) ?></span>
                </a>
                <a href="#" class="custom-tab tab-prestasi <?= $activeTab==='prestasi'?'active':'' ?>"
                   data-tab="prestasi" onclick="switchTab('prestasi');return false;">
                    <span class="tab-dot"></span>
                    Mahasiswa Prestasi
                    <span class="tab-count"><?= count($prestasiList) ?></span>
                </a>
            </div>

            <div class="tab-content-area">

                <!-- ── TAB: MANDIRI ── -->
                <div id="tab-mandiri" class="tab-pane-custom <?= $activeTab==='mandiri'?'active':'' ?>">
                    <div class="table-header">
                        <div>
                            <h6><i class="bi bi-person-fill text-primary me-2"></i>Daftar Mahasiswa Mandiri</h6>
                            <small class="text-muted">Tagihan = Tarif UKT Nominal + Rp 100.000</small>
                        </div>
                        <span class="badge badge-type" style="background:#eef0ff;color:#4361ee;">
                            <?= count($mandiriList) ?> Mahasiswa
                        </span>
                    </div>
                    <?php if (empty($mandiriList)): ?>
                        <div class="empty-state"><i class="bi bi-inbox-fill d-block"></i><p>Tidak ada data.</p></div>
                    <?php else: ?>
                    <div class="table-responsive">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Mahasiswa</th>
                                <th>NIM</th>
                                <th>Semester</th>
                                <th>Golongan UKT</th>
                                <th>Nama Wali</th>
                                <th>Tarif UKT</th>
                                <th>Tagihan Semester</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $colors = ['av-blue','av-purple','av-orange','av-green','av-pink'];
                        foreach ($mandiriList as $i => $m): ?>
                        <tr>
                            <td><small class="text-muted"><?= $m->getIdMahasiswa() ?></small></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-cell <?= $colors[$i % count($colors)] ?>">
                                        <?= strtoupper(substr($m->getNamaMahasiswa(), 0, 1)) ?>
                                    </div>
                                    <div>
                                        <div class="cell-name"><?= htmlspecialchars($m->getNamaMahasiswa()) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="cell-sub"><?= $m->getNim() ?></span></td>
                            <td><span class="badge-sem">Semester <?= $m->getSemester() ?></span></td>
                            <td><span class="badge-golongan"><?= htmlspecialchars($m->getGolonganUkt()) ?></span></td>
                            <td><?= htmlspecialchars($m->getNamaWali()) ?></td>
                            <td><?= fmt($m->getTarifUktNominal()) ?></td>
                            <td><span class="tagihan-val" style="color:var(--primary)"><?= fmt($m->hitungTagihanSemester()) ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- ── TAB: BIDIKMISI ── -->
                <div id="tab-bidikmisi" class="tab-pane-custom <?= $activeTab==='bidikmisi'?'active':'' ?>">
                    <div class="table-header">
                        <div>
                            <h6><i class="bi bi-award-fill me-2" style="color:var(--accent-green)"></i>Daftar Mahasiswa Bidikmisi</h6>
                            <small class="text-muted">Tagihan UKT = Rp 0 (Gratis) | Mendapatkan Dana Saku Subsidi</small>
                        </div>
                        <span class="badge badge-type" style="background:#e0faf7;color:#2ec4b6;">
                            <?= count($bidikmisiList) ?> Mahasiswa
                        </span>
                    </div>
                    <?php if (empty($bidikmisiList)): ?>
                        <div class="empty-state"><i class="bi bi-inbox-fill d-block"></i><p>Tidak ada data.</p></div>
                    <?php else: ?>
                    <div class="table-responsive">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Mahasiswa</th>
                                <th>NIM</th>
                                <th>Semester</th>
                                <th>Nomor KIP Kuliah</th>
                                <th>Dana Saku Subsidi</th>
                                <th>Tagihan Semester</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($bidikmisiList as $i => $m): ?>
                        <tr>
                            <td><small class="text-muted"><?= $m->getIdMahasiswa() ?></small></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-cell <?= $colors[$i % count($colors)] ?>">
                                        <?= strtoupper(substr($m->getNamaMahasiswa(), 0, 1)) ?>
                                    </div>
                                    <div>
                                        <div class="cell-name"><?= htmlspecialchars($m->getNamaMahasiswa()) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="cell-sub"><?= $m->getNim() ?></span></td>
                            <td><span class="badge-sem">Semester <?= $m->getSemester() ?></span></td>
                            <td><span class="badge-kip"><?= htmlspecialchars($m->getNomorKipKuliah()) ?></span></td>
                            <td style="color:var(--accent-green);font-weight:600"><?= fmt($m->getDanaSakuSubsidi()) ?>/bln</td>
                            <td><span class="tagihan-gratis"><i class="bi bi-check-circle-fill me-1"></i>GRATIS</span></td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- ── TAB: PRESTASI ── -->
                <div id="tab-prestasi" class="tab-pane-custom <?= $activeTab==='prestasi'?'active':'' ?>">
                    <div class="table-header">
                        <div>
                            <h6><i class="bi bi-star-fill me-2" style="color:var(--accent-orange)"></i>Daftar Mahasiswa Prestasi</h6>
                            <small class="text-muted">Tagihan = 25% dari Tarif UKT Nominal (Diskon 75%)</small>
                        </div>
                        <span class="badge badge-type" style="background:#fff4e0;color:#ff9f1c;">
                            <?= count($prestasiList) ?> Mahasiswa
                        </span>
                    </div>
                    <?php if (empty($prestasiList)): ?>
                        <div class="empty-state"><i class="bi bi-inbox-fill d-block"></i><p>Tidak ada data.</p></div>
                    <?php else: ?>
                    <div class="table-responsive">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Mahasiswa</th>
                                <th>NIM</th>
                                <th>Semester</th>
                                <th>Instansi Beasiswa</th>
                                <th>Min. IPK Syarat</th>
                                <th>Tarif UKT</th>
                                <th>Tagihan Semester</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($prestasiList as $i => $m): ?>
                        <tr>
                            <td><small class="text-muted"><?= $m->getIdMahasiswa() ?></small></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-cell <?= $colors[$i % count($colors)] ?>">
                                        <?= strtoupper(substr($m->getNamaMahasiswa(), 0, 1)) ?>
                                    </div>
                                    <div>
                                        <div class="cell-name"><?= htmlspecialchars($m->getNamaMahasiswa()) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="cell-sub"><?= $m->getNim() ?></span></td>
                            <td><span class="badge-sem">Semester <?= $m->getSemester() ?></span></td>
                            <td><span class="badge-instansi"><?= htmlspecialchars($m->getNamaInstansiBeasiswa()) ?></span></td>
                            <td>
                                <span style="background:#fff0f1;color:var(--accent-pink);font-weight:700;padding:3px 10px;border-radius:20px;font-size:.72rem;">
                                    IPK ≥ <?= number_format($m->getMinimalIpkSyarat(), 2) ?>
                                </span>
                            </td>
                            <td><?= fmt($m->getTarifUktNominal()) ?></td>
                            <td><span class="tagihan-val" style="color:var(--accent-orange)"><?= fmt($m->hitungTagihanSemester()) ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    </div>
                    <?php endif; ?>
                </div>

            </div><!-- /tab-content-area -->
        </div><!-- /tab-panel-wrapper -->

    </main>

    <footer class="page-footer">
        &copy; <?= date('Y') ?> &nbsp;|&nbsp; <strong>Rifki Pramudya Pangestu</strong> &nbsp;|&nbsp;
        UAS Pemrograman Berorientasi Objek &nbsp;|&nbsp; TRPL 1A
    </footer>
</div><!-- /main-content -->

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // ─── Tab Switcher ─────────────────────────────────────
    function switchTab(name) {
        // Sembunyikan semua pane
        document.querySelectorAll('.tab-pane-custom').forEach(p => p.classList.remove('active'));
        // Non-aktifkan semua tab
        document.querySelectorAll('.custom-tab').forEach(t => t.classList.remove('active'));

        // Aktifkan pane & tab yang dipilih
        document.getElementById('tab-' + name).classList.add('active');
        document.querySelector('.custom-tab[data-tab="' + name + '"]').classList.add('active');
    }

    // Set tab awal sesuai URL param (jika ada)
    const urlParams = new URLSearchParams(window.location.search);
    const tabParam  = urlParams.get('tab');
    if (tabParam && ['mandiri','bidikmisi','prestasi'].includes(tabParam)) {
        switchTab(tabParam);
    }
</script>

</body>
</html>
