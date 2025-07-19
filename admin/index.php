<?php
// Include authentication check
include 'auth_check.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Admin Panel</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
  
  <style>
    :root {
      --primary-color: #dc2626;
      --primary-dark: #b91c1c;
      --secondary-color: #1f2937;
      --accent-color: #3b82f6;
      --success-color: #10b981;
      --warning-color: #f59e0b;
      --danger-color: #ef4444;
      --light-color: #f8fafc;
      --dark-color: #111827;
      --sidebar-width: 320px;
      --border-radius: 16px;
      --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
      --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
      --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
      --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #fef7ed 0%, #fed7aa 100%);
      min-height: 100vh;
      overflow-x: hidden;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    body.sidebar-open {
      background: linear-gradient(135deg, rgba(254, 247, 237, 0.8) 0%, rgba(254, 215, 170, 0.8) 100%);
    }

    /* Floating Toggle Button */
    .toggle-btn {
      position: fixed;
      top: 25px;
      left: 25px;
      width: 65px;
      height: 65px;
      background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
      border: none;
      border-radius: 50%;
      color: white;
      cursor: pointer;
      z-index: 3000;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      box-shadow: var(--shadow-xl);
      display: flex;
      align-items: center;
      justify-content: center;
      backdrop-filter: blur(15px);
      border: 4px solid rgba(255, 255, 255, 0.15);
    }

    .toggle-btn:hover {
      transform: scale(1.1) rotate(5deg);
      box-shadow: 0 25px 50px -12px rgba(220, 38, 38, 0.5);
    }

    .toggle-btn:active {
      transform: scale(0.95);
    }

    .toggle-btn.active {
      background: linear-gradient(135deg, var(--danger-color) 0%, #dc2626 100%);
      transform: rotate(90deg) scale(1.1);
      left: calc(var(--sidebar-width) + 25px);
    }

    /* Hamburger Icon Animation */
    .hamburger {
      position: relative;
      width: 24px;
      height: 18px;
      transition: all 0.3s ease;
    }

    .hamburger span {
      position: absolute;
      width: 100%;
      height: 2px;
      background: white;
      border-radius: 2px;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      left: 0;
    }

    .hamburger span:nth-child(1) { top: 0; }
    .hamburger span:nth-child(2) { top: 8px; }
    .hamburger span:nth-child(3) { top: 16px; }

    .toggle-btn.active .hamburger span:nth-child(1) {
      transform: rotate(45deg) translate(6px, 6px);
    }

    .toggle-btn.active .hamburger span:nth-child(2) {
      opacity: 0;
      transform: translateX(20px);
    }

    .toggle-btn.active .hamburger span:nth-child(3) {
      transform: rotate(-45deg) translate(6px, -6px);
    }

    /* Overlay */
    .overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(220, 38, 38, 0.3);
      backdrop-filter: blur(8px);
      z-index: 1500;
      opacity: 0;
      visibility: hidden;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .overlay.active {
      opacity: 1;
      visibility: visible;
    }

    /* Modern Sidebar */
    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      width: var(--sidebar-width);
      height: 100vh;
      background: linear-gradient(145deg, #f5f5dc 0%, #faebd7 100%);
      transform: translateX(-100%);
      transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      z-index: 2000;
      border-radius: 0 var(--border-radius) var(--border-radius) 0;
      box-shadow: var(--shadow-xl);
      border-right: 3px solid #dc2626;
      overflow: hidden;
    }

    .sidebar.active {
      transform: translateX(0);
    }

    /* Sidebar Header */
    .sidebar-header {
      background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
      padding: 2.5rem 2rem 2rem 2rem;
      color: white;
      position: relative;
      overflow: hidden;
      min-height: 120px;
    }

    .sidebar-header::before {
      content: '';
      position: absolute;
      top: 0;
      right: 0;
      width: 100px;
      height: 100px;
      background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
      transform: translate(30%, -30%);
    }

    .sidebar-header h4 {
      font-size: 1.5rem;
      font-weight: 700;
      margin: 0;
      display: flex;
      align-items: center;
      gap: 0.75rem;
      position: relative;
      z-index: 10;
    }

    .sidebar-header .subtitle {
      font-size: 0.875rem;
      opacity: 0.9;
      margin-top: 0.5rem;
      font-weight: 400;
    }

    /* Navigation Links */
    .nav-links {
      flex: 1;
      padding: 1.5rem 0;
      overflow-y: auto;
      scrollbar-width: none;
      -ms-overflow-style: none;
    }

    .nav-links::-webkit-scrollbar {
      width: 4px;
    }

    .nav-links::-webkit-scrollbar-track {
      background: rgba(255, 255, 255, 0.1);
      border-radius: 2px;
    }

    .nav-links::-webkit-scrollbar-thumb {
      background: rgba(255, 255, 255, 0.3);
      border-radius: 2px;
    }

    .nav-links a {
      display: flex;
      align-items: center;
      padding: 1.2rem 1.5rem;
      color: #dc2626 !important;
      text-decoration: none;
      font-size: 1rem;
      font-weight: 500;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      cursor: pointer;
      position: relative;
      border-radius: 12px;
      margin: 0.3rem 1rem 0.3rem 0.5rem;
      background: #f5f5dc;
      border-left: 4px solid #f5f5dc;
    }

    .nav-links a::before {
      content: '';
      position: absolute;
      left: 0;
      top: 0;
      bottom: 0;
      width: 4px;
      background: #dc2626;
      transform: scaleY(0);
      transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      border-radius: 0 2px 2px 0;
    }

    .nav-links a::after {
      content: '';
      position: absolute;
      right: 1rem;
      top: 50%;
      transform: translateY(-50%);
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: #dc2626;
      opacity: 0;
      transition: all 0.3s ease;
    }

    .nav-links a:hover {
      background: #dc2626;
      color: #f5f5dc !important;
      transform: translateX(8px);
      border-left-color: #dc2626;
      box-shadow: 0 4px 12px rgba(220, 38, 38, 0.2);
    }

    .nav-links a:hover::before {
      transform: scaleY(1);
    }

    .nav-links a:hover::after {
      opacity: 1;
      background: #dc2626;
    }

    .nav-links a.active {
      background: #dc2626;
      color: #f5f5dc !important;
      transform: translateX(8px);
      border-left-color: #dc2626;
      box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4);
      font-weight: 600;
    }

    .nav-links a.active::before {
      transform: scaleY(1);
      background: #f5f5dc;
    }

    .nav-links a.active::after {
      opacity: 1;
      background: #f5f5dc;
      animation: pulse 2s infinite;
    }

    .nav-links i {
      margin-right: 1rem;
      font-size: 1.2rem;
      width: 24px;
      text-align: center;
      opacity: 0.8;
    }

    .nav-links a:hover i,
    .nav-links a.active i {
      opacity: 1;
      transform: scale(1.1);
    }

    /* Logout Section */
    .logout {
      padding: 1.5rem;
      border-top: 2px solid #ddd;
      background: rgba(220, 38, 38, 0.05);
    }

    .logout a {
      color: #dc2626;
      text-decoration: none;
      display: flex;
      align-items: center;
      font-size: 0.95rem;
      font-weight: 500;
      transition: all 0.3s ease;
      padding: 0.75rem;
      border-radius: 8px;
    }

    .logout a:hover {
      color: #f5f5dc;
      background: #dc2626;
    }

    .logout i {
      margin-right: 0.75rem;
      font-size: 1.1rem;
    }

    /* Content Area */
    .content {
      width: 100%;
      height: 100vh;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
    }

    .content.blur {
      filter: blur(4px) brightness(0.7);
      transform: scale(0.95);
    }

    iframe {
      width: 100%;
      height: 100vh;
      border: none;
      display: block;
      border-radius: var(--border-radius);
      box-shadow: var(--shadow-lg);
    }

    /* Loading Animation */
    @keyframes pulse {
      0%, 100% { opacity: 1; }
      50% { opacity: 0.5; }
    }

    .loading {
      animation: pulse 2s cubic-bezier(0.4, 0, 0.2, 1) infinite;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .toggle-btn {
        width: 50px;
        height: 50px;
        top: 20px;
        left: 20px;
      }

      .sidebar {
        width: 100%;
        border-radius: 0;
      }

      .nav-links a {
        padding: 1.25rem 1.5rem;
        font-size: 1rem;
      }

      .sidebar-header {
        padding: 1.5rem;
      }
    }

    /* Custom Scrollbar for Webkit */
    .nav-links::-webkit-scrollbar {
      width: 4px;
    }

    .nav-links::-webkit-scrollbar-track {
      background: transparent;
    }

    .nav-links::-webkit-scrollbar-thumb {
      background: rgba(255, 255, 255, 0.2);
      border-radius: 2px;
    }

    .nav-links::-webkit-scrollbar-thumb:hover {
      background: rgba(255, 255, 255, 0.3);
    }
  </style>
</head>
<body>

<!-- Floating Toggle Button -->
<button class="toggle-btn" id="toggleBtn" onclick="toggleSidebar()">
  <div class="hamburger">
    <span></span>
    <span></span>
    <span></span>
  </div>
</button>

<!-- Overlay -->
<div class="overlay" id="overlay" onclick="closeSidebar()"></div>

<!-- Modern Sidebar -->
<div class="sidebar" id="sidebar">
  <div class="sidebar-header">
    <h4>
      <i class="fas fa-shield-alt"></i>
      Admin Panel
    </h4>
    <div class="subtitle">Sistem Donor Darah</div>
  </div>
  
  <div class="nav-links">
    <a onclick="loadPage('welcome.php')" id="nav-welcome">
      <i class="fas fa-home"></i> 
      Dashboard
    </a>
    <a onclick="loadPage('permintaan.php')" id="nav-permintaan">
      <i class="fas fa-hand-holding-medical"></i> 
      Permintaan Darah
    </a>
    <a onclick="loadPage('users.php')" id="nav-users">
      <i class="fas fa-users-cog"></i> 
      Kelola Pengguna
    </a>
    <a onclick="loadPage('laporan.php')" id="nav-laporan">
      <i class="fas fa-chart-line"></i> 
      Laporan & Statistik
    </a>
    <a onclick="loadPage('manage_notifications.php')" id="nav-notifications">
      <i class="fas fa-bullhorn"></i> 
      Notifikasi
    </a>
    <a onclick="loadPage('settings.php')" id="nav-settings">
      <i class="fas fa-cog"></i> 
      Pengaturan
    </a>
    <a onclick="loadPage('activity.php')" id="nav-activity">
      <i class="fas fa-history"></i> 
      Log Aktivitas
    </a>
  </div>
  
  <div class="logout">
    <a href="../logout.php" onclick="return confirm('Yakin ingin keluar?')">
      <i class="fas fa-sign-out-alt"></i> 
      Logout
    </a>
  </div>
</div>

<!-- Content Area -->
<div class="content" id="content">
  <iframe id="mainFrame" src="welcome.php" title="Admin Content" class="loading"></iframe>
</div>

<!-- Enhanced JavaScript -->
<script>
  // Global variables
  let isInitialized = false;
  let currentActivePage = 'welcome';
  
  // Initialize on page load
  document.addEventListener('DOMContentLoaded', function() {
    initializeAdminPanel();
  });
  
  function initializeAdminPanel() {
    const toggleBtn = document.getElementById('toggleBtn');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const content = document.getElementById('content');
    const body = document.body;
    const iframe = document.getElementById('mainFrame');
    
    // Set initial active menu
    setActiveMenu('nav-welcome');
    
    // Remove loading class when iframe loads
    iframe.addEventListener('load', function() {
      this.classList.remove('loading');
    });
    
    isInitialized = true;
  }
  
  function toggleSidebar() {
    const toggleBtn = document.getElementById('toggleBtn');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const content = document.getElementById('content');
    const body = document.body;
    
    const isActive = sidebar.classList.contains('active');
    
    if (!isActive) {
      // Open sidebar
      openSidebar();
    } else {
      // Close sidebar
      closeSidebar();
    }
  }
  
  function openSidebar() {
    const toggleBtn = document.getElementById('toggleBtn');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const content = document.getElementById('content');
    const body = document.body;
    
    // Add active classes
    toggleBtn.classList.add('active');
    sidebar.classList.add('active');
    overlay.classList.add('active');
    content.classList.add('blur');
    body.classList.add('sidebar-open');
    
    // Disable scroll on body
    body.style.overflow = 'hidden';
  }
  
  function closeSidebar() {
    const toggleBtn = document.getElementById('toggleBtn');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const content = document.getElementById('content');
    const body = document.body;
    
    // Remove active classes
    toggleBtn.classList.remove('active');
    sidebar.classList.remove('active');
    overlay.classList.remove('active');
    content.classList.remove('blur');
    body.classList.remove('sidebar-open');
    
    // Enable scroll on body
    body.style.overflow = 'auto';
  }
  
  function loadPage(page) {
    const iframe = document.getElementById('mainFrame');
    let navId = 'nav-' + page.replace('.php', '').replace('-', '').replace('_', '');
    
    // Handle special cases for navigation ID mapping
    if (page === 'manage_notifications.php') {
      navId = 'nav-notifications';
    }
    
    // Add loading class
    iframe.classList.add('loading');
    
    // Set source
    iframe.src = page;
    
    // Set active menu
    setActiveMenu(navId);
    
    // Store current page
    currentActivePage = page.replace('.php', '').replace('-', '').replace('_', '');
    
    // Auto-close sidebar on mobile or after page load
    setTimeout(() => {
      if (window.innerWidth <= 768) {
        closeSidebar();
      } else {
        // On desktop, auto-close after a delay
        setTimeout(() => {
          closeSidebar();
        }, 800);
      }
    }, 300);
  }
  
  function setActiveMenu(activeId) {
    // Remove active class from all menu items
    const menuItems = document.querySelectorAll('.nav-links a');
    menuItems.forEach(item => {
      item.classList.remove('active');
    });
    
    // Add active class to selected menu
    const activeItem = document.getElementById(activeId);
    if (activeItem) {
      activeItem.classList.add('active');
    }
  }
  
  // Auto-close sidebar when clicking on content area (desktop)
  document.addEventListener('click', function(e) {
    const content = document.getElementById('content');
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('toggleBtn');
    
    if (window.innerWidth > 768 && 
        sidebar.classList.contains('active') && 
        content.contains(e.target) &&
        !toggleBtn.contains(e.target)) {
      closeSidebar();
    }
  });
  
  // Handle escape key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      closeSidebar();
    }
  });
  
  // Handle resize
  window.addEventListener('resize', function() {
    if (window.innerWidth > 768) {
      closeSidebar();
    }
  });
  
  // Add smooth scroll behavior
  document.documentElement.style.scrollBehavior = 'smooth';
  
  // Prevent context menu on toggle button (optional)
  document.getElementById('toggleBtn').addEventListener('contextmenu', function(e) {
    e.preventDefault();
  });
</script>

</body>
</html>

