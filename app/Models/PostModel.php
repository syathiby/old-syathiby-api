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
        return $this->findAll();
    }

    public function getPostByLink($link)
    {
        return $this->where('link', $link)->first();
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
