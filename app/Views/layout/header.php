<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'SICOR-OPEO'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-size: 0.9rem; background-color: #f4f6f9; }
        .sidebar {
            height: 100vh;
            background-color: #212529;
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            width: 250px;
            overflow-y: auto;
            z-index: 1000;
        }
        .sidebar-header {
            padding: 1rem;
            background-color: #1a1d20;
            text-align: center;
        }
        .sidebar a {
            color: #ADB5BD;
            text-decoration: none;
            display: block;
            padding: 12px 20px;
            border-bottom: 1px solid #2c3034;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: #7d0e32;
            color: white;
        }
        .sidebar i { margin-right: 10px; }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        .top-bar {
            background: white;
            padding: 10px 20px;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        @media (max-width: 768px) {
            .sidebar { width: 100%; height: auto; position: relative; }
            .main-content { margin-left: 0; }
        }
        .card-stat { border-left: 4px solid #7d0e32; }
    </style>
</head>
<body>
