<?php

namespace App\Controller;

use App\Controller\Base;
use App\Service\PageService;

class Page extends Base
{
    public function renderBySlug(): void{
        $slug = $_GET['slug'] ?? null;
        if(empty($slug)){
            die('Page 404');
        }
        $ps = new PageService();
        $page = $ps->getPageBySlug($slug);
        if(empty($page) || $page['status'] !== 'published'){
            die('Page 404');
        }
        $this->renderPage('page', 'frontoffice', ['page' => $page]);
    }
}
