<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Segoe UI', sans-serif; background: #F3F4F6; }
    .sidebar { position: fixed; left: 0; top: 0; width: 260px; height: 100vh; background: #172554; display: flex; flex-direction: column; z-index: 50; }
    .sidebar-logo { display: flex; align-items: center; gap: 12px; padding: 22px 20px; border-bottom: 1px solid #374151; }
    .sidebar-logo img { width: 46px; height: 46px; border-radius: 12px; object-fit: cover; }
    .sidebar-logo .brand { color: white; font-size: 18px; font-weight: bold; line-height: 1.2; }
    .sidebar-logo .motto { color: #D4A72C; font-size: 11px; font-style: italic; }
    .nav { flex: 1; padding: 18px 14px; overflow-y: auto; }
    .nav a { display: flex; align-items: center; gap: 12px; color: #CBD5E1; text-decoration: none; padding: 12px 15px; border-radius: 10px; font-size: 14px; margin-bottom: 4px; transition: all 0.2s; }
    .nav a:hover { background: #2D3748; color: white; }
    .nav a.active { background: #D4A72C; color: #172554; font-weight: bold; }
    .nav .section-title { color: #6B7280; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; padding: 15px 15px 6px; }
    .sidebar-footer { padding: 18px 20px; border-top: 1px solid #374151; color: #9CA3AF; font-size: 13px; }
    .main { margin-left: 260px; min-height: 100vh; display: flex; flex-direction: column; }
    .topbar { background: white; padding: 16px 30px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e2e8f0; position: sticky; top: 0; z-index: 40; }
    .topbar .page-title { color: #172554; font-size: 20px; font-weight: bold; }
    .topbar-right { display: flex; align-items: center; gap: 18px; }
    .notif-bell { position: relative; display: inline-flex; align-items: center; justify-content: center; width: 42px; height: 42px; border-radius: 50%; background: #F3F4F6; color: #172554; text-decoration: none; border: 1px solid #E5E7EB; transition: background .15s ease, transform .15s ease, box-shadow .15s ease; }
    .notif-bell:hover { background: #E8ECF3; transform: translateY(-1px); box-shadow: 0 4px 10px rgba(23,37,84,0.12); }
    .notif-bell:active { transform: translateY(0); }
    .notif-badge { position: absolute; top: -4px; right: -4px; min-width: 20px; height: 20px; padding: 0 6px; background: #DC2626; color: #fff; font-size: 11px; font-weight: bold; border-radius: 20px; border: 2px solid #fff; display: flex; align-items: center; justify-content: center; line-height: 1; }
    .profile { display: flex; align-items: center; gap: 10px; cursor: pointer; }
    .profile .avatar { width: 40px; height: 40px; border-radius: 50%; background: #D4A72C; color: #172554; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 16px; }
    .profile img.avatar { object-fit: cover; background: #E5E7EB; }
    .profile .info { line-height: 1.2; }
    .profile .info .name { color: #172554; font-size: 14px; font-weight: bold; }
    .profile .info .role { color: #6B7280; font-size: 12px; }
    .content { flex: 1; padding: 30px; }
    .panel { background: white; border-radius: 16px; padding: 25px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); border: 1px solid #e2e8f0; margin-bottom: 25px; }
    .panel h3 { color: #172554; font-size: 18px; margin-bottom: 18px; }
    table { width: 100%; border-collapse: collapse; }
    th { text-align: left; color: #6B7280; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; padding: 10px 12px; border-bottom: 2px solid #F3F4F6; }
    td { padding: 13px 12px; font-size: 14px; color: #374151; border-bottom: 1px solid #F3F4F6; }
    tr:hover td { background: #F9FAFB; }
    .badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; }
    .badge-active { background: #dcfce7; color: #15803D; }
    .badge-pending { background: #FEF3C7; color: #EA580C; }
    .badge-rejected { background: #fee2e2; color: #DC2626; }
    .badge-present { background: #dcfce7; color: #15803D; }
    .badge-absent { background: #fee2e2; color: #DC2626; }
    .badge-retard { background: #FEF3C7; color: #EA580C; }
    .badge-permission { background: #dbeafe; color: #1D4ED8; }
    .btn { display: inline-block; padding: 7px 16px; border-radius: 8px; font-size: 13px; font-weight: bold; text-decoration: none; cursor: pointer; border: none; transition: opacity 0.2s; }
    .btn:hover { opacity: 0.85; }
    .btn-gold { background: #D4A72C; color: #172554; }
    .btn-green { background: #15803D; color: white; }
    .btn-red { background: #DC2626; color: white; }
    .btn-navy { background: #172554; color: white; }
    .btn-light { background: #F3F4F6; color: #172554; }
    .form-group { margin-bottom: 18px; }
    .form-group label { display: block; color: #374151; font-size: 14px; font-weight: bold; margin-bottom: 6px; }
    .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 12px 14px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 15px; font-family: inherit; }
    .empty-state { text-align: center; padding: 40px; color: #9CA3AF; }
    .empty-state .icon { font-size: 44px; margin-bottom: 12px; }
    .footer { background: #172554; color: #9CA3AF; text-align: center; padding: 22px; font-size: 14px; }
    .footer .brand { color: white; font-weight: bold; }
    .footer .motto { color: #D4A72C; font-size: 12px; margin-top: 4px; letter-spacing: 2px; }
    .nav-toggle { display: none; }
    .hamburger { display: none; }
    @media (max-width: 900px) {
        .main { margin-left: 0; }
        .sidebar { transform: translateX(-100%); transition: transform 0.25s ease; box-shadow: 4px 0 24px rgba(0,0,0,0.35); }
        .nav-toggle:checked ~ .sidebar { transform: translateX(0); }
        .hamburger { display: flex; align-items: center; justify-content: center; position: fixed; top: 12px; left: 12px; z-index: 60; width: 42px; height: 42px; border-radius: 10px; background: #172554; color: #fff; font-size: 20px; cursor: pointer; box-shadow: 0 2px 8px rgba(0,0,0,0.25); }
        .topbar { padding: 12px 16px 12px 64px; }
        .topbar .page-title { font-size: 17px; }
        .topbar .profile .info { display: none; }
        .content { padding: 16px; }
        .panel { padding: 16px; overflow-x: auto; }
        .panel table { min-width: 520px; }
        h1 { font-size: 20px; }
    }
    @media (max-width: 600px) {
        .topbar-right { gap: 12px; }
        .profile .avatar { width: 34px; height: 34px; font-size: 14px; }
        .notif-bell { width: 38px; height: 38px; }
    }
</style>
