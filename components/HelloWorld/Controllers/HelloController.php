<?php

/**
 * Safi Microframework Skeleton
 * @author Jean Bruenn
 * @copyright 2026 All Rights Reserved
 */

declare(strict_types=1);

namespace Components\HelloWorld\Controllers;

use Components\HelloWorld\Models\SystemMetric;
use Safi\Core\AbstractController;
use Safi\Core\Attributes\Route;
use Safi\Core\Http\Response;

final class HelloController extends AbstractController
{
    #[Route('/hello', method: 'GET', name: 'hello.index')]
    #[Route('/', method: 'GET', name: 'home')]
    public function index(): Response
    {
        $rawNotice = $this->request->get('notice');
        $notice = is_string($rawNotice) ? $rawNotice : 'Welcome to your clean Safi node!';

        $rawStartTime = $_SERVER['REQUEST_TIME_FLOAT'] ?? microtime(true);
        $startTime = is_numeric($rawStartTime) ? (float) $rawStartTime : microtime(true);
        $executionTimeMs = round((microtime(true) - $startTime) * 1000.0, 2);

        /** @var SystemMetric|null $clickMetric */
        $clickMetric = $this->db->findOneModel(SystemMetric::class, 'metric_key = ?', ['counter_clicks']);
        $counterValue = $clickMetric instanceof SystemMetric ? (int) $clickMetric->value : 0;

        $telemetry = [
            'apcu_active' => extension_loaded('apcu') && apcu_enabled(),
            'php_version' => PHP_VERSION,
            'sapi' => PHP_SAPI,
            'execution_time_ms' => $executionTimeMs,
        ];

        return $this->render('@HelloWorld/index.twig', [
            'title' => 'System Dashboard Matrix',
            'notice' => $notice,
            'counter' => $counterValue,
            'telemetry' => $telemetry,
        ]);
    }

    #[Route('/hello/notice', method: 'POST', name: 'hello.notice')]
    public function updateNotice(): Response
    {
        $this->validateCsrf();
        $newNotice = $this->request->post('notice');
        $cleanNotice = is_string($newNotice) ? trim($newNotice) : '';

        if ($this->request->isXhr()) {
            return $this->html("<blockquote>\"" . htmlspecialchars($cleanNotice) . "\"</blockquote>");
        }

        return $this->redirect('/hello', ['notice' => $cleanNotice]);
    }

    #[Route('/hello/counter', method: 'POST', name: 'hello.counter')]
    public function incrementCounter(): Response
    {
        $this->validateCsrf();

        /** @var SystemMetric|null $metric */
        $metric = $this->db->findOneModel(SystemMetric::class, 'metric_key = ?', ['counter_clicks']);

        if (!$metric instanceof SystemMetric) {
            $metric = $this->db->dispenseModel(SystemMetric::class);
            $metric->key = 'counter_clicks';
            $metric->value = '1';
        } else {
            $current = (int) $metric->value;
            $metric->value = (string) ($current + 1);
        }

        $this->db->storeModel($metric);

        return $this->html(
            '<button hx-post="/hello/counter" hx-vals=\'{"csrf_token": "' . htmlspecialchars($this->security->getCsrfToken()) . '"}\' hx-target="#counter-widget" hx-swap="outerHTML" id="counter-widget" class="contrast">
                <i class="fa-solid fa-plus"></i> Clicks: ' . htmlspecialchars($metric->value) . '
             </button>'
        );
    }
}
