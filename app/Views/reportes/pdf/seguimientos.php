<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <style>
        @page {
            size: A4 landscape;
            margin: 1cm;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            font-size: 10px;
            color: #333;
            background: #fff;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid #2c3e50;
        }
        
        .header h1 {
            font-size: 22px;
            color: #2c3e50;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .header .subtitle {
            font-size: 12px;
            color: #7f8c8d;
            margin-bottom: 10px;
        }
        
        .header .fecha {
            font-size: 10px;
            color: #95a5a6;
            background: #ecf0f1;
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
        }
        
        .company-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding: 10px;
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
            border-radius: 8px;
        }
        
        .company-info h2 {
            font-size: 16px;
        }
        
        .company-info p {
            font-size: 10px;
            opacity: 0.9;
        }
        
        .filtros-aplicados {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 12px 15px;
            margin-bottom: 20px;
        }
        
        .filtros-aplicados h4 {
            font-size: 12px;
            color: #495057;
            margin-bottom: 8px;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 5px;
        }
        
        .filtros-aplicados .filtro-item {
            display: inline-block;
            margin-right: 15px;
            margin-bottom: 5px;
        }
        
        .filtros-aplicados .filtro-label {
            font-weight: 600;
            color: #6c757d;
        }
        
        .filtros-aplicados .filtro-valor {
            color: #212529;
            background: #e9ecef;
            padding: 2px 8px;
            border-radius: 4px;
            margin-left: 5px;
        }
        
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        thead th { 
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
            padding: 10px 6px;
            text-align: center;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 8px;
            letter-spacing: 0.5px;
            border: none;
        }
        
        thead th:first-child {
            border-top-left-radius: 8px;
        }
        
        thead th:last-child {
            border-top-right-radius: 8px;
        }
        
        tbody tr:nth-child(even) { 
            background-color: #f8f9fa; 
        }
        
        tbody td { 
            padding: 8px 6px;
            text-align: center;
            border: 1px solid #ecf0f1;
            font-size: 9px;
        }
        
        .estado-pendiente {
            color: #e67e22;
            font-weight: bold;
        }
        
        .estado-proceso {
            color: #3498db;
            font-weight: bold;
        }
        
        .estado-completado {
            color: #27ae60;
            font-weight: bold;
        }
        
        .prioridad-alta {
            color: #e74c3c;
        }
        
        .prioridad-media {
            color: #f39c12;
        }
        
        .prioridad-baja {
            color: #27ae60;
        }
        
        .total { 
            margin-top: 20px; 
            text-align: right; 
            font-weight: bold; 
            font-size: 12px;
            padding: 10px 20px;
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
            border-radius: 8px;
            display: inline-block;
            float: right;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #95a5a6;
            border-top: 1px solid #ecf0f1;
            padding-top: 10px;
        }
        
        .watermark {
            position: fixed;
            bottom: 20px;
            right: 20px;
            opacity: 0.1;
            font-size: 60px;
            font-weight: bold;
            color: #2c3e50;
            transform: rotate(-20deg);
            pointer-events: none;
        }
        
        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none; }
            .watermark { opacity: 0.05; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px; text-align: center; padding: 15px; background: #f8f9fa; border-radius: 8px;">
        <button onclick="window.print()" style="padding: 12px 25px; cursor: pointer; background: #27ae60; color: white; border: none; border-radius: 5px; font-size: 14px; margin-right: 10px;">
            <i class="bi bi-printer"></i> Imprimir / Guardar PDF
        </button>
        <button onclick="window.close()" style="padding: 12px 25px; cursor: pointer; background: #95a5a6; color: white; border: none; border-radius: 5px; font-size: 14px;">
            Cerrar
        </button>
    </div>
    
    <div class="company-info">
        <div>
            <h2> Sistema de Caracterización AuryS</h2>
            <p>Reporte de Seguimientos Realizados</p>
        </div>
        <div style="text-align: right;">
            <p><strong>Fecha:</strong> <?= date('d/m/Y') ?></p>
            <p><strong>Hora:</strong> <?= date('H:i:s') ?></p>
        </div>
    </div>

    <div class="header">
        <h1><?= $title ?></h1>
        <p class="subtitle">Seguimientos y acompañamiento</p>
        <span class="fecha">Generado: <?= $fecha ?></span>
    </div>

    <?php if (!empty($filtros)): ?>
    <div class="filtros-aplicados">
        <h4><i class="bi bi-funnel"></i> Filtros Aplicados</h4>
        <div class="filtro-item">
            <span class="filtro-label">Departamento:</span>
            <span class="filtro-valor"><?= $filtros['departamento'] ?? 'Todos' ?></span>
        </div>
        <div class="filtro-item">
            <span class="filtro-label">Estado:</span>
            <span class="filtro-valor"><?= $filtros['estado'] ?? 'Todos' ?></span>
        </div>
        <div class="filtro-item">
            <span class="filtro-label">Total Registros:</span>
            <span class="filtro-valor"><?= $filtros['total'] ?? count($seguimientos) ?></span>
        </div>
    </div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>N°</th>
                <th>Fecha</th>
                <th>Persona</th>
                <th>Tipo</th>
                <th>Estado</th>
                <th>Prioridad</th>
                <th>Próxima Fecha</th>
                <th>Descripción</th>
            </tr>
        </thead>
        <tbody>
            <?php $num = 1; foreach ($seguimientos as $s): 
                $persona = model('App\Models\PersonaModel')->find($s['persona_id']);
                $nombre = $persona ? ($persona['primer_nombre'] . ' ' . $persona['primer_apellido']) : 'N/A';
                
                $claseEstado = '';
                $estado = strtoupper($s['estado'] ?? '');
                if ($estado === 'PENDIENTE') $claseEstado = 'estado-pendiente';
                elseif ($estado === 'EN_PROCESO') $claseEstado = 'estado-proceso';
                elseif ($estado === 'COMPLETADO') $claseEstado = 'estado-completado';
                
                $clasePrioridad = '';
                $prioridad = strtoupper($s['prioridad'] ?? '');
                if ($prioridad === 'ALTA') $clasePrioridad = 'prioridad-alta';
                elseif ($prioridad === 'MEDIA') $clasePrioridad = 'prioridad-media';
                elseif ($prioridad === 'BAJA') $clasePrioridad = 'prioridad-baja';
            ?>
            <tr>
                <td><?= $num++ ?></td>
                <td><?= date('d/m/Y', strtotime($s['fecha_seguimiento'])) ?></td>
                <td><?= $nombre ?></td>
                <td><?= $s['tipo_seguimiento'] ?? '' ?></td>
                <td class="<?= $claseEstado ?>"><?= $s['estado'] ?? '' ?></td>
                <td class="<?= $clasePrioridad ?>"><?= $s['prioridad'] ?? '' ?></td>
                <td><?= !empty($s['proxima_fecha']) ? date('d/m/Y', strtotime($s['proxima_fecha'])) : '-' ?></td>
                <td><?= substr($s['descripcion'] ?? '', 0, 50) ?>...</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="total">Total: <?= count($seguimientos) ?> Seguimientos</div>
    
    <div style="clear: both;"></div>
    
    <div class="footer">
        <p>Sistema de Caracterización AuryS - <?= date('Y') ?> | Página 1 de 1</p>
    </div>
    
    <div class="watermark">AuryS</div>
</body>
</html>
