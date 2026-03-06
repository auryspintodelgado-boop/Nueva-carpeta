<?php
/**
 * Vista del Dashboard
 * Sistema de Evaluación, Seguimiento y Caracterización
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Caracterización</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: #f5f5f5;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .header h1 {
            font-size: 24px;
        }
        
        .header-user {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .header-user a {
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 5px;
            background: rgba(255,255,255,0.2);
            transition: background 0.3s;
        }
        
        .header-user a:hover {
            background: rgba(255,255,255,0.3);
        }
        
        .nav {
            background: white;
            padding: 0 40px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        .nav ul {
            list-style: none;
            display: flex;
            gap: 30px;
        }
        
        .nav a {
            display: block;
            padding: 15px 0;
            color: #333;
            text-decoration: none;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
        }
        
        .nav a:hover, .nav a.active {
            color: #667eea;
            border-bottom-color: #667eea;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .stat-card h3 {
            color: #666;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 10px;
        }
        
        .stat-card .value {
            font-size: 36px;
            font-weight: bold;
            color: #333;
        }
        
        .stat-card.personas { border-left: 4px solid #667eea; }
        .stat-card.evaluaciones { border-left: 4px solid #f093fb; }
        .stat-card.seguimientos { border-left: 4px solid #4facfe; }
        .stat-card.usuarios { border-left: 4px solid #43e97b; }
        
        .section {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }
        
        .section h2 {
            color: #333;
            font-size: 20px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table th, .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        .table th {
            color: #666;
            font-weight: 500;
            font-size: 12px;
            text-transform: uppercase;
        }
        
        .table tr:hover {
            background: #f9f9f9;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .badge-pending { background: #fff3cd; color: #856404; }
        .badge-progress { background: #cce5ff; color: #004085; }
        .badge-completed { background: #d4edda; color: #155724; }
        .badge-cancelled { background: #f8d7da; color: #721c24; }
        
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .alert-success { background: #d4edda; color: #155724; }
        .alert-error { background: #f8d7da; color: #721c24; }
        .alert-warning { background: #fff3cd; color: #856404; }
        
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Sistema de Caracterización AURYS</h1>
        <div class="header-user">
            <span>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre_completo'] ?? 'Usuario'); ?></span>
            <a href="/login/logout">Cerrar Sesión</a>
        </div>
    </div>
    
    <nav class="nav">
        <ul>
            <li><a href="/dashboard" class="active">Dashboard</a></li>
            <li><a href="/personas">Personas</a></li>
            <li><a href="/evaluaciones">Evaluaciones</a></li>
            <li><a href="/seguimientos">Seguimientos</a></li>
            <li><a href="/usuarios">Usuarios</a></li>
        </ul>
    </nav>
    
    <div class="container">
        <?php if (isset($message) && $message['message']): ?>
            <div class="alert alert-<?php echo $message['type']; ?>">
                <?php echo htmlspecialchars($message['message']); ?>
            </div>
        <?php endif; ?>
        
        <div class="stats-grid">
            <div class="stat-card personas">
                <h3>Total Personas</h3>
                <div class="value"><?php echo number_format($stats['total_personas'] ?? 0); ?></div>
            </div>
            <div class="stat-card evaluaciones">
                <h3>Total Evaluaciones</h3>
                <div class="value"><?php echo number_format($stats['total_evaluaciones'] ?? 0); ?></div>
            </div>
            <div class="stat-card seguimientos">
                <h3>Total Seguimientos</h3>
                <div class="value"><?php echo number_format($stats['total_seguimientos'] ?? 0); ?></div>
            </div>
            <div class="stat-card usuarios">
                <h3>Total Usuarios</h3>
                <div class="value"><?php echo number_format($stats['total_usuarios'] ?? 0); ?></div>
            </div>
        </div>
        
        <div class="section">
            <h2>Seguimientos Activos</h2>
            <?php if (empty($seguimientosActivos)): ?>
                <div class="empty-state">No hay seguimientos activos</div>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Persona</th>
                            <th>Tipo</th>
                            <th>Responsable</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($seguimientosActivos as $s): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($s['fecha_seguimiento']); ?></td>
                            <td><?php echo htmlspecialchars($s['persona_id']); ?></td>
                            <td><?php echo htmlspecialchars($s['tipo_seguimiento_id']); ?></td>
                            <td><?php echo htmlspecialchars($s['responsable'] ?? 'N/A'); ?></td>
                            <td>
                                <span class="badge badge-<?php echo strtolower(str_replace('_', '', $s['estado_seguimiento'])); ?>">
                                    <?php echo htmlspecialchars($s['estado_seguimiento']); ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        
        <div class="section">
            <h2>Evaluaciones Pendientes</h2>
            <?php if (empty($evaluacionesPendientes)): ?>
                <div class="empty-state">No hay evaluaciones pendientes</div>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Persona</th>
                            <th>Cédula</th>
                            <th>Tipo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($evaluacionesPendientes as $e): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($e['fecha_evaluacion']); ?></td>
                            <td><?php echo htmlspecialchars($e['persona_nombre'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($e['cedula'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($e['tipo_nombre']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
