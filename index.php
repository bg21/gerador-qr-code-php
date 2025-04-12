<?php

require_once __DIR__ . '/vendor/autoload.php';

use Juhco\Qrcodephp\QRCodeGenerator;

// Cria diretório para armazenar os QR codes gerados
$outputDir = __DIR__ . '/qrcodes';
if (!file_exists($outputDir)) {
    mkdir($outputDir, 0755, true);
}

// Instancia o gerador de QR Code
$generator = new QRCodeGenerator();

// Exemplo 1: Gerar um QR Code simples e mostrar como imagem
$data = 'https://www.example.com';
$qrCodePath = $outputDir . '/exemplo1.png';
$generator->generate($data, $qrCodePath);

// Exemplo 2: Gerar um QR Code com rótulo
$data = 'https://www.example.com';
$qrCodePath = $outputDir . '/exemplo2.png';
$generator->generate($data, $qrCodePath, 'png', 'Visite nosso site');

// Exemplo 3: Gerar um QR Code com logo
// Substitua pelo caminho real do seu logo
$logoPath = __DIR__ . '/logo.png';
$data = 'https://www.example.com';
$qrCodePath = $outputDir . '/exemplo3.png';
// Apenas tenta gerar com logo se o arquivo existir
if (file_exists($logoPath)) {
    $generator->generate($data, $qrCodePath, 'png', 'Com Logo', $logoPath);
}

// Exemplo 4: Gerar QR Code em formato SVG
$data = 'https://www.example.com';
$qrCodePath = $outputDir . '/exemplo4.svg';
$generator->generate($data, $qrCodePath, 'svg', 'Formato SVG');

// Exemplo 5: Gerar QR Code e exibir diretamente (sem salvar arquivo)
$data = 'https://www.example.com';
$qrCodeDataUri = $generator->generate($data);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exemplos de QR Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .qr-example {
            margin-bottom: 30px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        h1, h2 {
            color: #333;
        }
        img {
            max-width: 200px;
            border: 1px solid #eee;
        }
    </style>
</head>
<body>
    <h1>Exemplos de QR Code usando Endroid/QR-Code</h1>
    
    <div class="qr-example">
        <h2>Exemplo 1: QR Code simples</h2>
        <p>URL: <?php echo htmlspecialchars($data); ?></p>
        <img src="qrcodes/exemplo1.png" alt="QR Code simples">
    </div>
    
    <div class="qr-example">
        <h2>Exemplo 2: QR Code com rótulo</h2>
        <p>URL: <?php echo htmlspecialchars($data); ?></p>
        <img src="qrcodes/exemplo2.png" alt="QR Code com rótulo">
    </div>
    
    <?php if (file_exists($logoPath)): ?>
    <div class="qr-example">
        <h2>Exemplo 3: QR Code com logo</h2>
        <p>URL: <?php echo htmlspecialchars($data); ?></p>
        <img src="qrcodes/exemplo3.png" alt="QR Code com logo">
    </div>
    <?php endif; ?>
    
    <div class="qr-example">
        <h2>Exemplo 4: QR Code em formato SVG</h2>
        <p>URL: <?php echo htmlspecialchars($data); ?></p>
        <img src="qrcodes/exemplo4.svg" alt="QR Code em SVG">
    </div>
    
    <div class="qr-example">
        <h2>Exemplo 5: QR Code gerado dinamicamente</h2>
        <p>URL: <?php echo htmlspecialchars($data); ?></p>
        <img src="<?php echo $qrCodeDataUri; ?>" alt="QR Code dinâmico">
    </div>
</body>
</html> 