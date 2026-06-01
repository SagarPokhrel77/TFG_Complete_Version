<?php

/**
 * Validación y guardado de subidas de imagen.
 */

const UPLOAD_IMAGE_ALLOWED_EXT = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
const UPLOAD_IMAGE_ALLOWED_MIME = [
    'image/jpeg',
    'image/png',
    'image/gif',
    'image/webp',
];

/**
 * @return array{ok: bool, error: string}
 */
function validateImageUpload(array $file): array
{
    if (!isset($file['error'])) {
        return ['ok' => false, 'error' => 'No se recibió ningún archivo.'];
    }

    if ($file['error'] === UPLOAD_ERR_NO_FILE) {
        return ['ok' => true, 'error' => ''];
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['ok' => false, 'error' => 'Error al subir el archivo. Inténtalo de nuevo.'];
    }

    $ext = strtolower(pathinfo($file['name'] ?? '', PATHINFO_EXTENSION));
    if (!in_array($ext, UPLOAD_IMAGE_ALLOWED_EXT, true)) {
        return [
            'ok' => false,
            'error' => 'Tipo de archivo incorrecto. Solo se permiten imágenes (JPG, PNG, GIF, WEBP).',
        ];
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = $finfo ? finfo_file($finfo, $file['tmp_name']) : '';
    if ($finfo) {
        finfo_close($finfo);
    }

    if ($mime === '' || !in_array($mime, UPLOAD_IMAGE_ALLOWED_MIME, true)) {
        return [
            'ok' => false,
            'error' => 'Tipo de archivo incorrecto. Solo se permiten imágenes (JPG, PNG, GIF, WEBP).',
        ];
    }

    return ['ok' => true, 'error' => ''];
}

/**
 * Guarda una imagen validada en $basePath.
 *
 * @return array{ok: bool, path: ?string, error: string}
 */
function saveValidatedImageUpload(array $file, string $basePath, string $fieldPrefix = 'img'): array
{
    if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return ['ok' => true, 'path' => null, 'error' => ''];
    }

    $check = validateImageUpload($file);
    if (!$check['ok']) {
        return ['ok' => false, 'path' => null, 'error' => $check['error']];
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $safeExt = $ext !== '' ? $ext : 'jpg';
    $fileName = $fieldPrefix . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $safeExt;
    $path = rtrim($basePath, '/\\') . '/' . $fileName;

    if (!is_dir($basePath)) {
        mkdir($basePath, 0755, true);
    }

    if (!move_uploaded_file($file['tmp_name'], $path)) {
        return ['ok' => false, 'path' => null, 'error' => 'No se pudo guardar la imagen.'];
    }

    return ['ok' => true, 'path' => $path, 'error' => ''];
}
