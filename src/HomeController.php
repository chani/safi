<?php

/**
 * Safi Microframework
 * @author Jean Bruenn
 * @copyright 2026 All Rights Reserved
 */

declare(strict_types=1);

namespace App;

use Safi\Core\AbstractController;
use Safi\Core\Http\Response;

final class HomeController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('home.twig', [
            'title' => 'Safi Microframework',
            'message' => 'System operational & Phase 3 integrated.',
            'version' => '0.1.0',
        ]);
    }
}
