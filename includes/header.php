<?php
/**
 * Hospital Call System - Header Template
 * King Khalid Hospital, Hail
 */

if (!defined('HOSPITAL_CALL_SYSTEM')) {
    exit('Direct access not permitted');
}

$pageTitle = $pageTitle ?? 'Hospital Call System';
$pageName = $pageName ?? 'landing';
$pageDescription = $pageDescription ?? 'King Khalid Hospital Hail — Internal Announcement & Paging System';
$bodyClass = $bodyClass ?? '';
$rtl = $rtl ?? false;
?>
<!DOCTYPE html>
<html lang="<?= $rtl ? 'ar' : 'en' ?>" dir="<?= $rtl ? 'rtl' : 'ltr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes, viewport-fit=cover">
    <meta name="description" content="<?= htmlspecialchars($pageDescription) ?>">
    <meta name="theme-color" content="#1F2A6D">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

    <!-- Open Graph / Social Media -->
    <meta property="og:title" content="<?= htmlspecialchars($pageTitle) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($pageDescription) ?>">
    <meta property="og:type" content="website">

    <!-- Favicon / Icons -->
    <link rel="icon" type="image/x-icon" href="assets/icons/favicon.ico">
    <link rel="manifest" href="manifest.json">

    <title><?= htmlspecialchars($pageTitle) ?> — King Khalid Hospital Hail</title>

    <!-- Font Awesome 6 (local) -->
    <link rel="stylesheet" href="assets/vendor/fontawesome/css/all.min.css">

    <!-- Google Fonts (local) -->
    <link href="assets/vendor/fonts/fonts.css" rel="stylesheet">

    <!-- Bootstrap 5 (local) -->
    <link href="assets/vendor/bootstrap/bootstrap.min.css" rel="stylesheet">

    <!-- Main Stylesheet -->
    <link href="assets/css/main.css?v=3.3" rel="stylesheet">
    <link href="assets/css/pages.css?v=3.3" rel="stylesheet">

    <style>
        .skip-link {
            position: absolute;
            top: -100px;
            left: 0;
            background: #1F2A6D;
            color: #fff;
            padding: 8px 16px;
            z-index: 9999;
            font-size: 0.9rem;
            text-decoration: none;
            border-radius: 0 0 4px 0;
        }
        .skip-link:focus {
            top: 0;
        }
    </style>

    <?php if ($rtl): ?>
    <style>
        body { font-family: 'Cairo', sans-serif; }
        .sidebar { right: 0; left: auto; }
        .main-wrap { margin-right: var(--sidebar-width-desktop); margin-left: 0; }
        @media (max-width: 767px) { .main-wrap { margin-right: 0; } }
        .sb-nav-item i { margin-left: 12px; margin-right: 0; }
        .input-wrap .input-icon { left: auto; right: 14px; }
        .input-wrap .form-input { padding-left: 14px; padding-right: 42px; }
    </style>
    <?php endif; ?>

    <?php if (isset($pageStyles)): ?>
    <style><?= $pageStyles ?></style>
    <?php endif; ?>
</head>
<body class="<?= htmlspecialchars($bodyClass) ?>" data-page="<?= htmlspecialchars($pageName) ?>">

    <a href="#main-content" class="skip-link">Skip to main content</a>
