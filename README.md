# Sistema de Geração de QR Code

Este é um sistema simples para geração de QR Codes usando a biblioteca Endroid/QR-Code.

## Funcionalidades

- Geração de QR Codes em formato PNG e SVG
- Personalização com rótulos de texto
- Adição de logos ao QR Code
- Formulário web para gerar QR Codes de forma interativa
- Possibilidade de download dos QR Codes gerados

## Requisitos

- PHP 7.4 ou superior
- Composer

## Instalação

1. Clone este repositório ou baixe os arquivos
2. Execute o comando para instalar as dependências:

```bash
composer install
```

3. Certifique-se de que o diretório `qrcodes` tenha permissão de escrita para o servidor web

## Como usar

### Exemplos básicos

Acesse o arquivo `index.php` para ver exemplos básicos de uso da biblioteca.

### Gerador interativo

Acesse o arquivo `formulario.php` para usar o gerador interativo de QR Codes com interface web.

### Uso via código

```php
<?php
require_once __DIR__ . '/vendor/autoload.php';

use Juhco\Qrcodephp\QRCodeGenerator;

// Instancia o gerador de QR Code
$generator = new QRCodeGenerator();

// Gera um QR Code simples e salva como arquivo
$data = 'https://www.seusite.com.br';
$qrCodePath = __DIR__ . '/qrcodes/meu-qrcode.png';
$generator->generate($data, $qrCodePath);

// Gera um QR Code com rótulo
$generator->generate($data, __DIR__ . '/qrcodes/qrcode-com-rotulo.png', 'png', 'Visite nosso site');

// Gera um QR Code com logo
$logoPath = __DIR__ . '/logo.png';
$generator->generate($data, __DIR__ . '/qrcodes/qrcode-com-logo.png', 'png', 'Com Logo', $logoPath);

// Gera QR Code em formato SVG
$generator->generate($data, __DIR__ . '/qrcodes/qrcode.svg', 'svg');

// Gera QR Code e retorna como data URI (sem salvar arquivo)
$qrCodeDataUri = $generator->generate($data);
echo '<img src="' . $qrCodeDataUri . '" alt="QR Code">';
```

## Parâmetros da classe QRCodeGenerator

O método `generate` aceita os seguintes parâmetros:

- `$data` (string, obrigatório): O texto ou URL a ser codificado no QR Code
- `$outputPath` (string, opcional): Caminho onde o QR Code será salvo. Se não for fornecido, o método retorna o conteúdo do QR Code
- `$format` (string, opcional): Formato de saída ('png' ou 'svg'). O padrão é 'png'
- `$label` (string, opcional): Texto do rótulo a ser exibido no QR Code
- `$logoPath` (string, opcional): Caminho para o logo a ser adicionado ao QR Code

## Licença

Este projeto está licenciado sob a licença MIT.

## Créditos

Este sistema utiliza a biblioteca [Endroid/QR-Code](https://github.com/endroid/qr-code). 