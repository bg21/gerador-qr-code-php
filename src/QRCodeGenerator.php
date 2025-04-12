<?php

namespace Juhco\Qrcodephp;

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;

class QRCodeGenerator
{
    /**
     * Gera um QR Code com base no texto fornecido
     *
     * @param string $data O texto/URL a ser codificado no QR Code
     * @param string $outputPath Caminho onde o QR Code será salvo (opcional)
     * @param string $format Formato de saída (png ou svg)
     * @param string $label Texto do rótulo (opcional)
     * @param string|null $logoPath Caminho para o logo (opcional)
     * @return string Caminho do arquivo gerado ou conteúdo do QR Code se $outputPath for null
     */
    public function generate(
        string $data,
        ?string $outputPath = null,
        string $format = 'png',
        ?string $label = null,
        ?string $logoPath = null
    ) {
        // Seleciona o writer correto
        $writer = $format === 'svg' ? new SvgWriter() : new PngWriter();
        
        // Cria o QR Code
        $qrCode = new QrCode(
            data: $data,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 300,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            foregroundColor: new Color(0, 0, 0),
            backgroundColor: new Color(255, 255, 255)
        );
        
        // Configura o logo, se fornecido
        $logo = null;
        if ($logoPath !== null && file_exists($logoPath)) {
            $logo = new Logo(
                path: $logoPath,
                resizeToWidth: 50,
                punchoutBackground: true
            );
        }
        
        // Configura o rótulo, se fornecido
        $labelObj = null;
        if ($label !== null) {
            $labelObj = new Label(
                text: $label,
                textColor: new Color(0, 0, 0)
            );
        }
        
        // Gera o QR code
        $result = $writer->write($qrCode, $logo, $labelObj);
        
        // Salva o QR code se um caminho for fornecido
        if ($outputPath !== null) {
            $result->saveToFile($outputPath);
            return $outputPath;
        }
        
        // Retorna o conteúdo do QR code
        return $format === 'png' 
            ? $result->getDataUri() 
            : $result->getString();
    }
} 