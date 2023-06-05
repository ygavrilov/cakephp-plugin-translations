<?php
declare(strict_types=1);

namespace Translations\Controller;

use App\Controller\AppController as BaseController;

class AppController extends BaseController
{
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Flash');
        $this->loadComponent('RequestHandler');
        $this->loadComponent('Authentication.Authentication', [
            'identityCheckEvent' => 'Controller.startup',
        ]);
        $this->loadComponent('Authorization.Authorization');
    }
}
