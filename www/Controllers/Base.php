<?php

namespace App\Controller;

use App\Core\Render;

class Base
{
    public function index() {
         $this->renderPage("home", "frontoffice");
    }
    protected function renderPage(string $view, string $template, array $data = []):void{
        $render = new Render($view, $template);  
        if(!empty($data)){
            foreach ($data as $key => $value){
            $render->assign($key, $value);
            }
        }
        $render->render();
    }
}