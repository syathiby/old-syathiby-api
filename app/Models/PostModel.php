<?php

namespace App\Models;

use CodeIgniter\Model;

class PostModel extends Model
{
    protected $table = 'posts';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'img', 'description', 'label', 'meta', 'link', 'created_by'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function createPost($data)
    {
        return $this->insert($data);
    }

    public function getPost()
    {
        $query = $this->table($this->table)
        ->select($this->table . '.id AS ' . $this->table . '_id, ' . $this->table . '.title, ' . $this->table . '.img, ' . $this->table . '.description, ' . $this->table . '.label, ' . $this->table . '.meta, ' . $this->table . '.link, ' . $this->table . '.created_by,'.$this->table.'.created_at, labels.name, labels.color')
        ->join('labels', 'labels.id = ' . $this->table . '.label', 'left')
        ->orderBy($this->table . '.created_at', 'DESC')
        ->findAll();

        return $query;
    }

    public function getPostByLink($link)
    {
        $query = $this->table($this->table)
        ->select($this->table . '.id AS ' . $this->table . '_id, ' . $this->table . '.title, ' . $this->table . '.img, ' . $this->table . '.description, ' . $this->table . '.label, ' . $this->table . '.meta, ' . $this->table . '.link, ' . $this->table . '.created_by,'.$this->table.'.created_at, labels.name, labels.color')
        ->join('labels', 'labels.id = ' . $this->table . '.label', 'left')
        ->where('link', $link)
        ->first();

        return $query;
    }

    public function updatePosts($id, $data)
    {
        return $this->update($id, $data);
    }

    public function deletePosts($id)
    {
        return $this->delete($id);
    }
}
