<?php

declare(strict_types=1);

namespace Src\App\Http\Controllers;

use Src\App\Enums\Perfil;
use Src\App\Utils\Url;
use Src\App\Utils\View;

class SharedController
{
    public static function getPage($title, $content, $options = [], $toast = '')
    {
        $showSidebar = $options['showSidebar'] ?? true;
        $activePage = $options['activePage'] ?? '';
        $bodyClass = $options['bodyClass'] ?? '';

        $toastData = $toast ?: ($_SESSION['toast'] ?? '');
        unset($_SESSION['toast']);

        return View::render(
            'Shared/index',
            [
                'title' => $title,
                'header' => self::getHeader(),
                'footer' => self::getFooter(),
                'toast' => self::getToast($toastData),
                'sidebar' => $showSidebar ? self::getSidebar($activePage) : '',
                'content' => $content,
                'usuario' => $_SESSION['usuario'] ?? '',
                'bodyClass' => $bodyClass,
                'basePath' => Url::base(),
            ],
        );
    }

    private static function getToast(mixed $toast): string
    {
        if (empty($toast) || ! is_array($toast)) {
            return '';
        }
        return View::render('Shared/toast', ['toast' => $toast]);
    }

    private static function getHeader(): string
    {
        return View::render('Shared/header');
    }

    private static function getFooter(): string
    {
        return View::render('Shared/footer');
    }

    private static function getSidebar($activePage = '')
    {
        $isAdmin = $_SESSION['usuario_role'] === Perfil::ADMIN->value;
        return View::render('Shared/sidebar', [
            'isAdmin'          => $isAdmin,
            'activeDashboard'  => $activePage === 'dashboard',
            'activeAdmin'      => $activePage === 'admin',
            'activeExemplo'    => $activePage === 'exemplo',
            'activeGaleria'    => $activePage === 'galeria',
            'activeConfig'     => $activePage === 'configuracoes',
            'urlDashboard'     => Url::path('/home'),
            'urlExemplo'       => Url::path('/exemplo'),
            'urlGraficos'       => Url::path('/graficos'),
            'urlGaleria'       => Url::path('/galeria'),
            'urlAdmin'         => Url::path('/admin'),
            'urlConfig'        => Url::path('/configuracoes'),
            'urlLogout'        => Url::path('/logout'),
        ]);
    }
}
