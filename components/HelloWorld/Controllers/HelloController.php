<?php

/**
 * Safi Microframework Skeleton
 * @author Jean Bruenn
 * @copyright 2026 All Rights Reserved
 */

declare(strict_types=1);

namespace Components\HelloWorld\Controllers;

use Safi\Core\AbstractController;
use Safi\Core\Attributes\Route;
use Safi\Core\Http\Response;
use Safi\Extensions\Auth\Models\LockedIp;
use Safi\Extensions\Auth\Models\LoginAttempt;
use Safi\Extensions\Queue\Models\Job;

final class HelloController extends AbstractController
{
    #[Route('/hello', method: 'GET', name: 'hello.index')]
    #[Route('/', method: 'GET', name: 'home')]
    public function index(): Response
    {
        $rawStartTime = $_SERVER['REQUEST_TIME_FLOAT'] ?? microtime(true);
        $startTime = is_numeric($rawStartTime) ? (float) $rawStartTime : microtime(true);
        $executionTimeMs = round((microtime(true) - $startTime) * 1000.0, 2);

        $baseDir = dirname(__DIR__, 3);

        $currentMemoryMb = round((float) memory_get_usage(true) / 1024.0 / 1024.0, 2);
        $peakMemoryMb = round((float) memory_get_peak_usage(true) / 1024.0 / 1024.0, 2);

        $opcacheActive = function_exists('opcache_get_status') && is_array(opcache_get_status(false));
        $opcacheStats = [
            'active' => $opcacheActive,
            'hit_rate' => 0.0,
            'used_mem_mb' => 0.0,
            'free_mem_mb' => 0.0,
        ];

        if ($opcacheActive) {
            /** @var array{opcache_enabled?: bool, memory_usage?: array{used_memory: int, free_memory: int}, opcache_statistics?: array{opcache_hit_rate: float}} $status */
            $status = opcache_get_status(false);
            if (isset($status['memory_usage'], $status['opcache_statistics'])) {
                $opcacheStats['hit_rate'] = round($status['opcache_statistics']['opcache_hit_rate'], 1);
                $opcacheStats['used_mem_mb'] = round((float) $status['memory_usage']['used_memory'] / 1024.0 / 1024.0, 2);
                $opcacheStats['free_mem_mb'] = round((float) $status['memory_usage']['free_memory'] / 1024.0 / 1024.0, 2);
            }
        }

        $apcuActive = extension_loaded('apcu') && apcu_enabled();
        $apcuMemoryMb = 0.0;
        if ($apcuActive && function_exists('apcu_sma_info')) {
            /** @var array{avail_mem?: float|int, num_seg?: float|int, seg_size?: float|int}|false $sma */
            $sma = apcu_sma_info(true);
            if (is_array($sma) && isset($sma['avail_mem'], $sma['num_seg'], $sma['seg_size'])) {
                $totalMem = (float) $sma['num_seg'] * (float) $sma['seg_size'];
                $usedMem = $totalMem - (float) $sma['avail_mem'];
                $apcuMemoryMb = round($usedMem / 1024.0 / 1024.0, 2);
            }
        }

        $dbFile = $baseDir . '/data/db/safi.db';
        $fileSize = file_exists($dbFile) ? filesize($dbFile) : 0;
        $dbSizeKb = ($fileSize !== false && $fileSize > 0) ? round((float) $fileSize / 1024.0, 1) : 0.0;

        $configFiles = [
            'config/config.php' => file_exists($baseDir . '/config/config.php'),
            'config/config.local.php' => file_exists($baseDir . '/config/config.local.php'),
            'init.inc.php' => file_exists($baseDir . '/init.inc.php'),
        ];

        $logFile = $baseDir . '/data/logs/app.log';
        $recentLogs = [];
        if (file_exists($logFile)) {
            $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            if (is_array($lines)) {
                $recentLogs = array_slice(array_reverse($lines), 0, 8);
            }
        }

        $lockedIpsCount = 0;
        $failedAttemptsCount = 0;
        try {
            $lockedIpsCount = $this->db->countModels(LockedIp::class);
            $failedAttemptsCount = $this->db->countModels(LoginAttempt::class);
        } catch (\Throwable) {
            // Guard
        }

        $pendingJobs = 0;
        $failedJobs = 0;
        try {
            $pendingJobs = $this->db->countModels(Job::class, 'status = ?', ['pending']);
            $failedJobs = $this->db->countModels(Job::class, 'status = ?', ['failed']);
        } catch (\Throwable) {
            // Guard
        }

        $telemetry = [
            'execution_time_ms' => $executionTimeMs,
            'php_version' => PHP_VERSION,
            'sapi' => PHP_SAPI,
            'memory_current_mb' => $currentMemoryMb,
            'memory_peak_mb' => $peakMemoryMb,
            'opcache' => $opcacheStats,
            'apcu_active' => $apcuActive,
            'apcu_memory_mb' => $apcuMemoryMb,
            'db_size_kb' => $dbSizeKb,
            'config_files' => $configFiles,
            'recent_logs' => $recentLogs,
            'banned_ips' => $lockedIpsCount,
            'failed_attempts' => $failedAttemptsCount,
            'pending_jobs' => $pendingJobs,
            'failed_jobs' => $failedJobs,
            'slow_queries' => [],
        ];

        return $this->render('@HelloWorld/index.twig', [
            'title' => 'Developer Operations Cockpit',
            'telemetry' => $telemetry,
        ]);
    }
}