<?php

declare(strict_types=1);

namespace App;

use Safi\Core\AbstractController;
use Safi\Core\Attributes\Route;
use Safi\Core\Http\Response;

final class HealthController extends AbstractController
{
    #[Route('/healthz', method: 'GET', name: 'health.check', public: true)]
    public function check(): Response
    {
        $dbStatus = 'OK';
        try {
            $this->db->exec('SELECT 1');
        } catch (\Throwable $e) {
            $dbStatus = 'ERROR: ' . $e->getMessage();
        }

        $status = $dbStatus === 'OK' ? 200 : 500;

        return $this->jsonResponse([
            'status' => $status === 200 ? 'UP' : 'DOWN',
            'timestamp' => date('c'),
            'checks' => [
                'database' => $dbStatus,
                'memory_peak_mb' => round((float) memory_get_peak_usage(true) / 1024.0 / 1024.0, 2),
            ],
        ], $status);
    }
}
