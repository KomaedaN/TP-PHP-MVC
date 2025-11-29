<?php

namespace App\Model;

class Page
{
    protected $id;
    protected $title;
    protected $slug;
    protected $content;
    protected $status;
    protected $author_id;

    public function setTitle($title){ $this->title = $title; }
    public function setSlug($slug){ $this->slug = $slug; }
    public function setContent($content){ $this->content = $content; }
    public function setStatus($status){ $this->status = $status; }
    public function setAuthorId($author_id){ $this->author_id = $author_id; }

    public function getId(){ return $this->id; }
    public function getTitle(){ return $this->title; }
    public function getSlug(){ return $this->slug; }
    public function getContent(){ return $this->content; }
    public function getStatus(){ return $this->status; }
    public function getAuthorId(){ return $this->author_id; }
}
