<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Sistema de Monitoramento'; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="<?php echo ASSETS_URL; ?>css/style.css" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo ASSETS_URL; ?>images/favicon.ico">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="<?php echo BASE_URL; ?>">
                <i class="bi bi-shield-check me-2"></i>
                <?php echo SYSTEM_NAME; ?>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>">
                            <i class="bi bi-house-door me-1"></i>Dashboard
                        </a>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-people me-1"></i>Cadastros
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>prestadores">
                                <i class="bi bi-person-badge me-2"></i>Prestadores
                            </a></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>clientes">
                                <i class="bi bi-building me-2"></i>Clientes
                            </a></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>agentes">
                                <i class="bi bi-person-check me-2"></i>Agentes
                            </a></li>
                        </ul>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-clipboard-check me-1"></i>Operações
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>atendimentos">
                                <i class="bi bi-headset me-2"></i>Atendimentos
                            </a></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>rondas">
                                <i class="bi bi-arrow-repeat me-2"></i>Rondas Periódicas
                            </a></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>ocorrencias">
                                <i class="bi bi-exclamation-triangle me-2"></i>Ocorrências Veiculares
                            </a></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>vigilancia">
                                <i class="bi bi-car-front me-2"></i>Vigilância Veicular
                            </a></li>
                        </ul>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>relatorios">
                            <i class="bi bi-file-earmark-text me-1"></i>Relatórios
                        </a>
                    </li>
                </ul>
                
                <!-- Busca rápida -->
                <form class="d-flex me-3" role="search">
                    <div class="input-group">
                        <input class="form-control form-control-sm" type="search" placeholder="Buscar..." id="searchInput">
                        <button class="btn btn-outline-light btn-sm" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
                
                <!-- Controles do usuário -->
                <ul class="navbar-nav">
                    <!-- Toggle tema -->
                    <li class="nav-item">
                        <button class="btn btn-outline-light btn-sm me-2" id="themeToggle" title="Alternar tema">
                            <i class="bi bi-sun-fill" id="themeIcon"></i>
                        </button>
                    </li>
                    
                    <!-- Notificações -->
                    <li class="nav-item dropdown">
                        <a class="nav-link position-relative" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-bell"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notificationBadge">
                                0
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" style="width: 300px;">
                            <li><h6 class="dropdown-header">Notificações</h6></li>
                            <li><hr class="dropdown-divider"></li>
                            <div id="notificationList">
                                <li><span class="dropdown-item-text text-muted">Nenhuma notificação</span></li>
                            </div>
                        </ul>
                    </li>
                    
                    <!-- Info do sistema -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-gear"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><h6 class="dropdown-header"><?php echo SYSTEM_NAME; ?></h6></li>
                            <li><span class="dropdown-item-text">Versão <?php echo SYSTEM_VERSION; ?></span></li>
                            <li><span class="dropdown-item-text">Por <?php echo SYSTEM_AUTHOR; ?></span></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="main-content">
        <div class="container-fluid py-4">
            
            <!-- Alertas -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['warning'])): ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <?php echo $_SESSION['warning']; unset($_SESSION['warning']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['info'])): ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="bi bi-info-circle me-2"></i>
                    <?php echo $_SESSION['info']; unset($_SESSION['info']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

