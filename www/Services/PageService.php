<?php

namespace App\Service;

use App\Core\Database;
use App\Model\Page;

class PageService
{
    private \PDO $pdo;

    public function __construct(){
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function createPage(array $data){
        $page = new Page();
        $page->setTitle($data['title']);
        $page->setSlug($data['slug']);
        $page->setContent($data['content']);
        $page->setStatus($data['status'] ?? 'draft');
        $page->setAuthorId($data['author_id'] ?? null);

        $sql = 'INSERT INTO page(title, slug, content, status, author_id, created_at, updated_at)
                VALUES (:title, :slug, :content, :status, :author_id, now(), now())';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'title' => $page->getTitle(),
            'slug' => $page->getSlug(),
            'content' => $page->getContent(),
            'status' => $page->getStatus(),
            'author_id' => $page->getAuthorId()
        ]);
        return $this->pdo->lastInsertId();
    }

    public function updatePage($id, array $data){
        $sql = 'UPDATE page SET title=:title, slug=:slug, content=:content, status=:status, author_id=:author_id, updated_at = now() WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'title' => $data['title'],
            'slug' => $data['slug'],
            'content' => $data['content'],
            'status' => $data['status'] ?? 'draft',
            'author_id' => $data['author_id'] ?? null,
            'id' => (int)$id
        ]);
    }

    public function getPageById($id){
        $sql = 'SELECT * FROM page WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => (int)$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getPageBySlug($slug){
        $sql = 'SELECT * FROM page WHERE slug = :slug';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function listPages($limit = 100, $offset = 0){
        $sql = 'SELECT * FROM page ORDER BY created_at DESC LIMIT :limit OFFSET :offset';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue('limit', (int)$limit, \PDO::PARAM_INT);
        $stmt->bindValue('offset', (int)$offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function deletePage($id){
        $sql = 'DELETE FROM page WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => (int)$id]);
    }

    public function generateSlug($title){
        $slug = iconv('utf-8', 'us-ascii//TRANSLIT', $title);
        $slug = preg_replace('/[^a-zA-Z0-9\s-]/', '', $slug);
        $slug = strtolower(trim($slug));
        $slug = preg_replace('/[\s-]+/', '-', $slug);
        
        $base = $slug;
        $i = 1;
        while($this->getPageBySlug($slug)){
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }
}
