<?php
/**
 * Health check endpoint for Render / uptime monitors.
 * Returns 200 OK if PHP is alive. Does NOT check DB
 * (so that the health check passes during DB connection
 * failures — Render would otherwise kill the container).
 */
header('Content-Type: application/json; charset=utf-8');
http_response_code(200);
echo json_encode([
    'status'    => 'ok',
    'service'   => 'hospital-call-system',
    'version'   => '3.1',
    'timestamp' => date('c'),
    'php'       => PHP_VERSION,
]);
