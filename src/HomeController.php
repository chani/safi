<?php

/**
 * Safi Microframework
 * @author Jean Bruenn
 * @copyright 2026 All Rights Reserved
 */

declare(strict_types=1);

namespace App;

use Safi\Core\AbstractController;
use Safi\Core\Attributes\Route;
use Safi\Core\Http\Response;

final class HomeController extends AbstractController
{
    #[Route('/', method: 'GET', name: 'home.index', public: true)]
    public function index(): Response
    {
        return $this->render('home', [
            'title' => 'Safi Microframework',
            'message' => 'System operational',
            'version' => '0.1.0',
        ]);
    }
}
