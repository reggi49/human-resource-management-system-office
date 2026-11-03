<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard | <?php echo isset($settingsvalue->sitetitle) ? $settingsvalue->sitetitle : 'HRMS Enterprise'; ?></title>
    
    <!-- Google Fonts: Outfit & Plus Jakarta Sans (Modern Executive Standard) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome & Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <style>
        /* ============================================
           DESIGN SYSTEM — EXECUTIVE GLASSMORPHISM & MATERIALS
           ============================================ */
        :root {
            --sidebar-w: 260px;
            --sidebar-collapsed: 72px;
            --header-h: 60px;

            /* Executive Dark Obsidian Sidebar */
            --sb-bg: #0B0F19;
            --sb-bg-hover: rgba(255,255,255,0.05);
            --sb-bg-active: linear-gradient(135deg, #4F46E5 0%, #6366F1 100%);
            --sb-text: rgba(255,255,255,0.65);
            --sb-text-hover: #FFFFFF;
            --sb-text-active: #FFFFFF;
            --sb-border: rgba(255,255,255,0.06);
            --sb-section: rgba(255,255,255,0.28);

            /* Luminous Material Canvas */
            --bg-page: #F8FAFC;
            --bg-card: rgba(255, 255, 255, 0.85);
            --border-card: rgba(226, 232, 240, 0.85);
            --border-subtle: rgba(241, 245, 249, 0.9);

            /* Typography */
            --text-primary: #0F172A;
            --text-secondary: #334155;
            --text-muted: #64748B;
            --text-light: #94A3B8;

            /* Luxury Curated Accents (HSL Tailored) */
            --accent-indigo: #4F46E5;
            --accent-indigo-light: rgba(99, 102, 241, 0.08);
            --accent-indigo-border: rgba(99, 102, 241, 0.25);

            --accent-emerald: #059669;
            --accent-emerald-light: rgba(16, 185, 129, 0.08);
            --accent-emerald-border: rgba(16, 185, 129, 0.25);

            --accent-amber: #D97706;
            --accent-amber-light: rgba(245, 158, 11, 0.08);
            --accent-amber-border: rgba(245, 158, 11, 0.25);

            --accent-rose: #E11D48;
            --accent-rose-light: rgba(225, 29, 72, 0.08);
            --accent-rose-border: rgba(225, 29, 72, 0.25);

            --accent-blue: #2563EB;
            --accent-blue-light: rgba(37, 99, 235, 0.08);
            --accent-blue-border: rgba(37, 99, 235, 0.25);

            --accent-cyan: #0284C7;
            --accent-cyan-light: rgba(2, 132, 199, 0.08);

            /* Multi-layer Depth Shadows */
            --shadow-card: 0 1px 3px 0 rgba(15, 23, 42, 0.03), 0 8px 20px -3px rgba(15, 23, 42, 0.04), inset 0 1px 0 rgba(255, 255, 255, 0.95);
            --shadow-card-hover: 0 16px 36px -4px rgba(99, 102, 241, 0.12), 0 4px 8px -2px rgba(15, 23, 42, 0.04), inset 0 1px 0 #FFFFFF;
            --shadow-header: 0 4px 20px -2px rgba(15, 23, 42, 0.03);
        }

        /* ============================================
           BASE RESET & CANVAS
           ============================================ */
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #F8FAFC;
            background-image: 
                radial-gradient(circle at 12% 12%, rgba(99, 102, 241, 0.06) 0%, transparent 45%),
                radial-gradient(circle at 88% 18%, rgba(6, 182, 212, 0.06) 0%, transparent 40%),
                radial-gradient(circle at 50% 85%, rgba(244, 63, 94, 0.03) 0%, transparent 50%);
            color: var(--text-secondary);
            min-height: 100vh;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            position: relative;
        }

        @keyframes pulseGreen {
            0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.5); }
            70% { box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
            100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94A3B8; }

        /* ============================================
           LAYOUT SHELL
           ============================================ */
        .app-shell {
            display: flex;
            min-height: 100vh;
        }

        /* ============================================
           SIDEBAR — DARK COLLAPSIBLE
           ============================================ */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--sb-bg);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 1000;
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            border-right: 1px solid var(--sb-border);
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed);
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 18px;
            border-bottom: 1px solid var(--sb-border);
            min-height: 58px;
        }

        .sidebar-toggle {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background: rgba(255,255,255,0.08);
            color: rgba(255,255,255,0.75);
            border: 1px solid rgba(255,255,255,0.12);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            flex-shrink: 0;
            font-size: 13px;
        }

        .sidebar-toggle:hover {
            background: rgba(255,255,255,0.15);
            color: #FFFFFF;
        }

        .sidebar-brand {
            display: flex;
            flex-direction: column;
            overflow: hidden;
            white-space: nowrap;
            opacity: 1;
            transition: opacity 0.2s ease;
        }

        .collapsed .sidebar-brand { opacity: 0; pointer-events: none; }

        .sidebar-brand-name {
            font-size: 14px;
            font-weight: 800;
            color: #FFFFFF;
            letter-spacing: -0.3px;
        }

        .sidebar-brand-sub {
            font-size: 10px;
            font-weight: 700;
            color: #38BDF8;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            margin-top: 1px;
        }

        /* Nav */
        .sidebar-nav {
            flex: 1;
            padding: 14px 10px;
            display: flex;
            flex-direction: column;
            gap: 2px;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .nav-section-label {
            font-size: 9.5px;
            font-weight: 700;
            color: var(--sb-section);
            letter-spacing: 1.4px;
            text-transform: uppercase;
            padding: 14px 12px 6px;
            white-space: nowrap;
            overflow: hidden;
        }

        .collapsed .nav-section-label {
            text-align: center;
            padding: 10px 0 4px;
            font-size: 0;
        }
        .collapsed .nav-section-label::after {
            content: '···';
            font-size: 10px;
            color: var(--sb-section);
        }

        .nav-link-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 9px 12px;
            border-radius: 9px;
            font-size: 13px;
            font-weight: 500;
            color: var(--sb-text);
            text-decoration: none !important;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
            position: relative;
        }

        .nav-link-item:hover {
            background: var(--sb-bg-hover);
            color: var(--sb-text-hover);
        }

        .nav-link-item.active {
            background: var(--sb-bg-active);
            color: var(--sb-text-active);
            font-weight: 600;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.35);
        }

        .nav-link-item i {
            font-size: 15px;
            width: 20px;
            text-align: center;
            flex-shrink: 0;
        }

        .nav-link-item .nav-label {
            overflow: hidden;
            transition: opacity 0.2s ease;
        }

        .collapsed .nav-link-item .nav-label {
            opacity: 0;
            width: 0;
        }

        .collapsed .nav-link-item {
            justify-content: center;
            padding: 10px;
        }

        /* Sign Out */
        .nav-signout {
            padding: 12px 10px;
            border-top: 1px solid var(--sb-border);
            margin-top: auto;
        }

        .nav-signout .nav-link-item {
            color: rgba(255,255,255,0.45);
        }
        .nav-signout .nav-link-item:hover {
            color: var(--accent-rose);
            background: rgba(239, 68, 68, 0.12);
        }

        /* ============================================
           MAIN CONTENT AREA
           ============================================ */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-w);
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .sidebar.collapsed ~ .main-content {
            margin-left: var(--sidebar-collapsed);
        }

        /* ============================================
           TOP HEADER (FROSTED GLASSMORPHISM)
           ============================================ */
        .top-header {
            height: 60px;
            padding: 0 26px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(255, 255, 255, 0.82);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            position: sticky;
            top: 0;
            z-index: 500;
            box-shadow: var(--shadow-header);
        }

        .header-greeting h1 {
            font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
            font-size: 17px;
            font-weight: 800;
            color: var(--text-primary);
            letter-spacing: -0.4px;
            margin: 0;
            line-height: 1.2;
        }

        .header-greeting p {
            font-size: 11.5px;
            color: var(--text-muted);
            font-weight: 500;
            margin: 0;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .header-icon-btn {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            border: 1px solid var(--border-card);
            background: #F8FAFC;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
            font-size: 13px;
        }

        .header-icon-btn:hover {
            color: var(--accent-indigo);
            background: var(--accent-indigo-light);
            border-color: var(--accent-indigo-border);
        }

        .notif-dot {
            position: absolute;
            top: 7px;
            right: 7px;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--accent-rose);
            border: 1.5px solid #FFFFFF;
        }

        .header-avatar {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            object-fit: cover;
            border: 2px solid #FFFFFF;
            box-shadow: 0 0 0 1.5px var(--accent-indigo);
            margin-left: 6px;
            cursor: pointer;
            transition: transform 0.2s ease;
        }
        .header-avatar:hover {
            transform: scale(1.05);
        }

        /* ============================================
           CONTENT BODY
           ============================================ */
        .content-body {
            flex: 1;
            padding: 16px 24px 28px;
        }

        /* Tab System */
        .portal-tab { display: none; }
        .portal-tab.active { display: block; }

        /* ============================================
           QUICK STATS ROW (CLEAN MATERIAL CARDS)
           ============================================ */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 14px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(226, 232, 240, 0.85);
            border-radius: 14px;
            padding: 14px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: var(--shadow-card);
            position: relative;
            overflow: hidden;
            transform-style: preserve-3d;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-card-hover);
            border-color: rgba(199, 210, 254, 0.9);
        }

        .stat-left {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            flex-shrink: 0;
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.4);
        }

        .stat-icon.emerald { background: var(--accent-emerald-light); color: var(--accent-emerald); }
        .stat-icon.amber   { background: var(--accent-amber-light); color: var(--accent-amber); }
        .stat-icon.rose     { background: var(--accent-rose-light); color: var(--accent-rose); }
        .stat-icon.indigo   { background: var(--accent-indigo-light); color: var(--accent-indigo); }

        .stat-info { display: flex; flex-direction: column; min-width: 0; }

        .stat-number {
            font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
            font-size: 20px;
            font-weight: 800;
            color: var(--text-primary);
            line-height: 1.1;
            letter-spacing: -0.5px;
            font-variant-numeric: tabular-nums;
        }

        .stat-label {
            font-size: 11px;
            font-weight: 500;
            color: var(--text-muted);
            margin-top: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .stat-pill {
            font-size: 9.5px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 6px;
            white-space: nowrap;
            flex-shrink: 0;
        }
        .stat-pill.green { background: var(--accent-emerald-light); color: var(--accent-emerald); border: 1px solid var(--accent-emerald-border); }
        .stat-pill.amber { background: var(--accent-amber-light); color: var(--accent-amber); border: 1px solid var(--accent-amber-border); }
        .stat-pill.gray  { background: #F1F5F9; color: #64748B; border: 1px solid var(--border-card); }
        .stat-pill.blue  { background: var(--accent-blue-light); color: var(--accent-blue); border: 1px solid var(--accent-blue-border); }

        /* ============================================
           DASHBOARD 2-COLUMN STRUCTURE (NO GAPS!)
           ============================================ */
        .dash-columns {
            display: grid;
            grid-template-columns: 8fr 4fr;
            gap: 14px;
            align-items: start;
        }

        .dash-col-left {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .dash-col-right {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .dash-subgrid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        /* ============================================
           CARD COMPONENT (FROSTED GLASSMORPHISM)
           ============================================ */
        .card-panel {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(226, 232, 240, 0.85);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow-card);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
        }

        .card-panel:hover {
            border-color: rgba(199, 210, 254, 0.9);
            box-shadow: var(--shadow-card-hover);
        }

        .card-head {
            padding: 14px 20px;
            border-bottom: 1px solid var(--border-subtle);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(10px);
        }

        .card-title {
            font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
            font-size: 14px;
            font-weight: 700;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 8px;
            letter-spacing: -0.2px;
        }

        .card-title i {
            font-size: 14px;
            color: var(--accent-indigo);
        }

        .card-body-inner {
            padding: 14px 16px;
        }

        /* ============================================
           ATTENDANCE HERO
           ============================================ */
        .attendance-hero {
            padding: 14px 16px;
        }

        .attendance-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border-subtle);
        }

        .attendance-live-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: var(--accent-indigo);
        }

        .attendance-live-tag .live-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--accent-emerald);
            animation: pulse-dot 1.5s infinite;
        }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(1.3); }
        }

        .attendance-clock {
            font-size: 13px;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.3px;
            font-variant-numeric: tabular-nums;
        }

        .attendance-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 20px;
        }

        .badge-tepat    { background: var(--accent-emerald-light); color: var(--accent-emerald); border: 1px solid var(--accent-emerald-border); }
        .badge-terlambat { background: var(--accent-amber-light); color: var(--accent-amber); border: 1px solid var(--accent-amber-border); }
        .badge-pulang   { background: var(--accent-cyan-light); color: var(--accent-cyan); border: 1px solid #A5F3FC; }
        .badge-belum    { background: #F1F5F9; color: #64748B; border: 1px solid var(--border-card); }

        .attendance-photos {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .atten-photo-block {
            border-radius: 12px;
            padding: 12px 14px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.2s ease;
        }

        .atten-photo-block.masuk { background: #F0FDF4; border: 1px solid #BBF7D0; }
        .atten-photo-block.keluar { background: #FFFBEB; border: 1px solid #FDE68A; }

        .atten-photo-img {
            width: 72px;
            height: 72px;
            border-radius: 10px;
            object-fit: cover;
            border: 2px solid #FFFFFF;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            flex-shrink: 0;
            cursor: pointer;
            transition: transform 0.2s ease;
        }
        .atten-photo-img:hover {
            transform: scale(1.05);
        }

        .atten-photo-empty {
            width: 72px;
            height: 72px;
            border-radius: 10px;
            border: 1.5px dashed #CBD5E1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            background: #F8FAFC;
            flex-shrink: 0;
        }
        .atten-photo-empty i { font-size: 20px; margin-bottom: 2px; }
        .atten-photo-empty span { font-size: 9px; font-weight: 700; }

        .atten-meta {
            flex: 1;
            min-width: 0;
        }

        .atten-photo-label {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.6px;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 5px;
            margin-bottom: 2px;
        }
        .atten-photo-label.green { color: var(--accent-emerald); }
        .atten-photo-label.amber { color: var(--accent-amber); }

        .atten-time {
            font-size: 18px;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 3px;
            font-variant-numeric: tabular-nums;
            line-height: 1.2;
        }

        .atten-subtext {
            font-size: 10.5px;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .attendance-shift-bar {
            margin-top: 12px;
            padding-top: 10px;
            border-top: 1px solid var(--border-subtle);
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 11px;
            color: var(--text-muted);
        }

        /* ============================================
           EXECUTIVE PROFILE CARD
           ============================================ */
        .profile-card-wrapper {
            position: relative;
        }

        .profile-banner {
            height: 46px;
            background: linear-gradient(135deg, #1E1B4B 0%, #4338CA 100%);
            border-radius: 14px 14px 0 0;
            position: relative;
            padding: 8px 14px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        .profile-banner-tag {
            font-size: 9px;
            font-weight: 700;
            color: #FFFFFF;
            background: rgba(255,255,255,0.15);
            padding: 2px 7px;
            border-radius: 4px;
            letter-spacing: 0.5px;
        }

        .profile-block {
            padding: 0 16px 14px;
            text-align: center;
        }

        .profile-avatar-box {
            display: inline-block;
            position: relative;
            margin-top: -24px;
            margin-bottom: 6px;
        }

        .profile-avatar-img {
            width: 64px;
            height: 64px;
            border-radius: 14px;
            object-fit: cover;
            border: 3px solid #FFFFFF;
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        }

        .profile-online-badge {
            position: absolute;
            bottom: -2px;
            right: -2px;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: #10B981;
            border: 2px solid #FFFFFF;
        }

        .profile-name {
            font-size: 15px;
            font-weight: 800;
            color: var(--text-primary);
            letter-spacing: -0.3px;
            margin-bottom: 2px;
            line-height: 1.2;
        }

        .profile-role {
            display: inline-block;
            font-size: 11px;
            font-weight: 600;
            color: var(--accent-indigo);
            background: var(--accent-indigo-light);
            border: 1px solid var(--accent-indigo-border);
            padding: 2px 10px;
            border-radius: 6px;
            margin-bottom: 12px;
        }

        .profile-grid-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6px;
            text-align: left;
            margin-bottom: 12px;
        }

        .profile-grid-item {
            background: #F8FAFC;
            border: 1px solid var(--border-card);
            border-radius: 8px;
            padding: 6px 10px;
        }

        .profile-grid-label {
            font-size: 9px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 1px;
        }

        .profile-grid-val {
            font-size: 11.5px;
            font-weight: 700;
            color: var(--text-primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .profile-biometric-pill {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 7px 10px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 600;
            background: var(--accent-emerald-light);
            border: 1px solid var(--accent-emerald-border);
            color: var(--accent-emerald);
        }

        /* ============================================
           CALENDAR (CLEAN & SLEEK)
           ============================================ */
        .cal-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .cal-nav span {
            font-size: 13px;
            font-weight: 700;
            color: var(--text-primary);
        }

        .cal-nav-btn {
            width: 26px;
            height: 26px;
            border-radius: 6px;
            border: 1px solid var(--border-card);
            background: #F8FAFC;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--text-muted);
            font-size: 10px;
            transition: all 0.2s ease;
        }

        .cal-nav-btn:hover {
            background: var(--accent-indigo-light);
            color: var(--accent-indigo);
            border-color: var(--accent-indigo-border);
        }

        .cal-legend {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
            font-size: 9.5px;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 6px;
        }

        .cal-legend-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            display: inline-block;
        }

        .cal-grid-wrap {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 3px;
            text-align: center;
        }

        .cal-day-label {
            font-size: 9.5px;
            font-weight: 700;
            color: var(--text-muted);
            padding: 4px 0;
            text-transform: uppercase;
        }

        .cal-cell {
            padding: 4px 0;
            height: 30px;
            border-radius: 6px;
            font-size: 11.5px;
            font-weight: 600;
            color: var(--text-secondary);
            background: #F8FAFC;
            cursor: pointer;
            transition: all 0.15s ease;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cal-cell:hover {
            background: var(--accent-indigo-light);
            color: var(--accent-indigo);
            transform: scale(1.06);
        }

        .cal-cell.is-today {
            background: var(--accent-indigo);
            color: #FFFFFF;
            font-weight: 700;
            box-shadow: 0 3px 10px rgba(79, 70, 229, 0.35);
        }

        .cal-cell.is-holiday { color: var(--accent-rose) !important; font-weight: 700; }
        .cal-cell.hl-green { background: var(--accent-emerald-light); border: 1px solid var(--accent-emerald-border); color: var(--accent-emerald); }
        .cal-cell.hl-red   { background: var(--accent-rose-light); border: 1px solid var(--accent-rose-border); color: var(--accent-rose); }

        .cal-dots {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 2px;
            position: absolute;
            bottom: 2px;
            left: 0;
            right: 0;
        }

        .cal-dot {
            width: 4px;
            height: 4px;
            border-radius: 50%;
        }

        .cal-dot.green { background: #10B981; }
        .cal-dot.red   { background: var(--accent-rose); }
        .cal-dot.blue  { background: var(--accent-blue); }

        /* ============================================
           ANNOUNCEMENT LIST
           ============================================ */
        .ann-item {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid var(--border-subtle);
        }

        .ann-item:last-child { border-bottom: none; }

        .ann-title {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 2px;
        }

        .ann-desc {
            font-size: 11px;
            color: var(--text-muted);
        }

        .ann-badge {
            font-size: 9px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 4px;
            background: var(--accent-indigo-light);
            color: var(--accent-indigo);
            border: 1px solid var(--accent-indigo-border);
            white-space: nowrap;
        }

        .ann-date {
            font-size: 10px;
            color: var(--text-muted);
            margin-top: 2px;
        }

        /* ============================================
           QUICK ACTIONS & SHORTCUTS
           ============================================ */
        .quick-actions-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 6px;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid var(--border-subtle);
        }

        .quick-action-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 8px 4px;
            border-radius: 8px;
            border: 1px solid var(--border-card);
            background: #F8FAFC;
            color: var(--text-secondary);
            text-decoration: none !important;
            font-size: 10px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .quick-action-btn i {
            font-size: 14px;
            margin-bottom: 3px;
            color: var(--accent-indigo);
        }

        .quick-action-btn:hover {
            background: #FFFFFF;
            border-color: var(--accent-indigo);
            color: var(--accent-indigo);
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.12);
            transform: translateY(-1px);
        }

        /* ============================================
           LEAVE MANAGEMENT
           ============================================ */
        .leave-stat-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 0;
            font-size: 13px;
        }

        .leave-num {
            font-weight: 800;
            color: var(--text-primary);
            font-size: 14px;
            width: 24px;
        }

        .leave-badge {
            font-size: 9px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 4px;
            background: var(--accent-indigo-light);
            color: var(--accent-indigo);
            border: 1px solid var(--accent-indigo-border);
            margin-left: 6px;
        }

        .leave-sub-tree {
            padding-left: 28px;
            margin-left: 12px;
            border-left: 2px solid var(--border-card);
            margin-top: 6px;
            font-size: 12px;
            color: var(--text-muted);
        }

        .leave-sub-tree div { padding: 3px 0; }

        .leave-req-btn {
            display: block;
            width: 100%;
            padding: 10px;
            border-radius: 9px;
            border: 1px solid var(--accent-indigo-border);
            background: var(--accent-indigo-light);
            color: var(--accent-indigo);
            font-size: 12px;
            font-weight: 700;
            text-align: center;
            cursor: pointer;
            text-decoration: none !important;
            margin-top: 16px;
            transition: all 0.2s ease;
        }

        .leave-req-btn:hover {
            background: var(--accent-indigo);
            color: #FFFFFF;
            border-color: var(--accent-indigo);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
        }

        /* ============================================
           GALLERY
           ============================================ */
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .gallery-thumb {
            width: 100%;
            height: 100px;
            border-radius: 10px;
            object-fit: cover;
            border: 1px solid var(--border-card);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            cursor: pointer;
        }

        .gallery-thumb:hover {
            transform: scale(1.04);
            box-shadow: 0 6px 16px rgba(0,0,0,0.08);
            border-color: #CBD5E1;
        }

        /* ============================================
           TABLES
           ============================================ */
        .tbl-clean {
            width: 100%;
            font-size: 13px;
            border-collapse: collapse;
        }

        .tbl-clean th {
            background: var(--bg-page);
            color: var(--text-muted);
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            padding: 10px 16px;
            border-bottom: 1px solid var(--border-card);
        }

        .tbl-clean td {
            padding: 12px 16px;
            border-bottom: 1px solid var(--border-subtle);
            color: var(--text-secondary);
        }

        .tbl-clean tbody tr:hover { background: #F8FAFC; }

        /* ============================================
           RESPONSIVE
           ============================================ */
        @media (max-width: 1200px) {
            .dash-columns { grid-template-columns: 1fr; }
            .stats-row { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 768px) {
            .sidebar { display: none; }
            .main-content { margin-left: 0 !important; }
            .stats-row { grid-template-columns: 1fr; }
            .attendance-photos { grid-template-columns: 1fr; }
            .dash-subgrid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="app-shell">

        <!-- ==========================================
             SIDEBAR — DARK COLLAPSIBLE
             ========================================== -->
        <aside class="sidebar" id="app-sidebar">
            <div class="sidebar-header">
                <button class="sidebar-toggle" id="sidebar-toggle-btn" title="Toggle Sidebar">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="sidebar-brand">
                    <span class="sidebar-brand-name"><?php echo isset($settingsvalue->sitetitle) ? $settingsvalue->sitetitle : 'DRI HRMS'; ?></span>
                    <span class="sidebar-brand-sub">Enterprise Portal</span>
                </div>
            </div>

            <div class="sidebar-nav">
                <div class="nav-section-label">Menu Utama</div>
                <a class="nav-link-item active" data-tab="tab-home">
                    <i class="fa-solid fa-house"></i>
                    <span class="nav-label">Dashboard</span>
                </a>
                <a class="nav-link-item" data-tab="tab-notices">
                    <i class="fa-solid fa-bullhorn" style="color: #F59E0B;"></i>
                    <span class="nav-label">Pengumuman & Edaran</span>
                    <?php if(isset($pending_notices_count) && $pending_notices_count > 0): ?>
                        <span class="badge badge-danger ml-auto" style="font-size: 10px; border-radius: 10px; padding: 2px 7px;"><?php echo $pending_notices_count; ?> Belum TTD</span>
                    <?php endif; ?>
                </a>
                <a class="nav-link-item" data-tab="tab-projects">
                    <i class="fa-solid fa-diagram-project"></i>
                    <span class="nav-label">Projects & Tasks</span>
                </a>

                <div class="nav-section-label">Sumber Daya</div>
                <a class="nav-link-item" data-tab="tab-todo">
                    <i class="fa-solid fa-list-check"></i>
                    <span class="nav-label">To-Do List</span>
                </a>
                <a class="nav-link-item" data-tab="tab-assets">
                    <i class="fa-solid fa-laptop"></i>
                    <span class="nav-label">Aset Perusahaan</span>
                </a>
                <a class="nav-link-item" data-tab="tab-payroll">
                    <i class="fa-solid fa-wallet"></i>
                    <span class="nav-label">Payroll (THP)</span>
                </a>
                <a class="nav-link-item" data-tab="tab-reimburse">
                    <i class="fa-solid fa-file-invoice-dollar text-warning"></i>
                    <span class="nav-label">Pengajuan Reimbursement</span>
                </a>
            </div>

            <div class="nav-signout">
                <a href="javascript:void(0);" onclick="openSecurityModal();" class="nav-link-item mb-1" style="cursor: pointer;">
                    <i class="fa-solid fa-shield-halved text-info"></i>
                    <span class="nav-label">Keamanan Akun</span>
                </a>
                <a href="<?php echo base_url('login/logout'); ?>" class="nav-link-item">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    <span class="nav-label">Sign Out</span>
                </a>
            </div>
        </aside>

        <!-- ==========================================
             MAIN CONTENT
             ========================================== -->
        <div class="main-content" style="position: relative;">

            <!-- Three.js Ambient 3D Interactive WebGL Canvas -->
            <canvas id="three-ambient-canvas" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 0; opacity: 0.65;"></canvas>

            <!-- Top Header -->
            <header class="top-header" style="position: sticky; top: 0; z-index: 500;">
                <div class="header-greeting">
                    <div class="d-flex align-items-center" style="gap: 10px;">
                        <h1 id="page-title-heading">
                            <?php 
                                $hour = (int)date('H');
                                if($hour < 12) $greeting = 'Selamat Pagi';
                                elseif($hour < 15) $greeting = 'Selamat Siang';
                                elseif($hour < 18) $greeting = 'Selamat Sore';
                                else $greeting = 'Selamat Malam';
                                echo $greeting . ', ' . (isset($employee->first_name) ? $employee->first_name : 'User');
                            ?> 👋
                        </h1>
                        <span class="badge badge-light border text-indigo font-weight-bold px-2.5 py-1 d-none d-md-inline-flex align-items-center" style="font-size: 11px; border-radius: 20px; background: rgba(99, 102, 241, 0.08); border-color: rgba(99, 102, 241, 0.2) !important; color: #4F46E5;">
                            <span class="pulse-dot mr-1.5" style="width: 7px; height: 7px; border-radius: 50%; background: #10B981; display: inline-block; box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.3); animation: pulseGreen 2s infinite;"></span>
                            <span id="live-time-ticker"><?php echo date('H:i'); ?> WIB</span>
                        </span>
                    </div>
                    <p><?php echo date('l, d F Y'); ?></p>
                </div>
                <div class="header-actions">
                    <div class="header-icon-btn" title="Pencarian"><i class="fa-solid fa-magnifying-glass"></i></div>
                    <div class="header-icon-btn" title="Notifikasi">
                        <i class="fa-regular fa-bell"></i>
                        <span class="notif-dot"></span>
                    </div>
                    <div class="header-icon-btn" title="Keamanan Akun (Ubah PIN & Password)" onclick="openSecurityModal();"><i class="fa-solid fa-gear"></i></div>
                    <img src="<?php echo (!empty($employee->em_image) && file_exists(FCPATH.'assets/images/users/'.$employee->em_image)) ? base_url('assets/images/users/'.$employee->em_image) : base_url('assets/images/users/user.png'); ?>" alt="Avatar" class="header-avatar">
                </div>
            </header>

            <!-- Content Body -->
            <div class="content-body" style="position: relative; z-index: 1;">

                <!-- ======================================
                     TAB: HOME DASHBOARD
                     ====================================== -->
                <div class="portal-tab active" id="tab-home">

                    <!-- Quick Stats Row (Compact & High-Density) -->
                    <?php
                        $count_hadir = 0;
                        $count_terlambat = 0;
                        $total_jam = 0;
                        if(!empty($attendance_logs)) {
                            foreach($attendance_logs as $log) {
                                if(isset($log->status) && $log->status == 'L') {
                                    $count_terlambat++;
                                }
                                $count_hadir++;
                                if(!empty($log->signin_time) && !empty($log->signout_time) && $log->signout_time != '00:00:00') {
                                    $total_jam += (strtotime($log->signout_time) - strtotime($log->signin_time)) / 3600;
                                }
                            }
                        }
                    ?>
                    <div class="stats-row">
                        <div class="stat-card tilt-card" data-tilt data-tilt-max="6" data-tilt-glare data-tilt-max-glare="0.12">
                            <div class="stat-left">
                                <div class="stat-icon emerald"><i class="fa-solid fa-calendar-check"></i></div>
                                <div class="stat-info">
                                    <div class="stat-number"><?php echo $count_hadir; ?> <span style="font-size:11px; font-weight:600; color:#94a3b8;">Hari</span></div>
                                    <div class="stat-label">Hadir Bulan Ini</div>
                                </div>
                            </div>
                            <span class="stat-pill green">100% Rate</span>
                        </div>
                        <div class="stat-card tilt-card" data-tilt data-tilt-max="6" data-tilt-glare data-tilt-max-glare="0.12">
                            <div class="stat-left">
                                <div class="stat-icon amber"><i class="fa-solid fa-clock"></i></div>
                                <div class="stat-info">
                                    <div class="stat-number"><?php echo $count_terlambat; ?> <span style="font-size:11px; font-weight:600; color:#94a3b8;">Kali</span></div>
                                    <div class="stat-label">Terlambat Masuk</div>
                                </div>
                            </div>
                            <span class="stat-pill amber">Disiplin</span>
                        </div>
                        <div class="stat-card tilt-card" data-tilt data-tilt-max="6" data-tilt-glare data-tilt-max-glare="0.12">
                            <div class="stat-left">
                                <div class="stat-icon rose"><i class="fa-solid fa-plane-departure"></i></div>
                                <div class="stat-info">
                                    <div class="stat-number"><?php echo 14 - (isset($pending_leaves) ? (int)$pending_leaves : 0); ?> <span style="font-size:11px; font-weight:600; color:#94a3b8;">Hari</span></div>
                                    <div class="stat-label">Sisa Kuota Cuti</div>
                                </div>
                            </div>
                            <span class="stat-pill gray">Tahun 2026</span>
                        </div>
                        <div class="stat-card tilt-card" data-tilt data-tilt-max="6" data-tilt-glare data-tilt-max-glare="0.12">
                            <div class="stat-left">
                                <div class="stat-icon indigo"><i class="fa-solid fa-business-time"></i></div>
                                <div class="stat-info">
                                    <div class="stat-number"><?php echo round($total_jam); ?> <span style="font-size:11px; font-weight:600; color:#94a3b8;">Jam</span></div>
                                    <div class="stat-label">Total Jam Kerja</div>
                                </div>
                            </div>
                            <span class="stat-pill blue">Efektif</span>
                        </div>
                    </div>

                    <!-- Main Dashboard 2-Column Responsive Layout (No empty spaces, seamless flow) -->
                    <div class="dash-columns">

                        <!-- LEFT COLUMN: Operations, Attendance & Work-Life -->
                        <div class="dash-col-left">

                            <!-- 1. PRESENSI HARI INI -->
                            <div class="card-panel">
                                <div class="card-head">
                                    <div class="d-flex align-items-center">
                                        <span class="badge badge-pill font-weight-bold px-2 py-1 mr-2" style="font-size: 10px; letter-spacing: 0.5px; background: #EEF2FF; border: 1px solid #C7D2FE; color: #4F46E5;">
                                            <i class="fa-solid fa-cube mr-1"></i> PRESENSI 3D AI
                                        </span>
                                        <span class="d-inline-flex align-items-center text-muted" style="font-size: 12px; font-weight: 600;">
                                            <span class="live-dot mr-1"></span> Terminal Presensi Terpadu
                                        </span>
                                    </div>
                                    <div class="d-flex align-items-center" style="gap: 8px;">
                                        <div class="attendance-clock px-2 py-1 rounded" id="portal-live-clock" style="font-size: 12px; font-weight: 700; background: #F8FAFC; border: 1px solid #E2E8F0; color: #0F172A;">
                                            <?php echo date('D, d M Y - H:i:s \W\I\B'); ?>
                                        </div>
                                        <?php if(!empty($attendance_today)): ?>
                                            <?php if(!empty($attendance_today->signout_time) && $attendance_today->signout_time != '00:00:00'): ?>
                                                <span class="attendance-status-badge badge-pulang">
                                                    <i class="fa-solid fa-circle-check"></i> SUDAH PULANG
                                                </span>
                                            <?php elseif($attendance_today->status == 'L'): ?>
                                                <span class="attendance-status-badge badge-terlambat">
                                                    <i class="fa-solid fa-clock-rotate-left"></i> MASUK (TERLAMBAT)
                                                </span>
                                            <?php else: ?>
                                                <span class="attendance-status-badge badge-tepat">
                                                    <i class="fa-solid fa-circle-check"></i> MASUK (TEPAT WAKTU)
                                                </span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="attendance-status-badge badge-belum">
                                                <i class="fa-solid fa-circle-minus"></i> BELUM ABSEN
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="attendance-hero">
                                    <?php 
                                        $photo_in_src = '';
                                        if (!empty($attendance_today)) {
                                            if (!empty($attendance_today->id) && !empty($attendance_today->photo_in)) {
                                                $photo_in_src = base_url('face/photo/' . $attendance_today->id . '?slot=in');
                                            } else if (!empty($attendance_today->photo_in)) {
                                                $photo_in_src = (strpos($attendance_today->photo_in, 'http') === 0) 
                                                    ? $attendance_today->photo_in 
                                                    : base_url('uploads/attendance/' . $attendance_today->photo_in);
                                            } else if (!empty($attendance_today->photo_url)) {
                                                $photo_in_src = (strpos($attendance_today->photo_url, 'http') === 0) 
                                                    ? $attendance_today->photo_url 
                                                    : base_url($attendance_today->photo_url);
                                            } else if (!empty($attendance_today->capture_in)) {
                                                $photo_in_src = (strpos($attendance_today->capture_in, 'http') === 0) 
                                                    ? $attendance_today->capture_in 
                                                    : base_url($attendance_today->capture_in);
                                            }
                                        }

                                        $photo_out_src = '';
                                        if (!empty($attendance_today)) {
                                            if (!empty($attendance_today->id) && !empty($attendance_today->photo_out)) {
                                                $photo_out_src = base_url('face/photo/' . $attendance_today->id . '?slot=out');
                                            } else if (!empty($attendance_today->photo_out)) {
                                                $photo_out_src = (strpos($attendance_today->photo_out, 'http') === 0) 
                                                    ? $attendance_today->photo_out 
                                                    : base_url('uploads/attendance/' . $attendance_today->photo_out);
                                            } else if (!empty($attendance_today->capture_out)) {
                                                $photo_out_src = (strpos($attendance_today->capture_out, 'http') === 0) 
                                                    ? $attendance_today->capture_out 
                                                    : base_url($attendance_today->capture_out);
                                            }
                                        }
                                    ?>
                                    <div class="attendance-photos">
                                        <!-- JAM MASUK -->
                                        <div class="atten-photo-block masuk">
                                            <?php if(!empty($photo_in_src)): ?>
                                                <img src="<?php echo $photo_in_src; ?>" class="atten-photo-img" alt="Foto Masuk" onerror="this.onerror=null; this.src='<?php echo base_url('assets/images/users/user.png'); ?>';">
                                            <?php else: ?>
                                                <div class="atten-photo-empty">
                                                    <i class="fa-solid fa-camera"></i>
                                                    <span>Belum Ada</span>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div class="atten-meta">
                                                <div class="atten-photo-label green">
                                                    <i class="fa-solid fa-right-to-bracket"></i> JAM MASUK TERCATAT
                                                </div>
                                                <div class="atten-time">
                                                    <?php echo (!empty($attendance_today) && !empty($attendance_today->signin_time)) ? date('H:i:s', strtotime($attendance_today->signin_time)) . ' WIB' : '--:--:--'; ?>
                                                </div>
                                                <div class="atten-subtext">
                                                    <?php if(!empty($attendance_today)): ?>
                                                        <span class="badge badge-success px-2 py-0" style="font-size:10px;"><i class="fa-solid fa-shield-check mr-1"></i> 3D Verified</span>
                                                        <span class="text-muted ml-1">• Terminal <?php echo (!empty($attendance_today->device_in) ? strtoupper($attendance_today->device_in) : 'Web / Terminal'); ?></span>
                                                    <?php else: ?>
                                                        <span class="text-muted"><i class="fa-solid fa-circle-info mr-1"></i> Silakan absen di Terminal atau Portal</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- JAM KELUAR -->
                                        <div class="atten-photo-block keluar">
                                            <?php if(!empty($photo_out_src)): ?>
                                                <img src="<?php echo $photo_out_src; ?>" class="atten-photo-img" alt="Foto Keluar" onerror="this.onerror=null; this.src='<?php echo base_url('assets/images/users/user.png'); ?>';">
                                            <?php else: ?>
                                                <div class="atten-photo-empty">
                                                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                                    <span>Belum Pulang</span>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div class="atten-meta">
                                                <div class="atten-photo-label amber">
                                                    <i class="fa-solid fa-right-from-bracket"></i> JAM KELUAR (PULANG)
                                                </div>
                                                <div class="atten-time">
                                                    <?php echo (!empty($attendance_today) && !empty($attendance_today->signout_time) && $attendance_today->signout_time != '00:00:00') ? date('H:i:s', strtotime($attendance_today->signout_time)) . ' WIB' : '--:--:--'; ?>
                                                </div>
                                                <div class="atten-subtext">
                                                    <?php if(!empty($attendance_today) && !empty($attendance_today->signout_time) && $attendance_today->signout_time != '00:00:00'): ?>
                                                        <span class="badge badge-info px-2 py-0" style="font-size:10px;"><i class="fa-solid fa-check mr-1"></i> Selesai Shift</span>
                                                    <?php else: ?>
                                                        <span class="badge badge-light text-dark px-2 py-0" style="font-size:10px; background:#e2e8f0;"><i class="fa-solid fa-clock mr-1"></i> Shift Berakhir 17:00 WIB</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="attendance-shift-bar">
                                        <div>
                                            <i class="fa-solid fa-briefcase text-primary mr-1"></i> Shift Kantor: <strong>08:00 - 17:00 WIB</strong>
                                        </div>
                                        <div>
                                            <span class="text-muted"><i class="fa-solid fa-location-dot mr-1"></i> Head Office Jakarta</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 2. KALENDER KEHADIRAN (Placed immediately under Presensi, NO empty gap) -->
                            <div class="card-panel">
                                <div class="card-head">
                                    <div class="card-title"><i class="fa-solid fa-calendar-days text-primary"></i> Kalender & Riwayat Kehadiran</div>
                                    <div class="cal-legend m-0">
                                        <div class="d-flex align-items-center mr-2"><span class="cal-legend-dot mr-1" style="background:#059669;"></span> Hadir</div>
                                        <div class="d-flex align-items-center mr-2"><span class="cal-legend-dot mr-1" style="background:var(--accent-rose);"></span> Terlambat</div>
                                        <div class="d-flex align-items-center"><span class="cal-legend-dot mr-1" style="background:var(--accent-blue);"></span> Event</div>
                                    </div>
                                </div>
                                <div class="card-body-inner py-3">
                                    <div class="cal-nav">
                                        <div class="cal-nav-btn"><i class="fa-solid fa-chevron-left"></i></div>
                                        <span><?php echo date('F Y'); ?></span>
                                        <div class="cal-nav-btn"><i class="fa-solid fa-chevron-right"></i></div>
                                    </div>

                                    <div class="cal-grid-wrap mb-1">
                                        <div class="cal-day-label" style="color:var(--accent-rose);">Min</div>
                                        <div class="cal-day-label">Sen</div>
                                        <div class="cal-day-label">Sel</div>
                                        <div class="cal-day-label">Rab</div>
                                        <div class="cal-day-label">Kam</div>
                                        <div class="cal-day-label">Jum</div>
                                        <div class="cal-day-label">Sab</div>
                                    </div>

                                    <div class="cal-grid-wrap">
                                        <?php 
                                            for ($p = 0; $p < $month_start_weekday; $p++) {
                                                echo '<div class="cal-cell" style="color:#CBD5E1;">-</div>';
                                            }

                                            $today_day = (int)date('j');
                                            for ($day = 1; $day <= $days_in_month; $day++) {
                                                $info = isset($calendar_matrix[$day]) ? $calendar_matrix[$day] : null;
                                                $weekday = (int)date('w', strtotime(date('Y-m-') . sprintf('%02d', $day)));
                                                $is_sunday = ($weekday === 0);
                                                $is_holiday = ($info && $info['is_holiday']) || $is_sunday;

                                                $cell_class = '';
                                                if ($day == $today_day) $cell_class .= ' is-today';
                                                if ($is_holiday && $day != $today_day) $cell_class .= ' is-holiday';

                                                $status = $info ? $info['status'] : 'NONE';
                                                if ($day != $today_day) {
                                                    if ($status === 'CLEAR') $cell_class .= ' hl-green';
                                                    else if ($status === 'NOT_CLEAR') $cell_class .= ' hl-red';
                                                }

                                                $json_data = htmlspecialchars(json_encode($info), ENT_QUOTES, 'UTF-8');

                                                echo '<div class="cal-cell ' . $cell_class . '" data-info="' . $json_data . '">';
                                                echo $day;
                                                echo '<div class="cal-dots">';
                                                if ($status === 'CLEAR') echo '<span class="cal-dot green"></span>';
                                                else if ($status === 'NOT_CLEAR') echo '<span class="cal-dot red"></span>';
                                                if ($info && $info['has_event']) echo '<span class="cal-dot blue"></span>';
                                                echo '</div>';
                                                echo '</div>';
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <!-- 3. LEAVE MANAGEMENT -->
                            <div class="card-panel">
                                <div class="card-head">
                                    <div class="card-title"><i class="fa-solid fa-calendar-minus text-primary"></i> Leave Management</div>
                                    <a href="#tab-leave" class="btn btn-sm btn-primary rounded-pill px-3 py-1 font-weight-bold" style="font-size:11px;" onclick="$('.nav-link-item[data-tab=\'tab-leave\']').click();">
                                        <i class="fa-solid fa-plus mr-1"></i> Ajukan Cuti
                                    </a>
                                </div>
                                <div class="card-body-inner py-3">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap: 16px;">
                                        <div class="d-flex align-items-center" style="gap: 20px;">
                                            <div class="leave-stat-row m-0">
                                                <span class="leave-num"><?php echo isset($pending_leaves) ? $pending_leaves : '0'; ?></span>
                                                <span class="text-muted ml-2" style="font-size:12px; font-weight:600;">Pending Requests</span>
                                            </div>
                                            <div class="leave-stat-row m-0">
                                                <span class="leave-num">14</span>
                                                <span class="text-muted ml-2" style="font-size:12px; font-weight:600;">Available Leaves (<?php echo date('Y'); ?>)</span>
                                            </div>
                                        </div>
                                        <div class="leave-sub-tree m-0 d-flex" style="gap: 16px;">
                                            <div><strong class="text-primary mr-1">10</strong> Vacation Leaves</div>
                                            <div><strong class="text-success mr-1">4</strong> Sick Leaves</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- RIGHT COLUMN: Identity & Quick Actions -->
                        <div class="dash-col-right">

                            <!-- 1. PROFILE CARD (Executive ID Card) -->
                            <div class="card-panel profile-card-wrapper">
                                <div class="profile-banner">
                                    <span class="profile-banner-tag"><i class="fa-solid fa-id-card mr-1"></i> ID CARD</span>
                                </div>
                                <div class="profile-block">
                                    <div class="profile-avatar-box">
                                        <img src="<?php echo (!empty($employee->em_image) && file_exists(FCPATH.'assets/images/users/'.$employee->em_image)) ? base_url('assets/images/users/'.$employee->em_image) : base_url('assets/images/users/user.png'); ?>" alt="Avatar" class="profile-avatar-img">
                                        <span class="profile-online-badge" title="Active Employee"></span>
                                    </div>
                                    
                                    <div class="profile-name">
                                        <?php echo isset($employee->first_name) ? trim($employee->first_name . ' ' . $employee->last_name) : 'Employee'; ?>
                                    </div>
                                    <div class="profile-role">
                                        <?php echo isset($designation->des_name) ? $designation->des_name : 'Staff'; ?>
                                    </div>

                                    <div class="profile-grid-info">
                                        <div class="profile-grid-item">
                                            <div class="profile-grid-label">Departemen</div>
                                            <div class="profile-grid-val" title="<?php echo isset($department->dep_name) ? $department->dep_name : '-'; ?>">
                                                <?php echo isset($department->dep_name) ? $department->dep_name : '-'; ?>
                                            </div>
                                        </div>
                                        <div class="profile-grid-item">
                                            <div class="profile-grid-label">Manager</div>
                                            <div class="profile-grid-val" title="<?php echo isset($manager->first_name) ? trim($manager->first_name . ' ' . $manager->last_name) : '-'; ?>">
                                                <?php echo isset($manager->first_name) ? trim($manager->first_name . ' ' . $manager->last_name) : '-'; ?>
                                            </div>
                                        </div>
                                        <div class="profile-grid-item">
                                            <div class="profile-grid-label">NIK / ID</div>
                                            <div class="profile-grid-val">
                                                <?php echo isset($employee->em_code) ? $employee->em_code : (isset($employee->em_id) ? $employee->em_id : '-'); ?>
                                            </div>
                                        </div>
                                        <div class="profile-grid-item">
                                            <div class="profile-grid-label">Lokasi</div>
                                            <div class="profile-grid-val">
                                                Jakarta, ID
                                            </div>
                                        </div>
                                    </div>

                                    <?php if(!empty($face_signature)): ?>
                                        <div class="profile-biometric-pill">
                                            <span><i class="fa-solid fa-shield-check mr-1"></i> Biometrik 3D Terdaftar</span>
                                            <a href="javascript:void(0);" onclick="startWebFaceRegistration();" class="text-primary font-weight-bold" style="font-size:11px; text-decoration:none;">
                                                <i class="fa-solid fa-rotate mr-1"></i> Pindai Ulang
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <button type="button" class="btn btn-danger btn-sm btn-block font-weight-bold rounded-lg py-2" onclick="startWebFaceRegistration();" style="font-size:12px;">
                                            <i class="fa-solid fa-face-smile mr-1"></i> Daftarkan Wajah 3D
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- 2. ANNOUNCEMENTS & QUICK ACTIONS -->
                            <div class="card-panel">
                                <div class="card-head">
                                    <div class="card-title"><i class="fa-solid fa-bullhorn text-warning"></i> Pengumuman & Notice Board</div>
                                    <div class="d-flex align-items-center" style="gap: 12px;">
                                        <a href="javascript:void(0);" onclick="$('.nav-link-item[data-tab=\'tab-notices\']').click();" class="text-primary font-weight-bold" style="font-size: 11.5px; text-decoration: none;">
                                            <i class="fa-solid fa-clock-rotate-left mr-1"></i> Arsip & Riwayat &rarr;
                                        </a>
                                        <span class="text-muted" style="font-size: 11px;">|</span>
                                        <a href="javascript:void(0);" onclick="openChangePinModal();" class="text-secondary font-weight-bold" style="font-size: 11.5px; text-decoration: none;">
                                            <i class="fa-solid fa-key mr-1"></i> Atur PIN
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body-inner py-3">
                                    <?php if(!empty($announcements)): ?>
                                        <?php foreach($announcements as $ann): ?>
                                            <div class="ann-item" id="ann-card-<?php echo $ann->id; ?>" style="padding: 12px 14px; border-radius: 10px; background: #F8FAFC; border: 1px solid #E2E8F0; margin-bottom: 10px; display: flex; flex-direction: column; gap: 8px;">
                                                <div class="d-flex align-items-start justify-content-between">
                                                    <div>
                                                        <div class="d-flex align-items-center" style="gap: 6px; margin-bottom: 3px;">
                                                            <?php if(!empty($ann->is_mandatory)): ?>
                                                                <span class="badge badge-pill font-weight-bold px-2 py-0" style="font-size: 9.5px; background: #FEF2F2; color: #DC2626; border: 1px solid #FECACA;">
                                                                    <i class="fa-solid fa-circle-exclamation mr-1"></i> Wajib TTD
                                                                </span>
                                                            <?php endif; ?>
                                                            <span class="text-muted" style="font-size: 11px; font-weight: 500;">
                                                                <i class="fa-regular fa-calendar mr-1"></i> <?php echo date('d M Y', strtotime($ann->date)); ?>
                                                            </span>
                                                        </div>
                                                        <div class="ann-title font-weight-bold" style="font-size: 13px; color: #0F172A; line-height: 1.3;"><?php echo $ann->title; ?></div>
                                                    </div>
                                                </div>

                                                <div class="ann-desc text-muted" style="font-size: 11.5px; line-height: 1.4;">
                                                    <?php echo !empty($ann->description) ? (strlen($ann->description) > 90 ? substr($ann->description, 0, 90).'...' : $ann->description) : 'Silakan buka detail untuk membaca surat edaran.'; ?>
                                                </div>

                                                <?php 
                                                    $ext = !empty($ann->file_url) ? strtolower(pathinfo($ann->file_url, PATHINFO_EXTENSION)) : '';
                                                    $has_image = in_array($ext, ['png', 'jpg', 'jpeg', 'gif', 'webp']);
                                                    $js_title = htmlspecialchars(json_encode($ann->title), ENT_QUOTES, 'UTF-8');
                                                    $js_desc = htmlspecialchars(json_encode(!empty($ann->description) ? $ann->description : 'Tidak ada deskripsi teks tambahan.'), ENT_QUOTES, 'UTF-8');
                                                    $js_date = htmlspecialchars(json_encode(date('d F Y', strtotime($ann->date))), ENT_QUOTES, 'UTF-8');
                                                    $js_file = htmlspecialchars(json_encode(!empty($ann->file_url) ? base_url('assets/images/notice/'.$ann->file_url) : ''), ENT_QUOTES, 'UTF-8');
                                                    $js_signed = !empty($ann->signed_at) ? 1 : 0;
                                                    $js_signedat = htmlspecialchars(json_encode(!empty($ann->signed_at) ? date('d M Y, H:i', strtotime($ann->signed_at)).' WIB' : ''), ENT_QUOTES, 'UTF-8');
                                                ?>
                                                <?php if($has_image): ?>
                                                    <div class="ann-media-preview my-1" style="cursor: pointer; border-radius: 8px; overflow: hidden; border: 1px solid #E2E8F0;" onclick="openNoticeSignModal(<?php echo $ann->id; ?>, <?php echo $js_title; ?>, <?php echo $js_desc; ?>, <?php echo $js_date; ?>, <?php echo $js_file; ?>, <?php echo $js_signed; ?>, <?php echo $js_signedat; ?>);">
                                                        <img src="<?php echo base_url('assets/images/notice/'.$ann->file_url); ?>" alt="Preview" style="width: 100%; max-height: 130px; object-fit: cover; object-position: top; display: block;">
                                                    </div>
                                                <?php endif; ?>

                                                <div class="d-flex align-items-center justify-content-between pt-2 border-top" style="border-color: #E2E8F0 !important;">
                                                    <div class="ann-signature-status" id="ann-status-<?php echo $ann->id; ?>">
                                                        <?php if(!empty($ann->signed_at)): ?>
                                                            <span class="badge badge-success px-2 py-1 font-weight-bold" style="font-size: 10px; background: #ECFDF5; color: #059669; border: 1px solid #A7F3D0;">
                                                                <i class="fa-solid fa-circle-check mr-1"></i> Tertanda (Signed)
                                                            </span>
                                                            <div class="text-muted mt-1" style="font-size: 9.5px;"><?php echo date('d M Y, H:i', strtotime($ann->signed_at)); ?></div>
                                                        <?php else: ?>
                                                            <span class="badge badge-warning px-2 py-1 font-weight-bold" style="font-size: 10px; background: #FFFBEB; color: #D97706; border: 1px solid #FDE68A;">
                                                                <i class="fa-solid fa-pen-nib mr-1"></i> Belum TTD
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>

                                                    <div>
                                                        <?php 
                                                            $js_title = htmlspecialchars(json_encode($ann->title), ENT_QUOTES, 'UTF-8');
                                                            $js_desc = htmlspecialchars(json_encode(!empty($ann->description) ? $ann->description : 'Tidak ada deskripsi teks tambahan.'), ENT_QUOTES, 'UTF-8');
                                                            $js_date = htmlspecialchars(json_encode(date('d F Y', strtotime($ann->date))), ENT_QUOTES, 'UTF-8');
                                                            $js_file = htmlspecialchars(json_encode(!empty($ann->file_url) ? base_url('assets/images/notice/'.$ann->file_url) : ''), ENT_QUOTES, 'UTF-8');
                                                            $js_signed = !empty($ann->signed_at) ? 1 : 0;
                                                            $js_signedat = htmlspecialchars(json_encode(!empty($ann->signed_at) ? date('d M Y, H:i', strtotime($ann->signed_at)).' WIB' : ''), ENT_QUOTES, 'UTF-8');
                                                        ?>
                                                        <?php if(empty($ann->signed_at)): ?>
                                                            <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 py-1 font-weight-bold" 
                                                                onclick="openNoticeSignModal(<?php echo $ann->id; ?>, <?php echo $js_title; ?>, <?php echo $js_desc; ?>, <?php echo $js_date; ?>, <?php echo $js_file; ?>, <?php echo $js_signed; ?>, <?php echo $js_signedat; ?>);"
                                                                style="font-size: 11px; background: #4F46E5; border: none; box-shadow: 0 2px 6px rgba(79,70,229,0.25);">
                                                                <i class="fa-solid fa-key mr-1"></i> Tanda Tangani (PIN)
                                                            </button>
                                                        <?php else: ?>
                                                            <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 py-1 font-weight-bold text-muted" 
                                                                onclick="openNoticeSignModal(<?php echo $ann->id; ?>, <?php echo $js_title; ?>, <?php echo $js_desc; ?>, <?php echo $js_date; ?>, <?php echo $js_file; ?>, <?php echo $js_signed; ?>, <?php echo $js_signedat; ?>);"
                                                                style="font-size: 11px;">
                                                                <i class="fa-regular fa-file-lines mr-1"></i> Buka Dokumen
                                                            </button>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="text-center py-4 text-muted" style="font-size: 12px;">
                                            <i class="fa-solid fa-bullhorn mb-2" style="font-size: 24px; opacity: 0.3;"></i>
                                            <div>Belum ada pengumuman resmi.</div>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Quick Action Shortcuts -->
                                    <div class="quick-actions-grid">
                                        <a href="#tab-leave" class="quick-action-btn" onclick="$('.nav-link-item[data-tab=\'tab-leave\']').click();">
                                            <i class="fa-solid fa-plane-departure"></i>
                                            <span>Ajukan Cuti</span>
                                        </a>
                                        <a href="#tab-reimbursement" class="quick-action-btn" onclick="$('.nav-link-item[data-tab=\'tab-reimbursement\']').click();">
                                            <i class="fa-solid fa-receipt"></i>
                                            <span>Reimburse</span>
                                        </a>
                                        <a href="javascript:void(0);" class="quick-action-btn" onclick="alert('Fitur e-Slip Gaji bulanan dapat diunduh di tab Payroll.');">
                                            <i class="fa-solid fa-file-invoice-dollar"></i>
                                            <span>Slip Gaji</span>
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

                <!-- ======================================
                     TAB: PROJECTS & TASKS
                     ====================================== -->
                <div class="portal-tab" id="tab-projects">
                    <div class="card-panel">
                        <div class="card-head">
                            <div class="card-title"><i class="fa-solid fa-diagram-project"></i> Project & Tugas Saya</div>
                        </div>
                        <div class="card-body-inner" style="padding:0;">
                            <div class="table-responsive">
                                <table class="tbl-clean">
                                    <thead>
                                        <tr>
                                            <th>Nama Project</th>
                                            <th>Tanggal Mulai</th>
                                            <th>Deadline</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(!empty($my_projects)): ?>
                                            <?php foreach($my_projects as $pro): ?>
                                                <tr>
                                                    <td class="font-weight-bold" style="color:var(--text-primary);"><?php echo isset($pro->pro_name) ? $pro->pro_name : 'Project Alpha'; ?></td>
                                                    <td><?php echo isset($pro->pro_start_date) ? date('d M Y', strtotime($pro->pro_start_date)) : '-'; ?></td>
                                                    <td><?php echo isset($pro->pro_end_date) ? date('d M Y', strtotime($pro->pro_end_date)) : '-'; ?></td>
                                                    <td><span class="badge badge-success px-3 py-1"><?php echo isset($pro->pro_status) ? strtoupper($pro->pro_status) : 'RUNNING'; ?></span></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada project yang ditugaskan.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB: TO-DO -->
                <div class="portal-tab" id="tab-todo">
                    <div class="card-panel">
                        <div class="card-head">
                            <div class="card-title"><i class="fa-solid fa-list-check"></i> To-Do List Pribadi</div>
                        </div>
                        <div class="card-body-inner">
                            <?php if(!empty($my_todos)): ?>
                                <?php foreach($my_todos as $todo): ?>
                                    <div class="d-flex align-items-center justify-content-between p-2 border-bottom" style="border-color:#F8FAFC !important;">
                                        <div>
                                            <input type="checkbox" <?php echo ($todo->value == 1) ? 'checked' : ''; ?> class="mr-2">
                                            <span style="font-weight:600; color:var(--text-primary);"><?php echo $todo->to_dodata; ?></span>
                                        </div>
                                        <small class="text-muted"><?php echo date('d M Y', strtotime($todo->date)); ?></small>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center py-3 text-muted">Belum ada daftar tugas pribadi.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- TAB: ASSETS -->
                <div class="portal-tab" id="tab-assets">
                    <div class="card-panel">
                        <div class="card-head">
                            <div class="card-title"><i class="fa-solid fa-laptop"></i> Aset & Peralatan</div>
                        </div>
                        <div class="card-body-inner" style="padding:0;">
                            <div class="table-responsive">
                                <table class="tbl-clean">
                                    <thead>
                                        <tr>
                                            <th>Nama Aset</th>
                                            <th>Kuantitas</th>
                                            <th>Tanggal Penyerahan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(!empty($my_assets)): ?>
                                            <?php foreach($my_assets as $ass): ?>
                                                <tr>
                                                    <td class="font-weight-bold" style="color:var(--text-primary);"><?php echo isset($ass->ass_name) ? $ass->ass_name : '-'; ?></td>
                                                    <td><?php echo isset($ass->log_qty) ? $ass->log_qty . ' Unit' : '1 Unit'; ?></td>
                                                    <td><?php echo isset($ass->start_date) ? date('d M Y', strtotime($ass->start_date)) : '-'; ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr><td colspan="3" class="text-center py-4 text-muted">Belum ada aset yang dicatat.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB: PAYROLL -->
                <div class="portal-tab" id="tab-payroll">
                    <div class="card-panel">
                        <div class="card-head">
                            <div class="card-title"><i class="fa-solid fa-wallet"></i> Payroll & Take Home Pay</div>
                        </div>
                        <div class="card-body-inner">
                            <?php 
                                $gaji_bruto = (isset($salary_info->addition) && $salary_info->addition > 0) ? (float)$salary_info->addition : ((isset($salary_info->basic)) ? (float)$salary_info->basic : 0);
                                $total_potongan = (isset($salary_info->diduction)) ? (float)$salary_info->diduction : 0;
                                $thp = (isset($salary_info->total_pay) && $salary_info->total_pay > 0) ? (float)$salary_info->total_pay : ($gaji_bruto - $total_potongan);
                            ?>
                            <div class="p-4 rounded-lg mb-3" style="background: linear-gradient(135deg, rgba(99,102,241,0.06), rgba(139,92,246,0.06)); border: 1px solid rgba(99,102,241,0.12);">
                                <small style="font-size:10px; font-weight:700; color:var(--text-muted); letter-spacing:0.5px;">TAKE HOME PAY (THP)</small>
                                <h2 style="font-size:28px; font-weight:800; color:var(--text-primary); margin:4px 0 0;">Rp <?php echo number_format($thp, 0, ',', '.'); ?></h2>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB: REIMBURSEMENT (BULK BATCH STAGING) -->
                <div class="portal-tab" id="tab-reimburse">
                    
                    <!-- Sub-Tab Navigation Header -->
                    <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
                        <ul class="nav nav-pills" id="reimb-subtabs" style="gap: 8px;">
                            <li class="nav-item">
                                <a class="nav-link active font-weight-bold px-4 py-2 text-white" id="subtab-btn-input" data-subtab="reimb-subtab-input" style="border-radius:20px; font-size:13px; cursor:pointer; background:#6366F1;">
                                    <i class="fa-solid fa-plus-circle mr-1.5"></i> Form Input Transaksi
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link font-weight-bold px-4 py-2 text-dark bg-white border" id="subtab-btn-list" data-subtab="reimb-subtab-list" style="border-radius:20px; font-size:13px; cursor:pointer;">
                                    <i class="fa-solid fa-receipt mr-1.5 text-indigo"></i> Daftar & Riwayat Reimbursement (<?php echo count($my_reimbursement_batches); ?>)
                                </a>
                            </li>
                        </ul>
                        <span class="badge badge-light border text-secondary px-3 py-1.5" style="border-radius:20px; font-size:11px;">
                            <i class="fa-solid fa-shield-check text-success mr-1"></i> Mode Input Kolektif
                        </span>
                    </div>

                    <!-- SUB-TAB 1: FORM INPUT TRANSAKSI -->
                    <div class="reimb-subtab-content" id="reimb-subtab-input">
                        <div class="row">
                            <!-- Left Column: Upload & Auto-Read Scanner -->
                            <div class="col-lg-5 mb-4">
                                <div class="card-panel">
                                    <div class="card-head d-flex align-items-center justify-content-between">
                                        <div class="card-title"><i class="fa-solid fa-receipt text-indigo"></i> 1. Unggah & Pindai Struk / Nota</div>
                                        <span class="badge badge-indigo px-3 py-1.5" style="border-radius: 20px; font-size: 10px; background:rgba(99,102,241,0.1); color:#6366F1;">Auto OCR Reader</span>
                                    </div>
                                    <div class="card-body-inner">
                                        
                                        <!-- Hidden Settings (Default Auto OCR Engine) -->
                                        <div class="d-none">
                                            <select id="ai-provider-select"><option value="nararouter" selected></option></select>
                                            <select id="ai-model-select"><option value="agnes-2.5-flash" selected></option></select>
                                            <input type="password" id="ai-api-key">
                                        </div>

                                        <!-- File Upload Dropzone Area -->
                                        <div class="form-group mb-3">
                                            <label class="font-weight-bold text-dark" style="font-size:12px;"><i class="fa-solid fa-cloud-arrow-up text-indigo mr-1"></i> Bukti Transaksi / Nota / Kuitansi</label>
                                            <label for="receipt-file-input" class="w-100 p-4 rounded-lg text-center mb-0" id="receipt-upload-box" style="border: 2px dashed #CBD5E1; background: rgba(241,245,249,0.5); cursor: pointer; transition: all 0.2s ease; display: block;">
                                                <i class="fa-solid fa-camera text-muted mb-2" style="font-size: 32px;" id="upload-icon"></i>
                                                <h6 class="font-weight-bold text-dark mb-1" id="upload-label" style="font-size:13px;">Klik atau Tarik File Nota Ke Sini</h6>
                                                <small class="text-muted d-block" style="font-size:11px;">Format: JPG, PNG, WEBP, PDF (Max 10MB)</small>
                                                <img id="receipt-preview-img" src="" class="img-fluid rounded mt-2 d-none" style="max-height: 160px; object-fit: contain;">
                                                <div id="pdf-preview-box" class="p-3 bg-white rounded border d-none mt-2">
                                                    <i class="fa-solid fa-file-pdf text-danger mr-1" style="font-size:24px;"></i>
                                                    <strong id="pdf-file-name" class="text-dark" style="font-size:12px;">Dokumen PDF</strong>
                                                </div>
                                                <input type="file" id="receipt-file-input" accept="image/*,.pdf,application/pdf" class="d-none">
                                            </label>
                                        </div>

                                        <!-- Auto Scan Action Button -->
                                        <button type="button" class="btn btn-indigo btn-block font-weight-bold py-2 mb-3" id="btn-scan-ai" style="border-radius: 10px; background: linear-gradient(135deg, #6366F1, #8B5CF6); color:#fff; border:none;">
                                            <i class="fa-solid fa-bolt mr-2" id="scan-btn-icon"></i> <span id="scan-btn-text">Pindai & Ekstrak Data Nota</span>
                                        </button>

                                        <!-- Single Form Input Item -->
                                        <div class="p-3 rounded-lg" style="background: rgba(241,245,249,0.7); border: 1px solid #E2E8F0;">
                                            <small class="font-weight-bold text-dark d-block mb-2"><i class="fa-solid fa-pen-to-square text-indigo mr-1"></i> Rincian Informasi Transaksi</small>
                                            
                                            <input type="hidden" id="input-receipt-image">
                                            <input type="hidden" id="input-ai-analysis">

                                            <div class="form-group mb-2">
                                                <label class="font-weight-bold text-dark" style="font-size:11px;">Judul Klaim / Merchant</label>
                                                <input type="text" class="form-control form-control-sm" id="input-title" placeholder="Contoh: Bensin Pertamax / Indomaret">
                                            </div>

                                            <div class="row">
                                                <div class="col-6 form-group mb-2">
                                                    <label class="font-weight-bold text-dark" style="font-size:11px;">Nominal (Rp)</label>
                                                    <input type="number" step="0.01" class="form-control form-control-sm font-weight-bold text-success" id="input-amount" placeholder="0">
                                                </div>
                                                <div class="col-6 form-group mb-2">
                                                    <label class="font-weight-bold text-dark" style="font-size:11px;">Kategori</label>
                                                    <select class="form-control form-control-sm" id="input-category">
                                                        <option value="Transportasi">Transportasi / Bensin</option>
                                                        <option value="Konsumsi">Konsumsi / Makan Rapat</option>
                                                        <option value="Perjalanan Dinas">Perjalanan Dinas</option>
                                                        <option value="Operasional Kantor">Operasional Kantor</option>
                                                        <option value="Kesehatan">Kesehatan / Medis</option>
                                                        <option value="Umum" selected>Lainnya / Umum</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label class="font-weight-bold text-dark" style="font-size:11px;">Detail Transaksi</label>
                                                <textarea class="form-control" id="input-description" rows="6" placeholder="Rincian detail barang, aktivitas, atau daftar transaksi..." style="font-size:12px; line-height:1.6; min-height:130px; border-radius:8px; background:#fff;"></textarea>
                                            </div>

                                            <button type="button" class="btn btn-primary btn-block font-weight-bold py-2" id="btn-add-to-staging" style="border-radius: 8px; background: #6366F1; border:none;">
                                                <i class="fa-solid fa-plus-circle mr-1"></i> Masukkan ke Temporary List
                                            </button>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- Right Column: Temporary Staging List & Batch Master Submission -->
                            <div class="col-lg-7 mb-4">
                                <div class="card-panel mb-4" style="border: 2px solid var(--accent-indigo);">
                                    <div class="card-head d-flex align-items-center justify-content-between" style="background: rgba(99,102,241,0.06); border-bottom: 1px solid rgba(99,102,241,0.15);">
                                        <div class="card-title text-indigo"><i class="fa-solid fa-layer-group"></i> 2. Temporary Staging List (Koleksi Nota)</div>
                                        <span class="badge badge-indigo px-3 py-1" id="staging-count-badge" style="border-radius: 20px; font-size:11px; background:#6366F1; color:#fff;">0 Nota Terkumpul</span>
                                    </div>
                                    <div class="card-body-inner p-0">
                                        
                                        <!-- Live Grand Total Banner -->
                                        <div class="p-3 text-white d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #0F172A, #1E293B);">
                                            <div>
                                                <small class="text-uppercase font-weight-bold text-cyan" style="font-size:10px; letter-spacing:1px;">GRAND TOTAL KLAIM BATCH</small>
                                                <h3 class="font-weight-bold mb-0 text-white" id="live-grand-total" style="font-size:24px;">Rp 0</h3>
                                            </div>
                                            <div class="text-right">
                                                <small class="text-muted d-block" style="font-size:10px;">Status Staging</small>
                                                <span class="badge badge-warning" id="staging-status-tag">Draft Temporary</span>
                                            </div>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="tbl-clean" id="table-staging-items">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 40px;">#</th>
                                                        <th style="width: 70px;">Nota</th>
                                                        <th>Judul / Merchant</th>
                                                        <th>Kategori</th>
                                                        <th class="text-right">Nominal</th>
                                                        <th style="width: 60px;" class="text-center">Hapus</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbody-staging">
                                                    <tr id="tr-empty-staging">
                                                        <td colspan="6" class="text-center py-5 text-muted">
                                                            <i class="fa-solid fa-inbox mb-2 text-indigo d-block" style="font-size:32px; opacity:0.4;"></i>
                                                            Belum ada nota di temporary list.<br>Upload nota di sebelah kiri & klik <strong>"Masukkan ke Temporary List"</strong>.
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Submit Master Batch Form -->
                                        <div class="p-3 border-top bg-light" id="batch-submit-box">
                                            <div class="form-group mb-2">
                                                <label class="font-weight-bold text-dark" style="font-size:11px;">Nama Laporan / Periode Pengajuan Klaim Batch</label>
                                                <input type="text" class="form-control form-control-sm font-weight-bold" id="input-batch-title" value="Klaim Operasional Mingguan - <?php echo date('d M Y'); ?>">
                                            </div>
                                            <button type="button" class="btn btn-emerald btn-block font-weight-bold py-2.5" id="btn-submit-batch" style="border-radius: 10px; background: #10B981; color:#fff; border:none; font-size:14px;" disabled>
                                                <i class="fa-solid fa-paper-plane mr-2"></i> Kirim Pengajuan Klaim Batch (<span id="btn-batch-total">Rp 0</span>)
                                            </button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SUB-TAB 2: DAFTAR & RIWAYAT REIMBURSEMENT -->
                    <div class="reimb-subtab-content d-none" id="reimb-subtab-list">
                        <div class="card-panel">
                            <div class="card-head d-flex align-items-center justify-content-between">
                                <div class="card-title"><i class="fa-solid fa-clock-rotate-left text-indigo"></i> Daftar & Riwayat Pengajuan Laporan Reimbursement</div>
                                <span class="badge badge-secondary px-3 py-1" style="border-radius: 20px; font-size:11px;"><?php echo count($my_reimbursement_batches); ?> Laporan Pengajuan</span>
                            </div>
                            <div class="card-body-inner p-0">
                                <div class="table-responsive">
                                    <table class="tbl-clean">
                                        <thead>
                                            <tr>
                                                <th>Kode & Tanggal</th>
                                                <th>Nama Laporan Batch</th>
                                                <th>Jumlah Nota</th>
                                                <th>Grand Total</th>
                                                <th>Status Approval</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(!empty($my_reimbursement_batches)): ?>
                                                <?php foreach($my_reimbursement_batches as $b): ?>
                                                    <tr>
                                                        <td>
                                                            <strong class="text-indigo d-block" style="font-size:11px;"><?php echo htmlspecialchars($b->batch_code); ?></strong>
                                                            <small class="text-muted"><?php echo date('d M Y H:i', strtotime($b->created_at)); ?></small>
                                                        </td>
                                                        <td>
                                                            <strong class="text-dark d-block"><?php echo htmlspecialchars($b->batch_title); ?></strong>
                                                            <?php if(!empty($b->items)): ?>
                                                                <small class="text-muted">
                                                                    Item: <?php 
                                                                        $names = array_map(function($i){ return $i->title; }, array_slice($b->items, 0, 2));
                                                                        echo htmlspecialchars(implode(', ', $names)) . (count($b->items) > 2 ? '...' : '');
                                                                    ?>
                                                                </small>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-light border text-dark font-weight-bold"><?php echo $b->total_items; ?> Nota</span>
                                                        </td>
                                                        <td>
                                                            <span class="font-weight-bold text-success" style="font-size:14px;">Rp <?php echo number_format($b->grand_total, 0, ',', '.'); ?></span>
                                                        </td>
                                                        <td>
                                                            <?php if($b->status == 'APPROVED'): ?>
                                                                <span class="badge badge-success px-3 py-1.5" style="border-radius:20px; font-size:10px;">APPROVED</span>
                                                            <?php elseif($b->status == 'REJECTED'): ?>
                                                                <span class="badge badge-danger px-3 py-1.5" style="border-radius:20px; font-size:10px;">REJECTED</span>
                                                            <?php else: ?>
                                                                <span class="badge badge-warning px-3 py-1.5" style="border-radius:20px; font-size:10px;">PENDING</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <button type="button" class="btn btn-xs btn-indigo btn-view-batch-detail px-2.5 py-1.5" data-batch='<?php echo json_encode($b); ?>' style="border-radius:6px; font-size:11px; background:#6366F1; color:#fff; border:none;">
                                                                <i class="fa-solid fa-eye mr-1"></i> Lihat Detail
                                                            </button>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="6" class="text-center py-5 text-muted">
                                                        <i class="fa-solid fa-receipt text-indigo mb-2 d-block" style="font-size:32px; opacity:0.3;"></i>
                                                        Belum ada riwayat pengajuan reimbursement.
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- ======================================
                     TAB: NOTICE BOARD & HISTORY (PENGUMUMAN RESMI)
                     ====================================== -->
                <div class="portal-tab" id="tab-notices">
                    
                    <!-- Top Executive Banner -->
                    <div class="card-panel mb-4" style="background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%); color: #FFFFFF; border: none; border-radius: 14px;">
                        <div class="card-body-inner p-4">
                            <div class="row align-items-center">
                                <div class="col-md-8 mb-3 mb-md-0">
                                    <div class="d-flex align-items-center mb-2" style="gap: 8px;">
                                        <span class="badge badge-warning px-3 py-1 font-weight-bold" style="font-size: 11px; background: #F59E0B; color: #1E293B; border-radius: 20px;">
                                            <i class="fa-solid fa-shield-check mr-1"></i> Official Digital Notice Board
                                        </span>
                                        <?php if(isset($pending_notices_count) && $pending_notices_count > 0): ?>
                                            <span class="badge badge-danger px-3 py-1 font-weight-bold" style="font-size: 11px; border-radius: 20px;">
                                                <i class="fa-solid fa-triangle-exclamation mr-1"></i> <?php echo $pending_notices_count; ?> Belum Tanda Tangan
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-success px-3 py-1 font-weight-bold" style="font-size: 11px; border-radius: 20px;">
                                                <i class="fa-solid fa-circle-check mr-1"></i> Semua Pengumuman Telah Tertanda
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <h3 class="font-weight-bold text-white mb-1" style="font-size: 22px;">Arsip & Riwayat Pengumuman Resmi</h3>
                                    <p class="text-white-50 mb-0" style="font-size: 13px;">
                                        Daftar seluruh surat edaran manajemen, pengumuman libur bersama, serta catatan verifikasi tanda tangan digital karyawan berbasis PIN.
                                    </p>
                                </div>
                                <div class="col-md-4 text-md-right">
                                    <button type="button" class="btn btn-outline-light px-3 py-2 rounded-lg font-weight-bold shadow-sm" onclick="openChangePinModal();" style="font-size: 12px;">
                                        <i class="fa-solid fa-key mr-1.5 text-warning"></i> Atur / Ubah PIN Saya
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filter & Search Controls -->
                    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4 pb-2" style="gap: 12px;">
                        <div class="btn-group" role="group" id="notice-tab-filters">
                            <button type="button" class="btn btn-primary font-weight-bold px-3 py-2 notice-filter-btn active" data-filter="all" style="border-radius: 10px 0 0 10px; font-size: 12px;">
                                Semua Pengumuman (<?php echo isset($all_notices) ? count($all_notices) : 0; ?>)
                            </button>
                            <button type="button" class="btn btn-outline-secondary font-weight-bold px-3 py-2 notice-filter-btn" data-filter="pending" style="font-size: 12px;">
                                <i class="fa-solid fa-clock text-danger mr-1"></i> Perlu TTD (<?php echo isset($pending_notices_count) ? $pending_notices_count : 0; ?>)
                            </button>
                            <button type="button" class="btn btn-outline-secondary font-weight-bold px-3 py-2 notice-filter-btn" data-filter="signed" style="border-radius: 0 10px 10px 0; font-size: 12px;">
                                <i class="fa-solid fa-check text-success mr-1"></i> Sudah TTD (<?php echo isset($all_notices) ? (count($all_notices) - (isset($pending_notices_count) ? $pending_notices_count : 0)) : 0; ?>)
                            </button>
                        </div>
                        <div style="min-width: 260px; max-width: 360px;" class="flex-grow-1">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white border-right-0" style="border-radius: 10px 0 0 10px;"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                                </div>
                                <input type="text" id="notice-history-search" class="form-control border-left-0" placeholder="Cari judul atau isi edaran..." style="border-radius: 0 10px 10px 0; font-size: 12.5px;">
                            </div>
                        </div>
                    </div>

                    <!-- Notice Grid Cards -->
                    <div class="row" id="notice-history-container">
                        <?php if(!empty($all_notices)): ?>
                            <?php foreach($all_notices as $notc): ?>
                                <?php 
                                    $is_signed = !empty($notc->signed_at);
                                    $is_mand = !empty($notc->is_mandatory);
                                    $ext = !empty($notc->file_url) ? strtolower(pathinfo($notc->file_url, PATHINFO_EXTENSION)) : '';
                                    $has_img = in_array($ext, ['png', 'jpg', 'jpeg', 'gif', 'webp']);
                                    $filter_status = ($is_mand && !$is_signed) ? 'pending' : ($is_signed ? 'signed' : 'info');

                                    $js_title = htmlspecialchars(json_encode($notc->title), ENT_QUOTES, 'UTF-8');
                                    $js_desc = htmlspecialchars(json_encode(!empty($notc->description) ? $notc->description : 'Tidak ada rincian teks tambahan.'), ENT_QUOTES, 'UTF-8');
                                    $js_date = htmlspecialchars(json_encode(date('d F Y', strtotime($notc->date))), ENT_QUOTES, 'UTF-8');
                                    $js_file = htmlspecialchars(json_encode(!empty($notc->file_url) ? base_url('assets/images/notice/'.$notc->file_url) : ''), ENT_QUOTES, 'UTF-8');
                                    $js_signed = $is_signed ? 1 : 0;
                                    $js_signedat = htmlspecialchars(json_encode($is_signed ? date('d M Y, H:i', strtotime($notc->signed_at)).' WIB' : ''), ENT_QUOTES, 'UTF-8');
                                ?>
                                <div class="col-lg-6 mb-4 notice-history-card" data-status="<?php echo $filter_status; ?>" data-title="<?php echo strtolower(htmlspecialchars($notc->title.' '.(!empty($notc->description)?$notc->description:''))); ?>">
                                    <div class="card-panel h-100 d-flex flex-column" style="border: 1px solid #E2E8F0; border-radius: 14px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); overflow: hidden;">
                                        
                                        <!-- Card Image Preview Banner -->
                                        <?php if($has_img): ?>
                                            <div style="height: 180px; overflow: hidden; position: relative; background: #F1F5F9; cursor: pointer;" onclick="openNoticeSignModal(<?php echo $notc->id; ?>, <?php echo $js_title; ?>, <?php echo $js_desc; ?>, <?php echo $js_date; ?>, <?php echo $js_file; ?>, <?php echo $js_signed; ?>, <?php echo $js_signedat; ?>);">
                                                <img src="<?php echo base_url('assets/images/notice/'.$notc->file_url); ?>" alt="Dokumen" style="width: 100%; height: 100%; object-fit: cover; object-position: top; transition: transform 0.3s ease;">
                                                <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, transparent 40%, rgba(15,23,42,0.7) 100%);"></div>
                                                <div style="position: absolute; bottom: 10px; right: 12px; font-size: 11px; background: rgba(15,23,42,0.85); color: #fff; padding: 3px 8px; border-radius: 6px;">
                                                    <i class="fa-solid fa-magnifying-glass-plus mr-1"></i> Klik Perbesar
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <div class="card-body-inner p-4 d-flex flex-column flex-grow-1">
                                            
                                            <!-- Meta Badges -->
                                            <div class="d-flex align-items-center justify-content-between mb-2 pb-1">
                                                <div class="d-flex align-items-center" style="gap: 6px;">
                                                    <span class="badge badge-light border text-muted px-2 py-1 font-weight-bold" style="font-size: 11px;">
                                                        <i class="fa-regular fa-calendar mr-1"></i> <?php echo date('d M Y', strtotime($notc->date)); ?>
                                                    </span>
                                                    <?php if($is_mand): ?>
                                                        <span class="badge badge-danger px-2 py-1 font-weight-bold" style="font-size: 10px; background:#FEF2F2; color:#DC2626; border:1px solid #FECACA;">
                                                            <i class="fa-solid fa-file-signature mr-1"></i> Wajib TTD
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="badge badge-secondary px-2 py-1 font-weight-bold" style="font-size: 10px;">
                                                            Informasi
                                                        </span>
                                                    <?php endif; ?>
                                                </div>

                                                <!-- Status TTD Badge -->
                                                <div>
                                                    <?php if($is_signed): ?>
                                                        <span class="badge badge-success px-2.5 py-1 font-weight-bold" style="font-size: 11px; background:#ECFDF5; color:#059669; border:1px solid #A7F3D0;">
                                                            <i class="fa-solid fa-circle-check mr-1"></i> Tertanda Digital
                                                        </span>
                                                    <?php elseif($is_mand): ?>
                                                        <span class="badge badge-warning px-2.5 py-1 font-weight-bold" style="font-size: 11px; background:#FFFBEB; color:#D97706; border:1px solid #FDE68A;">
                                                            <i class="fa-solid fa-pen-nib mr-1"></i> Belum Tanda Tangan
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                            <!-- Title & Description -->
                                            <h4 class="font-weight-bold text-dark mb-2" style="font-size: 16px; line-height: 1.4;">
                                                <?php echo $notc->title; ?>
                                            </h4>

                                            <p class="text-secondary mb-3 flex-grow-1" style="font-size: 12.5px; line-height: 1.5;">
                                                <?php 
                                                    if(!empty($notc->description)) {
                                                        echo (strlen($notc->description) > 160) ? substr($notc->description, 0, 160).'...' : $notc->description;
                                                    } else {
                                                        echo 'Surat edaran resmi. Silakan buka dokumen untuk membaca rincian lengkap.';
                                                    }
                                                ?>
                                            </p>

                                            <!-- Card Bottom Action & Audit Info -->
                                            <div class="pt-3 border-top mt-auto d-flex align-items-center justify-content-between" style="border-color: #E2E8F0 !important;">
                                                <div>
                                                    <?php if($is_signed): ?>
                                                        <small class="text-success font-weight-bold d-block" style="font-size: 11px;">
                                                            <i class="fa-solid fa-check-double mr-1"></i> <?php echo date('d M Y, H:i', strtotime($notc->signed_at)); ?> WIB
                                                        </small>
                                                    <?php elseif($is_mand): ?>
                                                        <small class="text-danger font-weight-bold d-block" style="font-size: 11px;">
                                                            <i class="fa-solid fa-lock mr-1"></i> Perlu verifikasi PIN
                                                        </small>
                                                    <?php else: ?>
                                                        <small class="text-muted d-block" style="font-size: 11px;">Pengumuman umum</small>
                                                    <?php endif; ?>
                                                </div>

                                                <div>
                                                    <?php if(!$is_signed && $is_mand): ?>
                                                        <button type="button" class="btn btn-primary btn-sm px-3 py-1.5 font-weight-bold rounded-lg shadow-sm"
                                                            onclick="openNoticeSignModal(<?php echo $notc->id; ?>, <?php echo $js_title; ?>, <?php echo $js_desc; ?>, <?php echo $js_date; ?>, <?php echo $js_file; ?>, <?php echo $js_signed; ?>, <?php echo $js_signedat; ?>);"
                                                            style="background: #4F46E5; border: none; font-size: 12px;">
                                                            <i class="fa-solid fa-pen-nib mr-1.5"></i> Baca & TTD (PIN)
                                                        </button>
                                                    <?php else: ?>
                                                        <button type="button" class="btn btn-outline-secondary btn-sm px-3 py-1.5 font-weight-bold rounded-lg"
                                                            onclick="openNoticeSignModal(<?php echo $notc->id; ?>, <?php echo $js_title; ?>, <?php echo $js_desc; ?>, <?php echo $js_date; ?>, <?php echo $js_file; ?>, <?php echo $js_signed; ?>, <?php echo $js_signedat; ?>);"
                                                            style="font-size: 12px;">
                                                            <i class="fa-solid fa-file-lines mr-1.5 text-primary"></i> Buka Dokumen Lengkap
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-12 py-5 text-center text-muted">
                                <i class="fa-solid fa-bullhorn mb-3" style="font-size: 40px; opacity: 0.3;"></i>
                                <h5 class="font-weight-bold text-dark">Belum Ada Pengumuman</h5>
                                <p class="small">Semua pengumuman dan surat edaran dari HRD akan tercatat rapi di sini.</p>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>

            </div>
        </div>

    </div>

    <!-- ==========================================
         MODALS
         ========================================== -->

    <!-- Calendar Detail Modal -->
    <div class="modal fade" id="calendarDetailModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content rounded-lg border-0 shadow-lg">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title font-weight-bold text-dark" id="modal-date-title">Detail Tanggal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body py-4">
                    <div class="p-3 mb-3 rounded-lg bg-danger text-white d-none" id="modal-holiday-banner">
                        <i class="fa-solid fa-calendar-minus mr-2"></i> <strong id="modal-holiday-name">Hari Libur Nasional</strong>
                    </div>
                    <div class="p-3 mb-3 rounded-lg bg-primary text-white d-none" id="modal-event-banner">
                        <i class="fa-solid fa-bullhorn mr-2"></i> <strong id="modal-event-title">Company Event</strong>
                    </div>
                    <div class="p-3 rounded-lg bg-light border">
                        <h6 class="font-weight-bold text-dark mb-2"><i class="fa-regular fa-clock text-danger mr-1"></i> Rekam Presensi</h6>
                        <div class="row text-center my-3">
                            <div class="col-6">
                                <small class="text-muted font-weight-bold d-block">JAM MASUK</small>
                                <span class="h5 font-weight-bold text-success" id="modal-signin-time">--:--</span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted font-weight-bold d-block">JAM KELUAR</small>
                                <span class="h5 font-weight-bold text-danger" id="modal-signout-time">--:--</span>
                            </div>
                        </div>
                        <small class="text-muted d-block" id="modal-verification-status">
                            <i class="fa-solid fa-shield-check text-success mr-1"></i> Verifikasi AI Face & Geofence
                        </small>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-secondary btn-sm px-4 rounded-pill" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Web Face Registration Modal -->
    <!-- Web Face Registration Modal (3D Multi-Angle Biometric Window) -->
    <div class="modal fade" id="webFaceRegisterModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document" style="max-width: 980px;">
            <div class="modal-content border-0 shadow-2xl" style="border-radius: 20px; overflow: hidden; background: #0f172a; color: #e2e8f0;">
                
                <!-- Modal Header -->
                <div class="modal-header border-0 px-4 pt-4 pb-2 d-flex align-items-center justify-content-between" style="background: #0f172a;">
                    <div>
                        <div class="d-flex align-items-center mb-1">
                            <span class="badge badge-pill badge-danger px-3 py-1 font-weight-bold mr-2" style="font-size: 11px; letter-spacing: 0.5px;">
                                <i class="fa-solid fa-cube mr-1"></i> 3D AI BIOMETRIK
                            </span>
                            <span class="text-white-50" style="font-size: 12px;"><i class="fa-solid fa-shield-halved text-success mr-1"></i> MediaPipe 468-Landmark Geometri Presisi (Bebas Halusinasi)</span>
                        </div>
                        <h4 class="modal-title font-weight-bold text-white mb-0" style="font-size: 20px;">
                            Registrasi Biometrik Wajah 3D Multi-Sudut
                        </h4>
                    </div>
                    <button type="button" class="close text-white-50" onclick="stopWebFaceRegistration();" data-dismiss="modal" aria-label="Close" style="opacity: 0.8; font-size: 28px;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body px-4 py-3" style="background: #0f172a;">
                    <div class="row">
                        
                        <!-- Kolom Kiri: Kamera Viewport HD & 3D HUD Overlay -->
                        <div class="col-lg-7 mb-3 mb-lg-0">
                            <div class="position-relative overflow-hidden shadow-inner d-flex align-items-center justify-content-center" style="background: #020617; border: 2px solid #1e293b; height: 440px; border-radius: 16px;">
                                
                                <!-- Video Stream -->
                                <video id="reg-webcam-feed" autoplay playsinline muted style="width: 100%; height: 100%; object-fit: cover; transform: scaleX(-1);"></video>
                                
                                <!-- Canvas Jaring 3D FaceMesh -->
                                <canvas id="reg-mesh-canvas" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 2;"></canvas>
                                <canvas id="reg-canvas-snap" style="display: none;"></canvas>

                                <!-- Flash Shutter Effect -->
                                <div id="reg-flash-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: #ffffff; opacity: 0; pointer-events: none; z-index: 15; transition: opacity 0.15s ease-out;"></div>

                                <!-- Target Oval & HUD Overlay -->
                                <div class="position-absolute" style="top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 3; display: flex; flex-direction: column; align-items: center; justify-content: space-between; padding: 16px;">
                                    
                                    <!-- Badge Status Atas -->
                                    <div class="d-flex justify-content-between align-items-center w-100">
                                        <span class="badge badge-dark px-3 py-1 text-white font-weight-normal" style="background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.1); font-size: 11px;">
                                            <i class="fa-solid fa-camera mr-1 text-info"></i> Kamera HD Widescreen
                                        </span>
                                        <span id="reg-angle-compass" class="badge badge-primary px-3 py-1 font-weight-bold" style="background: rgba(59, 130, 246, 0.25); border: 1px solid #3b82f6; color: #93c5fd; font-size: 11px;">
                                            🎯 Sudut Target: Depan
                                        </span>
                                    </div>

                                    <!-- Oval Frame Guide -->
                                    <div id="reg-oval-guide" style="width: 220px; height: 280px; border-radius: 50%; border: 2.5px dashed rgba(59, 130, 246, 0.7); box-shadow: 0 0 25px rgba(59, 130, 246, 0.25); transition: all 0.3s ease; position: relative;">
                                        <!-- Crosshairs -->
                                        <div style="position: absolute; top: -10px; left: 50%; transform: translateX(-50%); width: 3px; height: 12px; background: #3b82f6; border-radius: 2px;"></div>
                                        <div style="position: absolute; bottom: -10px; left: 50%; transform: translateX(-50%); width: 3px; height: 12px; background: #3b82f6; border-radius: 2px;"></div>
                                        <div style="position: absolute; left: -10px; top: 50%; transform: translateY(-50%); width: 12px; height: 3px; background: #3b82f6; border-radius: 2px;"></div>
                                        <div style="position: absolute; right: -10px; top: 50%; transform: translateY(-50%); width: 12px; height: 3px; background: #3b82f6; border-radius: 2px;"></div>
                                    </div>

                                    <!-- Dynamic Instruction Badge di Bawah Video -->
                                    <div id="reg-pose-guide" class="text-center px-4 py-2 rounded-pill font-weight-bold shadow-lg" style="background: rgba(15, 23, 42, 0.9); backdrop-filter: blur(10px); border: 1px solid rgba(59, 130, 246, 0.5); color: #60a5fa; font-size: 13px; max-width: 95%;">
                                        <i class="fa-solid fa-spinner fa-spin mr-2"></i> Menginisialisasi kamera 3D...
                                    </div>
                                </div>

                            </div>
                            
                            <div class="d-flex align-items-center justify-content-between mt-2 px-1">
                                <small class="text-white-50" style="font-size: 11px;">
                                    <i class="fa-solid fa-circle-info text-primary mr-1"></i> Posisikan wajah di dalam oval dan ikuti instruksi 5 arah.
                                </small>
                                <button type="button" class="btn btn-link btn-sm text-info p-0 font-weight-bold" style="font-size: 11px; text-decoration: none;" onclick="manualCaptureCurrentStep();">
                                    <i class="fa-solid fa-camera mr-1"></i> Tangkap Sudut Ini Manual
                                </button>
                            </div>
                        </div>

                        <!-- Kolom Kanan: Checklist 5 Sudut & Kontrol Otorisasi -->
                        <div class="col-lg-5 d-flex flex-column justify-content-between">
                            
                            <!-- Progress Bar Section -->
                            <div class="p-3 rounded-lg mb-2" style="background: #1e293b; border: 1px solid #334155; border-radius: 14px;">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="font-weight-bold text-white" style="font-size: 13px;">Status Pemindaian 3D</span>
                                    <span id="reg-progress-pct" class="font-weight-bold text-info" style="font-size: 13px;">0% (0 / 5 Sudut)</span>
                                </div>
                                <div class="progress rounded-pill" style="height: 10px; background: #0f172a;">
                                    <div id="reg-progress-bar" class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar" style="width: 0%;"></div>
                                </div>
                            </div>

                            <!-- 5 Steps Checklist -->
                            <div class="steps-container flex-grow-1 mb-3" style="display: flex; flex-direction: column; gap: 7px;">
                                
                                <!-- Step 1: Depan -->
                                <div class="step-card d-flex align-items-center p-2 rounded-lg" id="step-row-center" style="background: #1e293b; border: 1.5px solid #3b82f6; border-radius: 12px; transition: all 0.3s;">
                                    <div class="step-icon-box rounded-circle d-flex align-items-center justify-content-center mr-2" style="width: 34px; height: 34px; background: rgba(59, 130, 246, 0.2); color: #60a5fa; font-size: 14px;">
                                        <i class="fa-solid fa-user"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="font-weight-bold text-white" style="font-size: 12px;">1. Hadap Depan (Netral)</div>
                                        <small class="text-white-50" style="font-size: 10.5px;">Wajah lurus ke arah kamera</small>
                                    </div>
                                    <div id="step-badge-center">
                                        <span class="badge badge-primary px-2 py-1 font-weight-bold" style="font-size: 10px;"><i class="fa-solid fa-arrows-rotate fa-spin mr-1"></i> Memindai</span>
                                    </div>
                                </div>

                                <!-- Step 2: Kiri -->
                                <div class="step-card d-flex align-items-center p-2 rounded-lg" id="step-row-left" style="background: #1e293b; border: 1px solid #334155; border-radius: 12px; opacity: 0.7; transition: all 0.3s;">
                                    <div class="step-icon-box rounded-circle d-flex align-items-center justify-content-center mr-2" style="width: 34px; height: 34px; background: rgba(148, 163, 184, 0.1); color: #94a3b8; font-size: 14px;">
                                        <i class="fa-solid fa-arrow-left"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="font-weight-bold text-white" style="font-size: 12px;">2. Menoleh ke Kiri</div>
                                        <small class="text-white-50" style="font-size: 10.5px;">Tengok perlahan ke kiri Anda ~15°</small>
                                    </div>
                                    <div id="step-badge-left">
                                        <span class="badge badge-secondary px-2 py-1" style="font-size: 10px; background: #334155;">Menunggu</span>
                                    </div>
                                </div>

                                <!-- Step 3: Kanan -->
                                <div class="step-card d-flex align-items-center p-2 rounded-lg" id="step-row-right" style="background: #1e293b; border: 1px solid #334155; border-radius: 12px; opacity: 0.7; transition: all 0.3s;">
                                    <div class="step-icon-box rounded-circle d-flex align-items-center justify-content-center mr-2" style="width: 34px; height: 34px; background: rgba(148, 163, 184, 0.1); color: #94a3b8; font-size: 14px;">
                                        <i class="fa-solid fa-arrow-right"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="font-weight-bold text-white" style="font-size: 12px;">3. Menoleh ke Kanan</div>
                                        <small class="text-white-50" style="font-size: 10.5px;">Tengok perlahan ke kanan Anda ~15°</small>
                                    </div>
                                    <div id="step-badge-right">
                                        <span class="badge badge-secondary px-2 py-1" style="font-size: 10px; background: #334155;">Menunggu</span>
                                    </div>
                                </div>

                                <!-- Step 4: Atas -->
                                <div class="step-card d-flex align-items-center p-2 rounded-lg" id="step-row-up" style="background: #1e293b; border: 1px solid #334155; border-radius: 12px; opacity: 0.7; transition: all 0.3s;">
                                    <div class="step-icon-box rounded-circle d-flex align-items-center justify-content-center mr-2" style="width: 34px; height: 34px; background: rgba(148, 163, 184, 0.1); color: #94a3b8; font-size: 14px;">
                                        <i class="fa-solid fa-arrow-up"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="font-weight-bold text-white" style="font-size: 12px;">4. Angkat Dagu / Atas</div>
                                        <small class="text-white-50" style="font-size: 10.5px;">Dongakkan kepala sedikit ~10°</small>
                                    </div>
                                    <div id="step-badge-up">
                                        <span class="badge badge-secondary px-2 py-1" style="font-size: 10px; background: #334155;">Menunggu</span>
                                    </div>
                                </div>

                                <!-- Step 5: Bawah -->
                                <div class="step-card d-flex align-items-center p-2 rounded-lg" id="step-row-down" style="background: #1e293b; border: 1px solid #334155; border-radius: 12px; opacity: 0.7; transition: all 0.3s;">
                                    <div class="step-icon-box rounded-circle d-flex align-items-center justify-content-center mr-2" style="width: 34px; height: 34px; background: rgba(148, 163, 184, 0.1); color: #94a3b8; font-size: 14px;">
                                        <i class="fa-solid fa-arrow-down"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="font-weight-bold text-white" style="font-size: 12px;">5. Menunduk / Bawah</div>
                                        <small class="text-white-50" style="font-size: 10.5px;">Tundukkan kepala sedikit ~10°</small>
                                    </div>
                                    <div id="step-badge-down">
                                        <span class="badge badge-secondary px-2 py-1" style="font-size: 10px; background: #334155;">Menunggu</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Bottom Authorization & Actions -->
                            <div class="pt-2 border-top" style="border-color: #334155 !important;">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <label class="font-weight-bold text-white mb-0" style="font-size: 12px;">
                                        <i class="fa-solid fa-lock text-warning mr-1"></i> PIN Otorisasi
                                    </label>
                                    <input type="password" id="reg-pin-input" class="form-control form-control-sm text-center font-weight-bold bg-dark text-white border-secondary" maxlength="4" placeholder="1234" value="1234" style="width: 90px; font-size: 14px; letter-spacing: 2px;">
                                </div>

                                <div class="d-flex" style="gap: 8px;">
                                    <button type="button" class="btn btn-outline-secondary font-weight-bold px-3 py-2 rounded-lg text-white-50" id="btn-reset-3d-scan" onclick="reset3DScan();" title="Mulai ulang semua sudut">
                                        <i class="fa-solid fa-arrow-rotate-left"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger flex-grow-1 py-2 font-weight-bold rounded-lg shadow-sm" id="btn-save-web-face" onclick="captureAndSaveWebFace();" disabled>
                                        <i class="fa-solid fa-check-double mr-1"></i> Simpan Biometrik 3D
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Already Registered Modal -->
    <div class="modal fade" id="alreadyRegisteredModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content rounded-lg border-0 shadow-lg text-center p-4">
                <div class="mb-3">
                    <div class="d-inline-flex align-items-center justify-content-center bg-success text-white rounded-circle shadow-sm" style="width: 70px; height: 70px; font-size: 32px;">
                        <i class="fa-solid fa-shield-check"></i>
                    </div>
                </div>
                <h4 class="font-weight-bold text-dark mb-2">Biometrik Wajah Sudah Terdaftar</h4>
                <p class="text-muted mb-3" style="font-size: 14px; line-height: 1.6;">
                    Data biometrik 512D Anda sudah terdaftar di database HRMS. Anda dapat langsung melakukan presensi touchless di terminal presensi kantor.
                </p>
                <div class="p-3 rounded-lg mb-3" style="background: rgba(234, 179, 8, 0.1); border: 1px solid rgba(234, 179, 8, 0.3); font-size: 13px; color: #B45309;">
                    <i class="fa-solid fa-circle-info mr-1"></i> Jika ingin <strong>reset data wajah</strong>, hubungi <strong>Admin HR</strong>.
                </div>
                <div>
                    <button type="button" class="btn btn-secondary px-4 py-2 font-weight-bold rounded-pill" data-dismiss="modal">Mengerti / Tutup</button>
                </div>
            </div>
        </div>
    <!-- Reimbursement Batch Detail & Timeline Modal -->
    <div class="modal fade" id="reimbursementBatchDetailModal" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 1060;">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 18px; overflow: hidden;">
                <div class="modal-header px-4 py-3 border-0 text-white" style="background: linear-gradient(135deg, #0F172A, #1E293B);">
                    <h5 class="modal-title font-weight-bold text-white d-flex align-items-center" style="font-size:15px;">
                        <i class="fa-solid fa-receipt text-cyan mr-2"></i> Rincian & Timeline Pengajuan Reimbursement
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity:0.8;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4 bg-light">
                    
                    <!-- Batch Header Summary Card -->
                    <div class="p-3 mb-4 rounded-lg bg-white shadow-sm border">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div>
                                <span class="badge badge-indigo px-2.5 py-1 mb-1" id="modal-batch-code" style="background:#6366F1; color:#fff; font-size:11px;">RB-20260811-0000</span>
                                <h5 class="font-weight-bold text-dark mb-0" id="modal-batch-title">Judul Laporan Batch</h5>
                            </div>
                            <div class="text-right">
                                <small class="text-muted d-block" style="font-size:10px;">Grand Total Rp</small>
                                <h4 class="font-weight-bold text-success mb-0" id="modal-batch-grand-total">Rp 0</h4>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between border-top pt-2 mt-2" style="font-size:11px;">
                            <span class="text-muted"><i class="fa-solid fa-calendar-days text-indigo mr-1"></i> Dikirim pada: <strong id="modal-batch-date" class="text-dark">-</strong></span>
                            <span class="text-muted"><i class="fa-solid fa-layer-group text-indigo mr-1"></i> Jumlah Item: <strong id="modal-batch-items-count" class="text-dark">0 Nota</strong></span>
                        </div>
                    </div>

                    <!-- TIMELINE STATUS PENGAJUAN (AUDIT TRAIL STEPPER) -->
                    <div class="card-panel mb-4 p-3 bg-white border shadow-sm">
                        <small class="font-weight-bold text-dark text-uppercase d-block mb-3" style="font-size:11px; letter-spacing:0.5px;">
                            <i class="fa-solid fa-timeline text-indigo mr-1"></i> Timeline Alur Persetujuan (Approval Trail)
                        </small>

                        <div class="timeline-stepper d-flex align-items-center justify-content-between position-relative px-2 py-3" style="background: rgba(241,245,249,0.6); border-radius: 12px;">
                            
                            <!-- Step 1: Submitting -->
                            <div class="text-center position-relative z-index-1 flex-fill">
                                <div class="timeline-icon mx-auto mb-2 rounded-circle bg-success text-white d-flex align-items-center justify-content-center" style="width:36px; height:36px; font-size:14px; margin:0 auto;">
                                    <i class="fa-solid fa-paper-plane"></i>
                                </div>
                                <strong class="d-block text-dark" style="font-size:11px;">1. Pengajuan Dikirim</strong>
                                <small class="text-success font-weight-bold" style="font-size:10px;" id="timeline-step1-time">Selesai</small>
                            </div>

                            <div class="timeline-line flex-fill border-top border-2 border-primary" style="height:2px; margin-top:-20px;"></div>

                            <!-- Step 2: Verification -->
                            <div class="text-center position-relative z-index-1 flex-fill">
                                <div class="timeline-icon mx-auto mb-2 rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" id="timeline-step2-icon" style="width:36px; height:36px; font-size:14px; margin:0 auto;">
                                    <i class="fa-solid fa-user-check"></i>
                                </div>
                                <strong class="d-block text-dark" style="font-size:11px;">2. Verifikasi Finance / HR</strong>
                                <small class="font-weight-bold" style="font-size:10px;" id="timeline-step2-status">Verifikasi Nota</small>
                            </div>

                            <div class="timeline-line flex-fill border-top border-2" style="height:2px; margin-top:-20px;"></div>

                            <!-- Step 3: Final Approval / Disbursement -->
                            <div class="text-center position-relative z-index-1 flex-fill">
                                <div class="timeline-icon mx-auto mb-2 rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" id="timeline-step3-icon" style="width:36px; height:36px; font-size:14px; margin:0 auto;">
                                    <i class="fa-solid fa-money-bill-wave"></i>
                                </div>
                                <strong class="d-block text-dark" style="font-size:11px;">3. Persetujuan & Pencairan</strong>
                                <small class="font-weight-bold text-muted" style="font-size:10px;" id="timeline-step3-status">Menunggu</small>
                            </div>

                        </div>
                    </div>

                    <!-- Itemized Receipts Table -->
                    <div class="card-panel bg-white border shadow-sm">
                        <div class="card-head py-2.5 px-3 bg-light border-bottom">
                            <small class="font-weight-bold text-dark"><i class="fa-solid fa-list-check text-indigo mr-1"></i> Rincian Struk & Bukti Nota Pembayaran</small>
                        </div>
                        <div class="table-responsive">
                            <table class="tbl-clean" id="modal-items-table">
                                <thead>
                                    <tr>
                                        <th style="width:30px;">#</th>
                                        <th style="width:60px;">Bukti</th>
                                        <th>Judul & Merchant</th>
                                        <th>Kategori</th>
                                        <th class="text-right">Nominal</th>
                                    </tr>
                                </thead>
                                <tbody id="modal-tbody-items">
                                    <!-- Dynamic rows loaded via JS -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
                <div class="modal-footer px-4 py-3 bg-white">
                    <button type="button" class="btn btn-secondary px-4 py-2 font-weight-bold rounded-pill" data-dismiss="modal" style="font-size:12px;">Tutup Window</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ==========================================
         MODAL DIGITAL PIN SIGNATURE UNTUK NOTICE BOARD
         ========================================== -->
    <div class="modal fade" id="noticeSignatureModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 640px;">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                <!-- Header -->
                <div class="modal-header text-white" style="background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%); border: none; padding: 18px 22px;">
                    <div>
                        <div class="d-flex align-items-center mb-1">
                            <span class="badge badge-pill px-2 py-0 mr-2" style="background: rgba(99,102,241,0.25); border: 1px solid rgba(99,102,241,0.5); color: #C7D2FE; font-size: 9.5px;">
                                <i class="fa-solid fa-file-signature mr-1"></i> SURAT EDARAN RESMI
                            </span>
                            <span id="modal-notice-date" style="font-size: 11.5px; color: #94A3B8;"></span>
                        </div>
                        <h5 class="modal-title font-weight-bold" id="modal-notice-title" style="font-size: 16px; color: #F8FAFC; line-height: 1.3;"></h5>
                    </div>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.8;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Body -->
                <div class="modal-body p-4" style="background: #F8FAFC;">
                    <!-- Isi Pengumuman & Teks Lengkap -->
                    <div class="p-3 bg-white rounded-lg border mb-3" style="border-color: #E2E8F0 !important;">
                        <div class="text-muted font-weight-bold mb-1" style="font-size: 10.5px; text-transform: uppercase; letter-spacing: 0.5px;">
                            <i class="fa-solid fa-align-left text-primary mr-1"></i> Isi Dokumen / Ketentuan Lengkap:
                        </div>
                        <div id="modal-notice-desc" style="font-size: 13px; line-height: 1.6; color: #1E293B; white-space: pre-line;"></div>
                        
                        <!-- Lampiran Gambar Surat Edaran (Full Preview) -->
                        <div id="modal-notice-img-box" class="mt-3 pt-3 border-top text-center" style="display: none;">
                            <div class="text-muted font-weight-bold mb-2 text-left" style="font-size: 11px; text-transform: uppercase;">
                                <i class="fa-solid fa-image text-primary mr-1"></i> Lampiran Gambar / Berkas Edaran:
                            </div>
                            <a id="modal-notice-img-link" href="#" target="_blank" title="Klik untuk membuka ukuran penuh / download">
                                <img id="modal-notice-img" src="" class="img-fluid rounded border shadow-sm" style="max-height: 480px; width: 100%; object-fit: contain; background: #FFFFFF;">
                            </a>
                            <div class="text-muted mt-1 font-weight-bold" style="font-size: 11px;">
                                <i class="fa-solid fa-magnifying-glass-plus text-primary mr-1"></i> Klik gambar di atas untuk membuka ukuran penuh / download
                            </div>
                        </div>

                        <!-- Lampiran Non-Gambar (PDF / Doc) -->
                        <div id="modal-notice-attachment" class="mt-3 pt-2 border-top" style="display: none;">
                            <a id="modal-notice-file-link" href="#" target="_blank" class="btn btn-sm btn-outline-secondary font-weight-bold rounded-pill" style="font-size: 11.5px;">
                                <i class="fa-solid fa-file-pdf text-danger mr-1"></i> Unduh / Buka Dokumen Asli
                            </a>
                        </div>
                    </div>

                    <!-- Already signed banner (if already signed) -->
                    <div id="modal-already-signed-box" class="p-3 rounded-lg text-center" style="background: #ECFDF5; border: 1px solid #A7F3D0; display: none;">
                        <div class="text-success font-weight-bold" style="font-size: 14px;">
                            <i class="fa-solid fa-circle-check mr-1"></i> Dokumen Ini Telah Anda Tandatangani
                        </div>
                        <div class="text-muted mt-1" style="font-size: 11.5px;" id="modal-signed-timestamp"></div>
                        <div class="mt-2">
                            <span class="badge badge-success px-3 py-1 font-weight-bold" style="font-size: 10px;">TERVERIFIKASI SAH DENGAN PIN</span>
                        </div>
                    </div>

                    <!-- Form Verifikasi PIN Tanda Tangan (if not signed) -->
                    <div id="modal-pin-sign-box" class="p-3 rounded-lg" style="background: #EEF2FF; border: 1px solid #C7D2FE;">
                        <div class="d-flex align-items-center mb-2">
                            <div style="width: 32px; height: 32px; border-radius: 8px; background: #4F46E5; color: white; display: flex; align-items: center; justify-content: center; font-size: 13px; margin-right: 10px;">
                                <i class="fa-solid fa-key"></i>
                            </div>
                            <div>
                                <div class="font-weight-bold" style="font-size: 12.5px; color: #1E1B4B;">Tanda Tangan Digital dengan PIN</div>
                                <div style="font-size: 11px; color: #4338CA;">Default PIN: <strong>1234</strong></div>
                            </div>
                        </div>

                        <p style="font-size: 11.5px; color: #475569; margin-bottom: 12px; line-height: 1.4;">
                            Dengan memasukkan PIN di bawah, saya menyatakan <strong>telah membaca, memahami, dan menyetujui</strong> seluruh isi edaran ini.
                        </p>

                        <form id="formSignNotice" onsubmit="submitNoticePin(event);">
                            <input type="hidden" id="sign-notice-id" name="notice_id" value="">
                            <div class="form-group mb-2">
                                <label class="font-weight-bold text-dark" style="font-size: 11px;">MASUKKAN 4-DIGIT PIN:</label>
                                <div class="input-group">
                                    <input type="password" id="sign-pin-input" name="pin" class="form-control text-center font-weight-bold" placeholder="• • • •" maxlength="6" autocomplete="off" required style="font-size: 18px; letter-spacing: 6px; border-radius: 8px 0 0 8px; border-color: #CBD5E1; background: #FFFFFF;">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePinVisibility();" style="border-color: #CBD5E1; border-radius: 0 8px 8px 0; background: #FFFFFF;">
                                            <i class="fa-solid fa-eye" id="pin-eye-icon"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div id="sign-error-msg" class="text-danger font-weight-bold mb-2" style="font-size: 11.5px; display: none;"></div>

                            <div class="d-flex align-items-center justify-content-between mt-3">
                                <button type="button" class="btn btn-light font-weight-bold text-muted rounded-pill px-3" data-dismiss="modal" style="font-size: 11.5px;">Batal</button>
                                <button type="submit" id="btn-submit-sign" class="btn btn-primary font-weight-bold rounded-pill px-4" style="font-size: 11.5px; background: #4F46E5; border: none; box-shadow: 0 4px 12px rgba(79,70,229,0.35);">
                                    <i class="fa-solid fa-check mr-1"></i> Setujui & Tanda Tangani
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ==========================================
         MODAL KEAMANAN AKUN (MANAJEMEN PIN & PASSWORD)
         ========================================== -->
    <style>
        #securityTab .nav-link {
            color: #64748B;
            background: #F1F5F9;
            border-radius: 10px;
            border: 1px solid #E2E8F0;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        #securityTab .nav-link.active {
            background: #0F172A !important;
            color: #FFFFFF !important;
            border-color: #0F172A !important;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.18);
        }
        #securityTab .nav-link:hover:not(.active) {
            background: #E2E8F0;
            color: #1E293B;
        }
    </style>
    <div class="modal fade" id="securityModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 440px;">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 18px; overflow: hidden; background: #FFFFFF;">
                <div class="modal-header text-white" style="background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%); padding: 18px 22px; border-bottom: 1px solid rgba(255,255,255,0.08);">
                    <div>
                        <h5 class="modal-title font-weight-bold mb-1" style="font-size: 15px; letter-spacing: -0.2px;">
                            <i class="fa-solid fa-shield-halved mr-2 text-primary"></i> Keamanan Akun & Kredensial
                        </h5>
                        <p class="text-white-50 mb-0" style="font-size: 11px;">Kelola PIN Konfirmasi Digital dan Password Akun Anda</p>
                    </div>
                    <button type="button" class="close text-white" data-dismiss="modal" style="opacity: 0.85;">&times;</button>
                </div>

                <!-- Navigation Tabs: PIN vs Password -->
                <div class="px-4 pt-3 pb-2" style="background: #F8FAFC; border-bottom: 1px solid #E2E8F0;">
                    <ul class="nav nav-pills nav-fill" id="securityTab" role="tablist" style="gap: 8px;">
                        <li class="nav-item">
                            <a class="nav-link active font-weight-bold py-2 px-3" id="sec-pin-tab" data-toggle="pill" href="#sec-tab-pin" role="tab" style="font-size: 12px;">
                                <i class="fa-solid fa-key mr-1.5 text-warning"></i> Ubah PIN (4-6 Digit)
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold py-2 px-3" id="sec-pass-tab" data-toggle="pill" href="#sec-tab-password" role="tab" style="font-size: 12px;">
                                <i class="fa-solid fa-lock mr-1.5 text-primary"></i> Ubah Password
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="tab-content" id="securityTabContent" style="background: #F8FAFC;">
                    <!-- TAB 1: PIN -->
                    <div class="tab-pane fade show active p-4" id="sec-tab-pin" role="tabpanel">
                        <form id="formUpdatePin" onsubmit="submitUpdatePin(event);">
                            <div class="alert alert-info py-2 px-3 mb-3 d-flex align-items-start" style="font-size: 11.5px; border-radius: 10px; background: #EFF6FF; border: 1px solid #BFDBFE; color: #1E40AF;">
                                <i class="fa-solid fa-circle-info mr-2 mt-0.5 text-primary"></i>
                                <div>
                                    PIN digunakan untuk <strong>Tanda Tangan Digital Flyer</strong> & <strong>Akses Registrasi Wajah</strong>. Default: <strong>1234</strong>.
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label style="font-size: 11.5px; font-weight: 600; color: #334155;">PIN Lama Saat Ini:</label>
                                <div class="input-group">
                                    <input type="password" id="old-pin-input" class="form-control text-center font-weight-bold" placeholder="• • • •" maxlength="6" required style="font-size: 16px; letter-spacing: 5px; border-radius: 10px 0 0 10px;">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary border-left-0" type="button" onclick="togglePassView('old-pin-input', this)" style="border-color: #ced4da; border-radius: 0 10px 10px 0;">
                                            <i class="fa-regular fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label style="font-size: 11.5px; font-weight: 600; color: #334155;">PIN Baru (4 - 6 Digit Angka):</label>
                                <div class="input-group">
                                    <input type="password" id="new-pin-input" class="form-control text-center font-weight-bold" placeholder="Contoh: 5678" maxlength="6" required style="font-size: 16px; letter-spacing: 5px; border-radius: 10px 0 0 10px;">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary border-left-0" type="button" onclick="togglePassView('new-pin-input', this)" style="border-color: #ced4da; border-radius: 0 10px 10px 0;">
                                            <i class="fa-regular fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <small class="text-muted mt-1 d-block" style="font-size: 10.5px;">Hanya masukkan 4 hingga 6 digit angka numerik (0-9).</small>
                            </div>
                            <div id="pin-change-feedback" class="font-weight-bold mb-3" style="font-size: 11.5px; display: none;"></div>
                            <div class="d-flex justify-content-end align-items-center mt-4" style="gap: 10px;">
                                <button type="button" class="btn btn-light rounded-pill px-3 font-weight-bold text-muted" data-dismiss="modal" style="font-size: 11.5px;">Batal</button>
                                <button type="submit" id="btn-save-pin" class="btn btn-primary rounded-pill px-4 font-weight-bold" style="font-size: 11.5px; background: #4F46E5; border: none; box-shadow: 0 4px 12px rgba(79,70,229,0.25);">
                                    <i class="fa-solid fa-floppy-disk mr-1"></i> Simpan PIN Baru
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- TAB 2: PASSWORD -->
                    <div class="tab-pane fade p-4" id="sec-tab-password" role="tabpanel">
                        <form id="formUpdatePassword" onsubmit="submitUpdatePassword(event);">
                            <div class="alert alert-warning py-2 px-3 mb-3 d-flex align-items-start" style="font-size: 11.5px; border-radius: 10px; background: #FFFBEB; border: 1px solid #FDE68A; color: #92400E;">
                                <i class="fa-solid fa-lock mr-2 mt-0.5 text-warning"></i>
                                <div>
                                    Ganti kata sandi login Anda. Minimal 6 karakter.
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label style="font-size: 11.5px; font-weight: 600; color: #334155;">Password Saat Ini (Lama):</label>
                                <div class="input-group">
                                    <input type="password" id="old-pass-input" class="form-control" placeholder="Masukkan password lama" required style="border-radius: 10px 0 0 10px; font-size: 12.5px;">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary border-left-0" type="button" onclick="togglePassView('old-pass-input', this)" style="border-color: #ced4da; border-radius: 0 10px 10px 0;">
                                            <i class="fa-regular fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label style="font-size: 11.5px; font-weight: 600; color: #334155;">Password Baru (Min. 6 Karakter):</label>
                                <div class="input-group">
                                    <input type="password" id="new-pass-input" class="form-control" placeholder="Masukkan password baru" minlength="6" required style="border-radius: 10px 0 0 10px; font-size: 12.5px;">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary border-left-0" type="button" onclick="togglePassView('new-pass-input', this)" style="border-color: #ced4da; border-radius: 0 10px 10px 0;">
                                            <i class="fa-regular fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label style="font-size: 11.5px; font-weight: 600; color: #334155;">Konfirmasi Password Baru:</label>
                                <div class="input-group">
                                    <input type="password" id="confirm-pass-input" class="form-control" placeholder="Ulangi password baru" minlength="6" required style="border-radius: 10px 0 0 10px; font-size: 12.5px;">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary border-left-0" type="button" onclick="togglePassView('confirm-pass-input', this)" style="border-color: #ced4da; border-radius: 0 10px 10px 0;">
                                            <i class="fa-regular fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div id="pass-change-feedback" class="font-weight-bold mb-3" style="font-size: 11.5px; display: none;"></div>
                            <div class="d-flex justify-content-end align-items-center mt-4" style="gap: 10px;">
                                <button type="button" class="btn btn-light rounded-pill px-3 font-weight-bold text-muted" data-dismiss="modal" style="font-size: 11.5px;">Batal</button>
                                <button type="submit" id="btn-save-pass" class="btn btn-primary rounded-pill px-4 font-weight-bold" style="font-size: 11.5px; background: #0F172A; border: none; box-shadow: 0 4px 12px rgba(15,23,42,0.25);">
                                    <i class="fa-solid fa-lock mr-1"></i> Update Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ==========================================
         SCRIPTS
         ========================================== -->
    <!-- MediaPipe Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/camera_utils/camera_utils.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/face_mesh/face_mesh.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/drawing_utils/drawing_utils.js" crossorigin="anonymous"></script>



    <!-- jQuery & Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Three.js & VanillaTilt for Modern 3D WebGL UI -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.0/vanilla-tilt.min.js"></script>

    <script>
        window.openNoticeSignModal = function(id, title, desc, date, file, isSigned, signedAt) {
            $('#sign-notice-id').val(id);
            $('#modal-notice-title').text(title);
            $('#modal-notice-date').html('<i class="fa-regular fa-calendar mr-1"></i> ' + date);
            $('#modal-notice-desc').text(desc);

            if (file && file.length > 0) {
                const ext = file.split('.').pop().toLowerCase();
                const isImg = ['png', 'jpg', 'jpeg', 'gif', 'webp'].includes(ext);
                if (isImg) {
                    $('#modal-notice-img').attr('src', file);
                    $('#modal-notice-img-link').attr('href', file);
                    $('#modal-notice-img-box').show();
                    $('#modal-notice-attachment').hide();
                } else {
                    $('#modal-notice-img-box').hide();
                    $('#modal-notice-file-link').attr('href', file);
                    $('#modal-notice-attachment').show();
                }
            } else {
                $('#modal-notice-img-box').hide();
                $('#modal-notice-attachment').hide();
            }

            $('#sign-pin-input').val('');
            $('#sign-error-msg').hide().text('');

            if (isSigned == 1) {
                $('#modal-pin-sign-box').hide();
                $('#modal-already-signed-box').show();
                $('#modal-signed-timestamp').text('Ditandatangani pada: ' + signedAt);
            } else {
                $('#modal-pin-sign-box').show();
                $('#modal-already-signed-box').hide();
            }

            $('#noticeSignatureModal').appendTo('body').modal('show');
        };

        window.openSecurityModal = function(tab) {
            const targetTab = tab === 'password' ? '#sec-pass-tab' : '#sec-pin-tab';
            $(targetTab).tab('show');
            $('#pin-change-feedback, #pass-change-feedback').hide().text('');
            $('#securityModal').appendTo('body').modal('show');
        };

        window.openChangePinModal = function(tab) {
            window.openSecurityModal(tab || 'pin');
        };

        function togglePassView(inputId, btnEl) {
            const input = document.getElementById(inputId);
            if (!input) return;
            const icon = btnEl.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                if (icon) {
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                }
            } else {
                input.type = 'password';
                if (icon) {
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            }
        }

        function togglePinVisibility() {
            const input = document.getElementById('sign-pin-input');
            const icon = document.getElementById('pin-eye-icon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        function submitNoticePin(e) {
            e.preventDefault();
            const noticeId = $('#sign-notice-id').val();
            const pin = $('#sign-pin-input').val().trim();
            const errorDiv = $('#sign-error-msg');
            const btnSubmit = $('#btn-submit-sign');

            if (!pin) {
                errorDiv.text('Silakan masukkan PIN Anda.').show();
                return;
            }

            btnSubmit.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin mr-1"></i> Memverifikasi...');
            errorDiv.hide();

            $.ajax({
                url: '<?php echo base_url("portal/sign_notice"); ?>',
                type: 'POST',
                data: { notice_id: noticeId, pin: pin },
                dataType: 'json',
                success: function(res) {
                    btnSubmit.prop('disabled', false).html('<i class="fa-solid fa-check mr-1"></i> Setujui & Tanda Tangani');
                    if (res.status === 'success') {
                        // Update status card di list
                        const statusContainer = $('#ann-status-' + noticeId);
                        statusContainer.html(`
                            <span class="badge badge-success px-2 py-1 font-weight-bold" style="font-size: 10px; background: #ECFDF5; color: #059669; border: 1px solid #A7F3D0;">
                                <i class="fa-solid fa-circle-check mr-1"></i> Tertanda (Signed)
                            </span>
                            <div class="text-muted mt-1" style="font-size: 9.5px;">${res.signed_at}</div>
                        `);

                        // Update tombol di card
                        const cardBtn = $(`#ann-card-${noticeId} .btn-open-sign`);
                        cardBtn.removeClass('btn-primary').addClass('btn-light border text-muted')
                            .removeAttr('style').attr('style', 'font-size: 11px;')
                            .attr('data-signed', '1')
                            .attr('data-signedat', res.signed_at)
                            .html('<i class="fa-regular fa-file-lines mr-1"></i> Buka Dokumen');

                        // Update modal state
                        $('#modal-pin-sign-box').slideUp();
                        $('#modal-signed-timestamp').text('Ditandatangani pada: ' + res.signed_at);
                        $('#modal-already-signed-box').slideDown();

                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil Ditandatangani!',
                                text: res.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            alert(res.message);
                        }
                    } else {
                        errorDiv.text(res.message).show();
                    }
                },
                error: function() {
                    btnSubmit.prop('disabled', false).html('<i class="fa-solid fa-check mr-1"></i> Setujui & Tanda Tangani');
                    errorDiv.text('Terjadi kendala koneksi server. Silakan coba lagi.').show();
                }
            });
        }

        function submitUpdatePin(e) {
            e.preventDefault();
            const oldPin = $('#old-pin-input').val().trim();
            const newPin = $('#new-pin-input').val().trim();
            const feedback = $('#pin-change-feedback');
            const btnSubmit = $('#btn-save-pin');

            if (!oldPin || !newPin) {
                feedback.removeClass('text-success').addClass('text-danger').text('PIN Lama dan Baru wajib diisi.').show();
                return;
            }

            if (newPin.length < 4 || newPin.length > 6 || !/^\d+$/.test(newPin)) {
                feedback.removeClass('text-success').addClass('text-danger').text('PIN Baru harus berupa 4-6 digit angka numerik.').show();
                return;
            }

            btnSubmit.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin mr-1"></i> Menyimpan...');
            feedback.hide();

            $.ajax({
                url: '<?php echo base_url("portal/update_pin"); ?>',
                type: 'POST',
                data: { old_pin: oldPin, new_pin: newPin },
                dataType: 'json',
                success: function(res) {
                    btnSubmit.prop('disabled', false).html('<i class="fa-solid fa-floppy-disk mr-1"></i> Simpan PIN Baru');
                    if (res.status === 'success') {
                        feedback.removeClass('text-danger').addClass('text-success').html('<i class="fa-solid fa-circle-check mr-1"></i> ' + res.message).show();
                        setTimeout(function() {
                            $('#securityModal').modal('hide');
                            $('#old-pin-input').val('');
                            $('#new-pin-input').val('');
                            feedback.hide();
                        }, 1800);
                    } else {
                        feedback.removeClass('text-success').addClass('text-danger').html('<i class="fa-solid fa-triangle-exclamation mr-1"></i> ' + res.message).show();
                    }
                },
                error: function() {
                    btnSubmit.prop('disabled', false).html('<i class="fa-solid fa-floppy-disk mr-1"></i> Simpan PIN Baru');
                    feedback.removeClass('text-success').addClass('text-danger').text('Gagal menghubungi server. Silakan coba kembali.').show();
                }
            });
        }

        function submitUpdatePassword(e) {
            e.preventDefault();
            const oldPass = $('#old-pass-input').val();
            const newPass = $('#new-pass-input').val();
            const confirmPass = $('#confirm-pass-input').val();
            const feedback = $('#pass-change-feedback');
            const btnSubmit = $('#btn-save-pass');

            if (!oldPass || !newPass || !confirmPass) {
                feedback.removeClass('text-success').addClass('text-danger').text('Semua kolom password wajib diisi.').show();
                return;
            }

            if (newPass.length < 6) {
                feedback.removeClass('text-success').addClass('text-danger').text('Password Baru minimal 6 karakter.').show();
                return;
            }

            if (newPass !== confirmPass) {
                feedback.removeClass('text-success').addClass('text-danger').text('Konfirmasi Password Baru tidak cocok.').show();
                return;
            }

            btnSubmit.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin mr-1"></i> Mengupdate...');
            feedback.hide();

            $.ajax({
                url: '<?php echo base_url("portal/update_password"); ?>',
                type: 'POST',
                data: { old_password: oldPass, new_password: newPass, confirm_password: confirmPass },
                dataType: 'json',
                success: function(res) {
                    btnSubmit.prop('disabled', false).html('<i class="fa-solid fa-lock mr-1"></i> Update Password');
                    if (res.status === 'success') {
                        feedback.removeClass('text-danger').addClass('text-success').html('<i class="fa-solid fa-circle-check mr-1"></i> ' + res.message).show();
                        setTimeout(function() {
                            $('#securityModal').modal('hide');
                            $('#old-pass-input').val('');
                            $('#new-pass-input').val('');
                            $('#confirm-pass-input').val('');
                            feedback.hide();
                        }, 2000);
                    } else {
                        feedback.removeClass('text-success').addClass('text-danger').html('<i class="fa-solid fa-triangle-exclamation mr-1"></i> ' + res.message).show();
                    }
                },
                error: function() {
                    btnSubmit.prop('disabled', false).html('<i class="fa-solid fa-lock mr-1"></i> Update Password');
                    feedback.removeClass('text-success').addClass('text-danger').text('Gagal menghubungi server. Silakan coba kembali.').show();
                }
            });
        }

        const isAlreadyRegistered = <?php echo !empty($face_signature) ? 'true' : 'false'; ?>;

        let regCamera = null;
        let regFaceMesh = null;

        const regVideo = document.getElementById('reg-webcam-feed');
        const regCanvas = document.getElementById('reg-mesh-canvas');
        const regCtx = regCanvas.getContext('2d');

        // Definisi 5 Langkah Pemindaian 3D
        const SCAN_STEPS = [
            { key: 'center', label: 'Depan (Netral)', compass: '🎯 Sudut Target: Depan', desc: 'Wajah lurus ke arah kamera' },
            { key: 'left',   label: 'Menoleh Kiri',   compass: '👈 Sudut Target: Kiri (~15°)', desc: 'Tengok perlahan ke kiri Anda' },
            { key: 'right',  label: 'Menoleh Kanan',  compass: '👉 Sudut Target: Kanan (~15°)', desc: 'Tengok perlahan ke kanan Anda' },
            { key: 'up',     label: 'Angkat Dagu',    compass: '👆 Sudut Target: Atas (~10°)', desc: 'Dongakkan kepala sedikit ke atas' },
            { key: 'down',   label: 'Menunduk',       compass: '👇 Sudut Target: Bawah (~10°)', desc: 'Tundukkan kepala sedikit ke bawah' }
        ];

        let currentStepIdx = 0;
        let capturedAngles = {};
        let stablePoseFrames = 0;
        const REQUIRED_STABLE_FRAMES = 8; // ~250ms pose stabil
        let is3DScanningActive = false;
        let latestRegFaceLandmarks = null;

        // Web Audio Synthesizer untuk Umpan Balik Suara Nyata (Bebas Ketergantungan File Audio Eksternal)
        function playBiometricBeep(freq = 880, duration = 0.08, type = 'sine') {
            try {
                const AudioCtx = window.AudioContext || window.webkitAudioContext;
                if (!AudioCtx) return;
                const ctx = new AudioCtx();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = type;
                osc.frequency.setValueAtTime(freq, ctx.currentTime);
                gain.gain.setValueAtTime(0.15, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + duration);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start();
                osc.stop(ctx.currentTime + duration);
            } catch (e) {}
        }

        function playFanfareSuccess() {
            playBiometricBeep(523, 0.09);
            setTimeout(() => playBiometricBeep(659, 0.09), 90);
            setTimeout(() => playBiometricBeep(784, 0.18), 180);
        }

        function flashShutterEffect() {
            const el = document.getElementById('reg-flash-overlay');
            if (el) {
                el.style.opacity = '0.75';
                setTimeout(() => { el.style.opacity = '0'; }, 120);
            }
        }

        regFaceMesh = new FaceMesh({
            locateFile: (file) => `https://cdn.jsdelivr.net/npm/@mediapipe/face_mesh/${file}`
        });

        regFaceMesh.setOptions({
            maxNumFaces: 1,
            refineLandmarks: true,
            minDetectionConfidence: 0.5,
            minTrackingConfidence: 0.5
        });

        regFaceMesh.onResults((results) => {
            if (!regCanvas || !regVideo) return;
            if (results && results.multiFaceLandmarks && results.multiFaceLandmarks.length > 0) {
                latestRegFaceLandmarks = results.multiFaceLandmarks[0];
            }
            const vWidth = regVideo.videoWidth || 640;
            const vHeight = regVideo.videoHeight || 480;
            const cWidth = regVideo.clientWidth || 400;
            const cHeight = regVideo.clientHeight || 440;

            regCanvas.width = cWidth;
            regCanvas.height = cHeight;

            regCtx.save();
            regCtx.clearRect(0, 0, regCanvas.width, regCanvas.height);

            const videoRatio = vWidth / vHeight;
            const containerRatio = cWidth / cHeight;

            let renderWidth, renderHeight, offsetX, offsetY;
            if (containerRatio > videoRatio) {
                renderWidth = cWidth;
                renderHeight = cWidth / videoRatio;
                offsetX = 0;
                offsetY = (cHeight - renderHeight) / 2;
            } else {
                renderWidth = cHeight * videoRatio;
                renderHeight = cHeight;
                offsetX = (cWidth - renderWidth) / 2;
                offsetY = 0;
            }

            const toRegPixel = (pt) => ({
                x: (1.0 - pt.x) * renderWidth + offsetX,
                y: pt.y * renderHeight + offsetY
            });

            const drawRegConnectors = (connections, color, lineWidth) => {
                regCtx.strokeStyle = color;
                regCtx.lineWidth = lineWidth;
                for (const conn of connections) {
                    const pt1 = results.multiFaceLandmarks[0][conn[0]];
                    const pt2 = results.multiFaceLandmarks[0][conn[1]];
                    if (pt1 && pt2) {
                        const p1 = toRegPixel(pt1);
                        const p2 = toRegPixel(pt2);
                        regCtx.beginPath();
                        regCtx.moveTo(p1.x, p1.y);
                        regCtx.lineTo(p2.x, p2.y);
                        regCtx.stroke();
                    }
                }
            };

            const guideEl = document.getElementById('reg-pose-guide');
            const ovalEl = document.getElementById('reg-oval-guide');
            const compassEl = document.getElementById('reg-angle-compass');

            if (results.multiFaceLandmarks && results.multiFaceLandmarks.length > 0) {
                // Gambar jaring biometrik 3D
                drawRegConnectors(FACEMESH_TESSELATION, 'rgba(59, 130, 246, 0.15)', 0.5);
                drawRegConnectors(FACEMESH_RIGHT_EYE, 'rgba(16, 185, 129, 0.85)', 1.2);
                drawRegConnectors(FACEMESH_LEFT_EYE, 'rgba(16, 185, 129, 0.85)', 1.2);
                drawRegConnectors(FACEMESH_FACE_OVAL, 'rgba(99, 102, 241, 0.65)', 1.5);
                drawRegConnectors(FACEMESH_LIPS, 'rgba(245, 158, 11, 0.7)', 1.2);

                if (is3DScanningActive && currentStepIdx < SCAN_STEPS.length) {
                    const lm = results.multiFaceLandmarks[0];
                    const nose = lm[1];
                    const chin = lm[152];
                    const forehead = lm[10];
                    const leftEye = lm[263];  // Mata kiri pengguna (di kanan layar unmirrored)
                    const rightEye = lm[33];  // Mata kanan pengguna (di kiri layar unmirrored)

                    // 1. Yaw (Tengok Kiri / Kanan)
                    const midEyeX = (leftEye.x + rightEye.x) / 2;
                    const eyeSpan = Math.abs(leftEye.x - rightEye.x) || 0.001;
                    const yawRatio = (nose.x - midEyeX) / eyeSpan;

                    // 2. Pitch (Mendongak / Menunduk)
                    const midFaceY = (forehead.y + chin.y) / 2;
                    const faceH = Math.abs(chin.y - forehead.y) || 0.001;
                    const pitchRatio = (nose.y - midFaceY) / faceH;
                    const zDiff = chin.z - forehead.z;

                    // 3. Roll (Miring Leher)
                    const rollDeg = Math.atan2(leftEye.y - rightEye.y, leftEye.x - rightEye.x) * 180 / Math.PI;

                    const activeStep = SCAN_STEPS[currentStepIdx];
                    let isMatch = false;
                    let instructionText = '';

                    if (Math.abs(rollDeg) > 13) {
                        instructionText = '⚠️ Posisi kepala miring, tegakkan leher Anda';
                    } else if (activeStep.key === 'center') {
                        if (Math.abs(yawRatio) <= 0.09 && Math.abs(pitchRatio) <= 0.07) {
                            isMatch = true;
                            instructionText = '🎯 Sempurna! Tahan posisi lurus ke depan...';
                        } else {
                            instructionText = '👤 Posisikan wajah lurus menghadap kamera di tengah oval';
                        }
                    } else if (activeStep.key === 'left') {
                        if (yawRatio >= 0.13) {
                            isMatch = true;
                            instructionText = '👈 Bagus! Tahan posisi menoleh ke kiri...';
                        } else {
                            instructionText = '👈 Putar / tengokkan wajah perlahan ke arah KIRI Anda';
                        }
                    } else if (activeStep.key === 'right') {
                        if (yawRatio <= -0.13) {
                            isMatch = true;
                            instructionText = '👉 Bagus! Tahan posisi menoleh ke kanan...';
                        } else {
                            instructionText = '👉 Putar / tengokkan wajah perlahan ke arah KANAN Anda';
                        }
                    } else if (activeStep.key === 'up') {
                        if (pitchRatio <= -0.04 || zDiff <= -0.015) {
                            isMatch = true;
                            instructionText = '👆 Bagus! Tahan posisi mendongak ke atas...';
                        } else {
                            instructionText = '👆 Angkat dagu / tengadahkan kepala sedikit ke ATAS';
                        }
                    } else if (activeStep.key === 'down') {
                        if (pitchRatio >= 0.04 || zDiff >= 0.015) {
                            isMatch = true;
                            instructionText = '👇 Bagus! Tahan posisi menunduk ke bawah...';
                        } else {
                            instructionText = '👇 Tundukkan kepala sedikit ke arah BAWAH';
                        }
                    }

                    if (compassEl) {
                        compassEl.innerText = activeStep.compass;
                    }

                    if (isMatch) {
                        stablePoseFrames++;
                        if (ovalEl) {
                            ovalEl.style.borderColor = '#10b981';
                            ovalEl.style.boxShadow = '0 0 35px rgba(16, 185, 129, 0.45)';
                        }
                        const holdPct = Math.min(100, Math.round((stablePoseFrames / REQUIRED_STABLE_FRAMES) * 100));
                        if (guideEl) {
                            guideEl.innerHTML = `<i class="fa-solid fa-circle-notch fa-spin text-success mr-2"></i> ${instructionText} (${holdPct}%)`;
                            guideEl.style.color = '#34d399';
                        }

                        if (stablePoseFrames >= REQUIRED_STABLE_FRAMES) {
                            // Sukses merekam sudut ini!
                            flashShutterEffect();
                            playBiometricBeep(880, 0.09);
                            captureCurrentStep(activeStep.key);
                            advanceToNextStep();
                        }
                    } else {
                        stablePoseFrames = Math.max(0, stablePoseFrames - 1);
                        if (ovalEl) {
                            ovalEl.style.borderColor = '#3b82f6';
                            ovalEl.style.boxShadow = '0 0 25px rgba(59, 130, 246, 0.25)';
                        }
                        if (guideEl) {
                            guideEl.innerHTML = instructionText;
                            guideEl.style.color = '#60a5fa';
                        }
                    }
                }
            } else {
                stablePoseFrames = 0;
                if (guideEl) {
                    guideEl.innerHTML = '<i class="fa-solid fa-circle-exclamation text-warning mr-2"></i> Wajah tidak terdeteksi. Silakan posisikan wajah di dalam frame.';
                    guideEl.style.color = '#fbbf24';
                }
                if (ovalEl) {
                    ovalEl.style.borderColor = '#eab308';
                    ovalEl.style.boxShadow = '0 0 20px rgba(234, 179, 8, 0.2)';
                }
            }
            regCtx.restore();
        });

        function captureCurrentStep(stepKey) {
            const snapCanvas = document.getElementById('reg-canvas-snap');
            snapCanvas.width = 640;
            snapCanvas.height = 480;
            const snapCtx = snapCanvas.getContext('2d');
            snapCtx.drawImage(regVideo, 0, 0, 640, 480);
            const dataUrl = snapCanvas.toDataURL('image/jpeg', 0.88);

            capturedAngles[stepKey] = {
                angle: stepKey,
                data: dataUrl
            };

            // Update status UI pada kartu checklist
            const row = document.getElementById('step-row-' + stepKey);
            const badge = document.getElementById('step-badge-' + stepKey);
            if (row) {
                row.style.border = '1.5px solid #10b981';
                row.style.background = 'rgba(16, 185, 129, 0.12)';
                row.style.opacity = '1';
            }
            if (badge) {
                badge.innerHTML = '<span class="badge badge-success px-2 py-1 font-weight-bold" style="font-size: 10px;"><i class="fa-solid fa-check mr-1"></i> Terekam</span>';
            }
        }

        function advanceToNextStep() {
            stablePoseFrames = 0;
            currentStepIdx++;

            const pct = Math.round((currentStepIdx / SCAN_STEPS.length) * 100);
            $('#reg-progress-pct').text(`${pct}% (${currentStepIdx} / ${SCAN_STEPS.length} Sudut)`);
            $('#reg-progress-bar').css('width', `${pct}%`);

            if (currentStepIdx < SCAN_STEPS.length) {
                const nextStep = SCAN_STEPS[currentStepIdx];
                const nextRow = document.getElementById('step-row-' + nextStep.key);
                const nextBadge = document.getElementById('step-badge-' + nextStep.key);
                if (nextRow) {
                    nextRow.style.border = '1.5px solid #3b82f6';
                    nextRow.style.opacity = '1';
                }
                if (nextBadge) {
                    nextBadge.innerHTML = '<span class="badge badge-primary px-2 py-1 font-weight-bold" style="font-size: 10px;"><i class="fa-solid fa-arrows-rotate fa-spin mr-1"></i> Memindai</span>';
                }
                $('#reg-angle-compass').text(nextStep.compass);
            } else {
                // Semua 5 sudut terekam lengkap!
                playFanfareSuccess();
                $('#reg-angle-compass').removeClass('badge-primary').addClass('badge-success').html('🎉 Semua 5 Sudut Lengkap!');
                $('#reg-pose-guide').html('🎉 Sempurna! Semua 5 orientasi wajah 3D terekam. Masukkan PIN dan klik Simpan.').css('color', '#34d399');
                $('#reg-progress-bar').removeClass('bg-info').addClass('bg-success');
                $('#btn-save-web-face').prop('disabled', false).removeClass('btn-danger').addClass('btn-success').html('<i class="fa-solid fa-check-double mr-1"></i> Simpan Biometrik 3D Sekarang');
            }
        }

        function manualCaptureCurrentStep() {
            if (currentStepIdx >= SCAN_STEPS.length) return;
            const activeStep = SCAN_STEPS[currentStepIdx];
            flashShutterEffect();
            playBiometricBeep(880, 0.08);
            captureCurrentStep(activeStep.key);
            advanceToNextStep();
        }

        function reset3DScan() {
            currentStepIdx = 0;
            capturedAngles = {};
            stablePoseFrames = 0;

            $('#reg-progress-pct').text('0% (0 / 5 Sudut)');
            $('#reg-progress-bar').css('width', '0%').removeClass('bg-success').addClass('bg-info');
            $('#btn-save-web-face').prop('disabled', true).removeClass('btn-success').addClass('btn-danger').html('<i class="fa-solid fa-check-double mr-1"></i> Simpan Biometrik 3D');
            $('#reg-angle-compass').removeClass('badge-success').addClass('badge-primary').text('🎯 Sudut Target: Depan');

            SCAN_STEPS.forEach((s, idx) => {
                const row = document.getElementById('step-row-' + s.key);
                const badge = document.getElementById('step-badge-' + s.key);
                if (idx === 0) {
                    if (row) {
                        row.style.border = '1.5px solid #3b82f6';
                        row.style.background = '#1e293b';
                        row.style.opacity = '1';
                    }
                    if (badge) {
                        badge.innerHTML = '<span class="badge badge-primary px-2 py-1 font-weight-bold" style="font-size: 10px;"><i class="fa-solid fa-arrows-rotate fa-spin mr-1"></i> Memindai</span>';
                    }
                } else {
                    if (row) {
                        row.style.border = '1px solid #334155';
                        row.style.background = '#1e293b';
                        row.style.opacity = '0.7';
                    }
                    if (badge) {
                        badge.innerHTML = '<span class="badge badge-secondary px-2 py-1" style="font-size: 10px; background: #334155;">Menunggu</span>';
                    }
                }
            });
        }

        function startWebFaceRegistration() {
            window.location.href = '<?php echo base_url("face/register"); ?>';
        }

        // Legacy face-api.js removed — registration is now handled via /face/register (InsightFace buffalo_l)

        async function captureAndSaveWebFace() {
            var pin = $('#reg-pin-input').val();
            if (!pin || pin.length !== 4) { 
                alert('⚠️ PIN Otorisasi harus 4 angka!'); 
                return; 
            }

            const snapCanvas = document.getElementById('reg-canvas-snap');
            snapCanvas.width = 640;
            snapCanvas.height = 480;
            const snapCtx = snapCanvas.getContext('2d');
            snapCtx.drawImage(regVideo, 0, 0, 640, 480);

            var empCode = '<?php echo isset($employee->em_code) ? $employee->em_code : ''; ?>';
            var empId = '<?php echo isset($employee->em_id) ? $employee->em_id : ''; ?>';

            // HANYA simpan 1 foto utama (sudut depan / center)
            var primaryImage = capturedAngles['center'] ? capturedAngles['center'].data : snapCanvas.toDataURL('image/jpeg', 0.88);

            $('#btn-save-web-face').prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin mr-1"></i> Mengekstrak TensorFlow 128D...');

            // Ekstrak matriks/vektor biometrik 128D TensorFlow FaceNet
            let vector = await extractTensorFlowVector(snapCanvas);
            if (!vector && capturedAngles['center']) {
                const imgEl = new Image();
                imgEl.src = capturedAngles['center'].data;
                await new Promise(r => { imgEl.onload = r; });
                vector = await extractTensorFlowVector(imgEl);
            }

            if (!vector || vector.length !== 128) {
                alert('⚠️ TensorFlow tidak mendeteksi wajah dengan jelas. Pastikan wajah tegak, pencahayaan cukup, dan ulangi pemindaian.');
                $('#btn-save-web-face').prop('disabled', false).html('<i class="fa-solid fa-check-double mr-1"></i> Simpan Biometrik 3D Sekarang');
                return;
            }

            $('#btn-save-web-face').html('<i class="fa-solid fa-spinner fa-spin mr-1"></i> Menyimpan Biometrik 128D...');

            $.ajax({
                url: '<?php echo base_url("api/register_face_web"); ?>',
                type: 'POST',
                data: { 
                    image: primaryImage,
                    vector_json: JSON.stringify(vector), // Vektor biometrik 128-D FaceNet
                    em_code: empCode, 
                    em_id: empId, 
                    nik: empCode ? empCode : empId,
                    pin: pin 
                },
                dataType: 'json',
                success: function(res) {
                    if (res.success) {
                        alert('✅ ' + res.message);
                        stopWebFaceRegistration();
                        $('#webFaceRegisterModal').modal('hide');
                        location.reload();
                    } else {
                        alert('❌ ' + (res.message || 'Gagal menyimpan data biometrik wajah.'));
                        $('#btn-save-web-face').prop('disabled', false).html('<i class="fa-solid fa-check-double mr-1"></i> Coba Lagi');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', xhr.responseText);
                    var msg = 'Gagal terhubung ke server API.';
                    try {
                        var errObj = JSON.parse(xhr.responseText);
                        if (errObj.message) msg = errObj.message;
                    } catch(e) {}
                    alert('❌ ' + msg);
                    $('#btn-save-web-face').prop('disabled', false).html('<i class="fa-solid fa-check-double mr-1"></i> Coba Lagi');
                }
            });
        }

        $(document).ready(function() {
            // Live Clock with GMT+7 / WIB
            setInterval(function() {
                var now = new Date();
                var timeStr = now.toLocaleDateString('id-ID', { weekday: 'short', day: '2-digit', month: 'short', year: 'numeric' }) + ' - ' + now.toLocaleTimeString('id-ID', { hour12: false }) + ' WIB';
                $('#portal-live-clock').text(timeStr);
            }, 1000);

            // Sidebar Toggle
            $('#sidebar-toggle-btn').on('click', function() {
                $('#app-sidebar').toggleClass('collapsed');
            });

            // Tab Navigation
            $('.nav-link-item[data-tab]').on('click', function(e) {
                e.preventDefault();
                
                $('.nav-link-item').removeClass('active');
                $(this).addClass('active');

                var targetTab = $(this).attr('data-tab');
                $('.portal-tab').removeClass('active');
                $('#' + targetTab).addClass('active');

                var pageTitle = $(this).find('.nav-label').text().trim();
                if (targetTab === 'tab-home') {
                    <?php 
                        $hour2 = (int)date('H');
                        if($hour2 < 12) $g2 = 'Selamat Pagi';
                        elseif($hour2 < 15) $g2 = 'Selamat Siang';
                        elseif($hour2 < 18) $g2 = 'Selamat Sore';
                        else $g2 = 'Selamat Malam';
                    ?>
                    $('#page-title-heading').html('<?php echo $g2 . ", " . (isset($employee->first_name) ? $employee->first_name : "User"); ?> 👋');
                } else {
                    $('#page-title-heading').text(pageTitle);
                }
            });

            // Calendar Click
            $('.cal-cell').on('click', function() {
                var rawData = $(this).attr('data-info');
                if(!rawData) return;

                var data = JSON.parse(rawData);
                if(!data) return;

                var dateObj = new Date(data.date);
                var formattedDate = dateObj.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                $('#modal-date-title').text('Detail Agenda - ' + formattedDate);

                if(data.is_holiday && data.holiday_name) {
                    $('#modal-holiday-name').text(data.holiday_name);
                    $('#modal-holiday-banner').removeClass('d-none');
                } else if(data.is_holiday) {
                    $('#modal-holiday-name').text('Hari Minggu / Libur');
                    $('#modal-holiday-banner').removeClass('d-none');
                } else {
                    $('#modal-holiday-banner').addClass('d-none');
                }

                if(data.has_event && data.event_title) {
                    $('#modal-event-title').text(data.event_title);
                    $('#modal-event-banner').removeClass('d-none');
                } else {
                    $('#modal-event-banner').addClass('d-none');
                }

                if(data.attendance) {
                    $('#modal-signin-time').text(data.attendance.signin_time ? data.attendance.signin_time : '--:--');
                    $('#modal-signout-time').text(data.attendance.signout_time ? data.attendance.signout_time : '--:--');
                    $('#modal-verification-status').html('<i class="fa-solid fa-shield-check text-success mr-1"></i> Presensi Tercatat & Verified (AI Face)');
                } else if(data.leave) {
                    $('#modal-signin-time').text('CUTI');
                    $('#modal-signout-time').text('CUTI');
                    $('#modal-verification-status').html('<i class="fa-solid fa-user-minus text-warning mr-1"></i> Izin / Cuti (' + (data.leave.leave_type || 'Cuti') + ')');
                } else {
                    $('#modal-signin-time').text('--:--');
                    $('#modal-signout-time').text('--:--');
                    $('#modal-verification-status').html('<i class="fa-solid fa-circle-info text-muted mr-1"></i> Tidak Ada Rekam Presensi');
                }

                $('#calendarDetailModal').modal('show');
            });

            // ==========================================
            // AI REIMBURSEMENT VISION OCR HANDLER
            // ==========================================
            function handleFileSelect(file) {
                if (!file) return;
                var isPdf = file.type === 'application/pdf' || file.name.toLowerCase().endsWith('.pdf');
                if (isPdf) {
                    $('#receipt-preview-img').addClass('d-none').attr('src', '');
                    $('#pdf-file-name').text(file.name);
                    $('#pdf-preview-box').removeClass('d-none');
                    $('#upload-icon').addClass('d-none');
                    $('#upload-label').text(file.name);
                } else {
                    $('#pdf-preview-box').addClass('d-none');
                    var reader = new FileReader();
                    reader.onload = function(ex) {
                        $('#receipt-preview-img').attr('src', ex.target.result).removeClass('d-none');
                        $('#upload-icon').addClass('d-none');
                        $('#upload-label').text(file.name);
                    };
                    reader.readAsDataURL(file);
                }
            }

            $('#receipt-file-input').on('change', function(e) {
                if (e.target.files && e.target.files[0]) {
                    handleFileSelect(e.target.files[0]);
                }
            });

            // Drag & Drop File Handlers
            var $dropBox = $('#receipt-upload-box');
            $dropBox.on('dragover dragenter', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).css({ 'border-color': '#6366F1', 'background': 'rgba(99,102,241,0.08)' });
            });

            $dropBox.on('dragleave dragend drop', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).css({ 'border-color': '#CBD5E1', 'background': 'rgba(241,245,249,0.5)' });
            });

            $dropBox.on('drop', function(e) {
                var dt = e.originalEvent.dataTransfer;
                if (dt && dt.files && dt.files[0]) {
                    $('#receipt-file-input')[0].files = dt.files;
                    handleFileSelect(dt.files[0]);
                }
            });

            // Sub-Tab Switcher (Form Input vs List Reimbursement)
            $('#reimb-subtabs a').on('click', function() {
                $('#reimb-subtabs a').removeClass('active text-white').addClass('text-dark bg-white border').css('background', '#fff');
                $(this).addClass('active text-white').removeClass('text-dark bg-white border').css('background', '#6366F1');
                var target = $(this).attr('data-subtab');
                $('.reimb-subtab-content').addClass('d-none');
                $('#' + target).removeClass('d-none');
            });

            // Auto Scan Button Click
            $('#btn-scan-ai').on('click', function() {
                var fileInput = $('#receipt-file-input')[0];
                if (!fileInput.files || !fileInput.files[0]) {
                    alert('Silakan pilih atau upload foto bukti kuitansi / nota terlebih dahulu!');
                    return;
                }

                var formData = new FormData();
                formData.append('receipt_file', fileInput.files[0]);
                formData.append('provider', $('#ai-provider-select').val());
                formData.append('model', $('#ai-model-select').val());
                formData.append('api_key', $('#ai-api-key').val());

                $('#btn-scan-ai').prop('disabled', true);
                $('#scan-btn-icon').removeClass('fa-bolt').addClass('fa-spinner fa-spin');
                $('#scan-btn-text').text('Membaca & Mengolah Data Nota...');

                $.ajax({
                    url: '<?php echo base_url("portal/scan_receipt_ai"); ?>',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(res) {
                        $('#btn-scan-ai').prop('disabled', false);
                        $('#scan-btn-icon').removeClass('fa-spinner fa-spin').addClass('fa-bolt');
                        $('#scan-btn-text').text('Pindai & Ekstrak Data Nota');

                        if (res.status == '200' && res.success) {
                            if (res.file_name) {
                                $('#input-receipt-image').val(res.file_name);
                            }

                            if (res.ai_data) {
                                var d = res.ai_data;
                                if (d.title || d.merchant) $('#input-title').val(d.title || d.merchant);
                                
                                var amtVal = d.amount;
                                if (amtVal !== undefined && amtVal !== null) {
                                    if (typeof amtVal === 'string') {
                                        amtVal = amtVal.replace(/[^\d\.,]/g, '');
                                        if (amtVal.indexOf('.') !== -1 && amtVal.indexOf(',') !== -1) {
                                            amtVal = amtVal.replace(/\./g, '').replace(',', '.');
                                        } else if (amtVal.indexOf('.') !== -1) {
                                            var parts = amtVal.split('.');
                                            if (parts.length > 1 && parts[parts.length - 1].length === 3) {
                                                amtVal = amtVal.replace(/\./g, '');
                                            }
                                        } else {
                                            amtVal = amtVal.replace(',', '.');
                                        }
                                    }
                                    var parsedNum = parseFloat(amtVal);
                                    if (!isNaN(parsedNum) && parsedNum > 0) {
                                        $('#input-amount').val(parsedNum);
                                    } else {
                                        $('#input-amount').val('');
                                    }
                                }

                                if (d.category) $('#input-category').val(d.category);
                                if (d.description) $('#input-description').val(d.description);
                                $('#input-ai-analysis').val(JSON.stringify(d));
                            } else {
                                alert('Sistem telah menyimpan foto nota. Silakan lengkapi detail form secara manual jika diperlukan.');
                            }
                        } else {
                            alert(res.message || 'Gagal memproses data gambar nota.');
                        }
                    },
                    error: function() {
                        $('#btn-scan-ai').prop('disabled', false);
                        $('#scan-btn-icon').removeClass('fa-spinner fa-spin').addClass('fa-bolt');
                        $('#scan-btn-text').text('Pindai & Ekstrak Data Nota');
                        alert('Terjadi kesalahan koneksi saat memproses data nota.');
                    }
                });
            });

            // ==========================================
            // TEMPORARY STAGING BASKET & BULK SUBMISSION
            // ==========================================
            var temporaryStagingItems = [];

            function renderStagingTable() {
                var $tbody = $('#tbody-staging');
                $tbody.empty();

                if (temporaryStagingItems.length === 0) {
                    $tbody.html('<tr id="tr-empty-staging"><td colspan="6" class="text-center py-5 text-muted"><i class="fa-solid fa-inbox mb-2 text-indigo d-block" style="font-size:32px; opacity:0.4;"></i>Belum ada nota di temporary list.<br>Upload nota di sebelah kiri & klik <strong>"Masukkan ke Temporary List"</strong>.</td></tr>');
                    $('#live-grand-total').text('Rp 0');
                    $('#btn-batch-total').text('Rp 0');
                    $('#staging-count-badge').text('0 Nota Terkumpul');
                    $('#btn-submit-batch').prop('disabled', true);
                    return;
                }

                var grandTotal = 0;
                temporaryStagingItems.forEach(function(item, idx) {
                    grandTotal += parseFloat(item.amount || 0);
                    var imgTag = item.receipt_image ? '<a href="<?php echo base_url("uploads/reimbursement/"); ?>' + item.receipt_image + '" target="_blank"><img src="<?php echo base_url("uploads/reimbursement/"); ?>' + item.receipt_image + '" style="width:36px; height:36px; object-fit:cover; border-radius:6px; border:1px solid #CBD5E1;"></a>' : '<span class="text-muted">-</span>';

                    var html = '<tr>' +
                        '<td><strong class="text-muted">' + (idx + 1) + '</strong></td>' +
                        '<td>' + imgTag + '</td>' +
                        '<td><strong class="text-dark d-block" style="font-size:12px;">' + (item.title || 'Klaim Nota') + '</strong>' + (item.description ? '<small class="text-muted text-truncate d-block" style="max-width:180px;">' + item.description + '</small>' : '') + '</td>' +
                        '<td><span class="badge badge-light border text-secondary" style="font-size:9px;">' + (item.category || 'Umum') + '</span></td>' +
                        '<td class="text-right font-weight-bold text-success">Rp ' + parseFloat(item.amount || 0).toLocaleString('id-ID') + '</td>' +
                        '<td class="text-center"><button type="button" class="btn btn-xs btn-outline-danger btn-delete-staging" data-index="' + idx + '" style="border-radius:4px;"><i class="fa-solid fa-trash"></i></button></td>' +
                        '</tr>';
                    $tbody.append(html);
                });

                var formattedTotal = 'Rp ' + grandTotal.toLocaleString('id-ID');
                $('#live-grand-total').text(formattedTotal);
                $('#btn-batch-total').text(formattedTotal);
                $('#staging-count-badge').text(temporaryStagingItems.length + ' Nota Terkumpul');
                $('#btn-submit-batch').prop('disabled', false);
            }

            // Click Add to Staging
            $('#btn-add-to-staging').on('click', function() {
                var title = $('#input-title').val().trim();
                var amount = parseFloat($('#input-amount').val());
                var category = $('#input-category').val();
                var description = $('#input-description').val().trim();
                var receiptImage = $('#input-receipt-image').val();
                var aiAnalysis = $('#input-ai-analysis').val();

                if (!title || isNaN(amount) || amount <= 0) {
                    alert('Judul Klaim / Merchant dan Nominal Biaya (Rp) wajib diisi!');
                    return;
                }

                temporaryStagingItems.push({
                    title: title,
                    amount: amount,
                    category: category,
                    description: description,
                    receipt_image: receiptImage,
                    ai_analysis: aiAnalysis
                });

                // Clear input form for next item
                $('#input-title').val('');
                $('#input-amount').val('');
                $('#input-description').val('');
                $('#input-receipt-image').val('');
                $('#input-ai-analysis').val('');
                $('#receipt-file-input').val('');
                $('#receipt-preview-img').attr('src', '').addClass('d-none');
                $('#pdf-preview-box').addClass('d-none');
                $('#upload-icon').removeClass('d-none');
                $('#upload-label').text('Klik atau Tarik File Nota Ke Sini');

                renderStagingTable();
            });

            // Delete Staging Item
            $(document).on('click', '.btn-delete-staging', function() {
                var idx = parseInt($(this).attr('data-index'));
                if (!isNaN(idx) && idx >= 0 && idx < temporaryStagingItems.length) {
                    temporaryStagingItems.splice(idx, 1);
                    renderStagingTable();
                }
            });

            // Submit Master Batch Claim
            $('#btn-submit-batch').on('click', function() {
                if (temporaryStagingItems.length === 0) {
                    alert('Belum ada transaksi di temporary list!');
                    return;
                }

                var batchTitle = $('#input-batch-title').val().trim();
                if (!batchTitle) {
                    alert('Silakan isi Nama Laporan / Periode Pengajuan Klaim Batch!');
                    return;
                }

                if (!confirm('Apakah Anda yakin ingin mengirim laporan pengajuan klaim batch ini (' + temporaryStagingItems.length + ' Nota, Total ' + $('#live-grand-total').text() + ')?')) {
                    return;
                }

                $(this).prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin mr-2"></i> Mengirim Laporan Batch...');

                $.ajax({
                    url: '<?php echo base_url("portal/save_bulk_reimbursement"); ?>',
                    type: 'POST',
                    data: {
                        batch_title: batchTitle,
                        items: JSON.stringify(temporaryStagingItems)
                    },
                    dataType: 'json',
                    success: function(res) {
                        if (res.status == '200' && res.success) {
                            alert(res.message);
                            location.reload();
                        } else {
                            $('#btn-submit-batch').prop('disabled', false).html('<i class="fa-solid fa-paper-plane mr-2"></i> Kirim Pengajuan Klaim Batch (<span id="btn-batch-total">' + $('#live-grand-total').text() + '</span>)');
                            alert(res.message || 'Gagal mengirim pengajuan batch.');
                        }
                    },
                    error: function() {
                        $('#btn-submit-batch').prop('disabled', false).html('<i class="fa-solid fa-paper-plane mr-2"></i> Kirim Pengajuan Klaim Batch (<span id="btn-batch-total">' + $('#live-grand-total').text() + '</span>)');
                        alert('Terjadi kesalahan koneksi server saat mengirim batch.');
                    }
                });
            });

            // Click Lihat Detail Modal Batch Reimbursement
            $(document).on('click', '.btn-view-batch-detail', function() {
                var raw = $(this).attr('data-batch');
                if (!raw) return;
                var b = JSON.parse(raw);
                if (!b) return;

                $('#modal-batch-code').text(b.batch_code || 'RB-00000000');
                $('#modal-batch-title').text(b.batch_title || 'Laporan Reimbursement');
                $('#modal-batch-grand-total').text('Rp ' + parseFloat(b.grand_total || 0).toLocaleString('id-ID'));
                $('#modal-batch-items-count').text((b.total_items || 0) + ' Nota');

                if (b.created_at) {
                    var d = new Date(b.created_at);
                    $('#modal-batch-date').text(d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' }));
                    $('#timeline-step1-time').text(d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + ' WIB (Selesai)');
                } else {
                    $('#modal-batch-date').text('-');
                    $('#timeline-step1-time').text('Selesai');
                }

                // Status Audit Trail Timeline Configuration
                var status = (b.status || 'PENDING').toUpperCase();
                var $step2Icon = $('#timeline-step2-icon');
                var $step2Status = $('#timeline-step2-status');
                var $step3Icon = $('#timeline-step3-icon');
                var $step3Status = $('#timeline-step3-status');

                if (status === 'APPROVED') {
                    $step2Icon.removeClass('bg-secondary bg-warning bg-danger bg-primary').addClass('bg-success').html('<i class="fa-solid fa-check"></i>');
                    $step2Status.removeClass('text-warning text-danger text-muted text-primary').addClass('text-success font-weight-bold').text('Terverifikasi (ACC)');

                    $step3Icon.removeClass('bg-secondary bg-warning bg-danger bg-primary').addClass('bg-success').html('<i class="fa-solid fa-circle-check"></i>');
                    $step3Status.removeClass('text-warning text-danger text-muted text-primary').addClass('text-success font-weight-bold').text('Disetujui & Dicairkan');
                } else if (status === 'REJECTED') {
                    $step2Icon.removeClass('bg-secondary bg-warning bg-success bg-primary').addClass('bg-danger').html('<i class="fa-solid fa-xmark"></i>');
                    $step2Status.removeClass('text-success text-warning text-muted text-primary').addClass('text-danger font-weight-bold').text('Ditolak (Rejected)');

                    $step3Icon.removeClass('bg-success bg-warning bg-primary').addClass('bg-danger').html('<i class="fa-solid fa-ban"></i>');
                    $step3Status.removeClass('text-success text-warning text-muted text-primary').addClass('text-danger font-weight-bold').text('Tidak Dicairkan');
                } else {
                    $step2Icon.removeClass('bg-secondary bg-success bg-danger').addClass('bg-primary').html('<i class="fa-solid fa-spinner fa-spin"></i>');
                    $step2Status.removeClass('text-success text-danger text-muted').addClass('text-primary font-weight-bold').text('Proses Pemeriksaan');

                    $step3Icon.removeClass('bg-success bg-warning bg-danger').addClass('bg-secondary').html('<i class="fa-solid fa-money-bill-wave"></i>');
                    $step3Status.removeClass('text-success text-danger text-primary').addClass('text-muted font-weight-bold').text('Menunggu Approval');
                }

                // Render Itemized Receipt Rows
                var $tbody = $('#modal-tbody-items');
                $tbody.empty();

                if (b.items && b.items.length > 0) {
                    b.items.forEach(function(it, idx) {
                        var imgBtn = it.receipt_image ? '<a href="<?php echo base_url("uploads/reimbursement/"); ?>' + it.receipt_image + '" target="_blank" class="btn btn-xs btn-outline-indigo px-2 py-1" style="border-radius:4px; font-size:10px;"><i class="fa-solid fa-image"></i> Lihat</a>' : '<span class="text-muted">-</span>';
                        var row = '<tr>' +
                            '<td>' + (idx + 1) + '</td>' +
                            '<td>' + imgBtn + '</td>' +
                            '<td><strong class="text-dark d-block" style="font-size:12px;">' + (it.title || 'Nota') + '</strong>' + (it.description ? '<small class="text-muted d-block">' + it.description + '</small>' : '') + '</td>' +
                            '<td><span class="badge badge-light border text-secondary" style="font-size:9px;">' + (it.category || 'Umum') + '</span></td>' +
                            '<td class="text-right font-weight-bold text-success">Rp ' + parseFloat(it.amount || 0).toLocaleString('id-ID') + '</td>' +
                            '</tr>';
                        $tbody.append(row);
                    });
                } else {
                    $tbody.html('<tr><td colspan="5" class="text-center py-3 text-muted">Tidak ada rincian item nota.</td></tr>');
                }

                $('#reimbursementBatchDetailModal').appendTo("body").modal('show');
            });

            // Notice History Tab Filters & Search
            $(document).on('click', '.notice-filter-btn', function() {
                $('.notice-filter-btn').removeClass('btn-primary active').addClass('btn-outline-secondary');
                $(this).removeClass('btn-outline-secondary').addClass('btn-primary active');
                filterNoticeCards();
            });

            $(document).on('keyup', '#notice-history-search', function() {
                filterNoticeCards();
            });

            function filterNoticeCards() {
                const activeFilter = $('.notice-filter-btn.active').data('filter') || 'all';
                const keyword = ($('#notice-history-search').val() || '').toLowerCase().trim();

                $('.notice-history-card').each(function() {
                    const cardStatus = $(this).data('status');
                    const cardText = ($(this).data('title') || '').toLowerCase();

                    let matchFilter = true;
                    if (activeFilter === 'pending' && cardStatus !== 'pending') matchFilter = false;
                    if (activeFilter === 'signed' && cardStatus !== 'signed') matchFilter = false;

                    let matchSearch = true;
                    if (keyword.length > 0 && !cardText.includes(keyword)) matchSearch = false;

                    if (matchFilter && matchSearch) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            }
        });
    </script>

    <!-- ==========================================
         THREE.JS WEBGL & 3D TILT ENGINE
         ========================================== -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Live Time Ticker in Header
            function updateLiveTicker() {
                const now = new Date();
                const hrs = String(now.getHours()).padStart(2, '0');
                const mins = String(now.getMinutes()).padStart(2, '0');
                const secs = String(now.getSeconds()).padStart(2, '0');
                const ticker = document.getElementById('live-time-ticker');
                if (ticker) {
                    ticker.textContent = hrs + ':' + mins + ':' + secs + ' WIB';
                }
            }
            setInterval(updateLiveTicker, 1000);
            updateLiveTicker();

            // 2. VanillaTilt for 3D Glass Surface Interaction
            if (typeof VanillaTilt !== 'undefined') {
                VanillaTilt.init(document.querySelectorAll(".stat-card, .tilt-card"), {
                    max: 8,
                    speed: 400,
                    glare: true,
                    "max-glare": 0.15,
                    scale: 1.01
                });
            }

            // 3. Three.js Interactive 3D WebGL Ambient Scene
            function initThreeAmbientScene() {
                const canvas = document.getElementById('three-ambient-canvas');
                if (!canvas || typeof THREE === 'undefined') return;

                const parent = canvas.parentElement;
                let width = parent.clientWidth || window.innerWidth;
                let height = parent.clientHeight || window.innerHeight;

                const scene = new THREE.Scene();
                const camera = new THREE.PerspectiveCamera(55, width / height, 1, 2000);
                camera.position.z = 450;

                const renderer = new THREE.WebGLRenderer({ 
                    canvas: canvas, 
                    alpha: true, 
                    antialias: true,
                    powerPreference: "high-performance" 
                });
                renderer.setSize(width, height);
                renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

                // Generate Floating 3D Geometric Nodes
                const particleCount = 80;
                const geometry = new THREE.BufferGeometry();
                const positions = new Float32Array(particleCount * 3);
                const velocities = [];
                const colors = new Float32Array(particleCount * 3);

                // Luxury Curated HSL Colors: Indigo (#6366F1), Electric Cyan (#06B6D4), Violet (#8B5CF6)
                const palette = [
                    new THREE.Color(0x6366F1),
                    new THREE.Color(0x06B6D4),
                    new THREE.Color(0x8B5CF6),
                    new THREE.Color(0x3B82F6),
                    new THREE.Color(0x10B981)
                ];

                for (let i = 0; i < particleCount; i++) {
                    positions[i * 3] = (Math.random() - 0.5) * 900;
                    positions[i * 3 + 1] = (Math.random() - 0.5) * 650;
                    positions[i * 3 + 2] = (Math.random() - 0.5) * 350;

                    velocities.push({
                        x: (Math.random() - 0.5) * 0.45,
                        y: (Math.random() - 0.5) * 0.45,
                        z: (Math.random() - 0.5) * 0.25
                    });

                    const color = palette[i % palette.length];
                    colors[i * 3] = color.r;
                    colors[i * 3 + 1] = color.g;
                    colors[i * 3 + 2] = color.b;
                }

                geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
                geometry.setAttribute('color', new THREE.BufferAttribute(colors, 3));

                // Particle Texture Creation (Smooth Round Glow Dot)
                const circleCanvas = document.createElement('canvas');
                circleCanvas.width = 64;
                circleCanvas.height = 64;
                const ctx = circleCanvas.getContext('2d');
                const grad = ctx.createRadialGradient(32, 32, 0, 32, 32, 32);
                grad.addColorStop(0, 'rgba(255,255,255,1)');
                grad.addColorStop(0.3, 'rgba(255,255,255,0.85)');
                grad.addColorStop(0.7, 'rgba(255,255,255,0.3)');
                grad.addColorStop(1, 'rgba(255,255,255,0)');
                ctx.fillStyle = grad;
                ctx.beginPath();
                ctx.arc(32, 32, 32, 0, Math.PI * 2);
                ctx.fill();

                const particleTexture = new THREE.CanvasTexture(circleCanvas);

                const pointMaterial = new THREE.PointsMaterial({
                    size: 9,
                    map: particleTexture,
                    vertexColors: true,
                    transparent: true,
                    opacity: 0.75,
                    blending: THREE.NormalBlending,
                    depthWrite: false
                });

                const particleMesh = new THREE.Points(geometry, pointMaterial);
                scene.add(particleMesh);

                // Connecting Lines
                const lineMaterial = new THREE.LineBasicMaterial({
                    color: 0x6366F1,
                    transparent: true,
                    opacity: 0.12,
                    blending: THREE.NormalBlending
                });

                let lineSegments = null;

                // Mouse Parallax Track
                let targetX = 0;
                let targetY = 0;
                let currentX = 0;
                let currentY = 0;

                window.addEventListener('mousemove', function(e) {
                    const rect = canvas.getBoundingClientRect();
                    targetX = (e.clientX - rect.left - width / 2) * 0.25;
                    targetY = (e.clientY - rect.top - height / 2) * 0.25;
                }, { passive: true });

                // Responsive Window Resize
                window.addEventListener('resize', function() {
                    width = parent.clientWidth || window.innerWidth;
                    height = parent.clientHeight || window.innerHeight;
                    camera.aspect = width / height;
                    camera.updateProjectionMatrix();
                    renderer.setSize(width, height);
                }, { passive: true });

                // Animation Clock & Loop
                const clock = new THREE.Clock();

                function animate() {
                    requestAnimationFrame(animate);

                    const delta = clock.getElapsedTime();

                    // Smooth Camera Parallax Lerp
                    currentX += (targetX - currentX) * 0.035;
                    currentY += (targetY - currentY) * 0.035;
                    camera.position.x = currentX;
                    camera.position.y = -currentY;
                    camera.lookAt(scene.position);

                    // Update Particle Positions
                    const pos = geometry.attributes.position.array;
                    const connectionPositions = [];

                    for (let i = 0; i < particleCount; i++) {
                        pos[i * 3] += velocities[i].x;
                        pos[i * 3 + 1] += velocities[i].y + Math.sin(delta * 0.8 + i) * 0.12;
                        pos[i * 3 + 2] += velocities[i].z;

                        // Bounds reflection
                        if (pos[i * 3] < -500 || pos[i * 3] > 500) velocities[i].x *= -1;
                        if (pos[i * 3 + 1] < -380 || pos[i * 3 + 1] > 380) velocities[i].y *= -1;
                        if (pos[i * 3 + 2] < -200 || pos[i * 3 + 2] > 200) velocities[i].z *= -1;

                        // Check distances for line lattice
                        for (let j = i + 1; j < particleCount; j++) {
                            const dx = pos[i * 3] - pos[j * 3];
                            const dy = pos[i * 3 + 1] - pos[j * 3 + 1];
                            const dz = pos[i * 3 + 2] - pos[j * 3 + 2];
                            const dist = Math.sqrt(dx * dx + dy * dy + dz * dz);

                            if (dist < 130) {
                                connectionPositions.push(
                                    pos[i * 3], pos[i * 3 + 1], pos[i * 3 + 2],
                                    pos[j * 3], pos[j * 3 + 1], pos[j * 3 + 2]
                                );
                            }
                        }
                    }

                    geometry.attributes.position.needsUpdate = true;

                    // Rebuild connections
                    if (lineSegments) {
                        scene.remove(lineSegments);
                        lineSegments.geometry.dispose();
                    }

                    if (connectionPositions.length > 0) {
                        const lineGeo = new THREE.BufferGeometry();
                        lineGeo.setAttribute('position', new THREE.Float32BufferAttribute(connectionPositions, 3));
                        lineSegments = new THREE.LineSegments(lineGeo, lineMaterial);
                        scene.add(lineSegments);
                    }

                    renderer.render(scene, camera);
                }

                animate();
            }

            // Launch Three.js Scene
            setTimeout(initThreeAmbientScene, 100);
        });
    </script>

</body>
</html>
