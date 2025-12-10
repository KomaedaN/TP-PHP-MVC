<?php

namespace App\Controller;

use App\Controller\Base;
use App\Service\PageService;

class AdminPages extends Base
{
    private $errors = [];


    public function create(): void{
        $this->isAuth();
        if(isset($_POST['title']) && isset($_POST['content'])){
            $title = trim($_POST['title']);
            $content = $_POST['content'];
            $status = $_POST['status'] ?? 'draft';
            $ps = new PageService();
            
            $slug = $ps->generateSlug($title);
            $data = [
                'title' => $title,
                'slug' => $slug,
                'content' => $content,
                'status' => $status,
                    'author_id' => $this->getCurrentUserId()
            ];
            $ps->createPage($data);
            $this->renderPage('dashboard', 'backoffice');
        }
        else{
            $this->renderPage('admin/page-form', 'backoffice');
        }
    }

    public function editForm(): void{
        $this->isAuth();
        $id = (int)$_GET['id'];
        $ps = new PageService();
        $page = $ps->getPageById($id);
        $this->renderPage('admin/page-form', 'backoffice', ['page' => $page]);
    }

    public function edit(): void{
        $this->isAuth();
        if(isset($_POST['id'], $_POST['title'], $_POST['content'])){
            $id = (int)$_POST['id'];
            $title = trim($_POST['title']);
            $content = $_POST['content'];
            $status = $_POST['status'] ?? 'draft';
            $ps = new PageService();
            $slug = $ps->generateSlug($title);
            $data = [
                'title' => $title,
                'slug' => $slug,
                'content' => $content,
                'status' => $status,
                'author_id' => $this->getCurrentUserId()
            ];
            $ps->updatePage($id, $data);
            $this->renderPage('dashboard', 'backoffice'); 
        }
        else{
            $this->renderPage('admin/page-form', 'backoffice');
        }
    }

    public function delete(): void{
        $this->isAuth();
        if(!empty($_POST['id'])){
            $id = (int)$_POST['id'];
            $ps = new PageService();
            $ps->deletePage($id);
        }
        header('Location: /admin/pages');
        exit;
    }

    public function renderWordPress(): void{
        $service = new PageService();
        $pages = $service->listPages();
        $this->renderPage('admin/pages', 'frontoffice', ['pages' => $pages]);
    }


    public function renderCreateForm(): void{
        $this->isAuth();
        $this->renderPage('admin/page-form', 'backoffice');
    }
}