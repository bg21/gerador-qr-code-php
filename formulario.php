<?php
require_once __DIR__ . '/vendor/autoload.php';

use Juhco\Qrcodephp\QRCodeGenerator;

// Cria diretório para armazenar os QR codes gerados
$outputDir = __DIR__ . '/qrcodes';
if (!file_exists($outputDir)) {
    mkdir($outputDir, 0755, true);
}

$qrCodeDataUri = '';
$message = '';
$downloadLink = '';

// Processa o formulário quando for enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST['data'] ?? '';
    $format = $_POST['format'] ?? 'png';
    $label = $_POST['label'] ?? '';
    $hasLogo = isset($_POST['use_logo']) && $_POST['use_logo'] === 'on';
    
    // Valida os dados
    if (empty($data)) {
        $message = '<div class="alert alert-danger">Por favor, informe o texto para o QR Code.</div>';
    } else {
        $generator = new QRCodeGenerator();
        
        // Verifica se deve usar logo
        $logoPath = null;
        if ($hasLogo && isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $tempName = $_FILES['logo']['tmp_name'];
            $logoName = uniqid() . '-' . $_FILES['logo']['name'];
            $logoPath = $outputDir . '/' . $logoName;
            move_uploaded_file($tempName, $logoPath);
        }
        
        // Gera um nome único para o arquivo
        $fileName = uniqid() . '.' . $format;
        $qrFilePath = $outputDir . '/' . $fileName;
        
        // Gera o QR Code
        if (isset($_POST['save']) && $_POST['save'] === 'on') {
            $generator->generate($data, $qrFilePath, $format, $label, $logoPath);
            $message = '<div class="alert alert-success">QR Code gerado com sucesso!</div>';
            $downloadLink = 'qrcodes/' . $fileName;
            // Gera também a versão para exibir
            $qrCodeDataUri = $generator->generate($data, null, $format, $label, $logoPath);
        } else {
            // Apenas gera para exibir
            $qrCodeDataUri = $generator->generate($data, null, $format, $label, $logoPath);
            $message = '<div class="alert alert-success">QR Code gerado com sucesso!</div>';
        }
        
        // Remove o logo temporário se foi criado
        if ($hasLogo && $logoPath && file_exists($logoPath)) {
            unlink($logoPath);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerador de QR Code</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .qr-preview {
            max-width: 300px;
            margin: 20px auto;
            text-align: center;
        }
        .qr-preview img {
            max-width: 100%;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h1 class="h4 mb-0">Gerador de QR Code</h1>
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <form method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="data" class="form-label">Texto ou URL</label>
                                <textarea class="form-control" id="data" name="data" rows="3" required><?php echo $_POST['data'] ?? ''; ?></textarea>
                                <div class="form-text">Digite o texto ou URL que deseja codificar no QR Code.</div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="format" class="form-label">Formato</label>
                                    <select class="form-select" id="format" name="format">
                                        <option value="png" <?php echo (isset($_POST['format']) && $_POST['format'] === 'png') ? 'selected' : ''; ?>>PNG</option>
                                        <option value="svg" <?php echo (isset($_POST['format']) && $_POST['format'] === 'svg') ? 'selected' : ''; ?>>SVG</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="label" class="form-label">Rótulo (opcional)</label>
                                    <input type="text" class="form-control" id="label" name="label" value="<?php echo $_POST['label'] ?? ''; ?>">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="use_logo" name="use_logo" <?php echo (isset($_POST['use_logo']) && $_POST['use_logo'] === 'on') ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="use_logo">
                                        Adicionar logo
                                    </label>
                                </div>
                                
                                <div id="logo_container" class="mt-2 <?php echo (isset($_POST['use_logo']) && $_POST['use_logo'] === 'on') ? '' : 'd-none'; ?>">
                                    <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="save" name="save" <?php echo (isset($_POST['save']) && $_POST['save'] === 'on') ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="save">
                                        Salvar QR Code para download
                                    </label>
                                </div>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Gerar QR Code</button>
                            </div>
                        </form>
                        
                        <?php if ($qrCodeDataUri): ?>
                        <div class="qr-preview mt-4">
                            <h3 class="h5 mb-3">Seu QR Code:</h3>
                            <img src="<?php echo $qrCodeDataUri; ?>" alt="QR Code gerado">
                            
                            <?php if ($downloadLink): ?>
                            <div class="mt-3">
                                <a href="<?php echo $downloadLink; ?>" download class="btn btn-success">
                                    Baixar QR Code
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const useLogoCheckbox = document.getElementById('use_logo');
            const logoContainer = document.getElementById('logo_container');
            
            useLogoCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    logoContainer.classList.remove('d-none');
                } else {
                    logoContainer.classList.add('d-none');
                }
            });
        });
    </script>
</body>
</html> 