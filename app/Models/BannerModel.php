<?php

namespace App\Models;

use CodeIgniter\Model;

class BannerModel extends Model
{
    protected $table = 'banner';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'caption', 'image', 'link', 'created_by'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function createBanner($data)
    {
        return $this->insert($data);
    }

    public function getBanners()
    {
        return $this->findAll();
    }

    public function updateLabel($id, $data)
    {
        return $this->update($id, $data);
    }

    public function deleteBanner($id)
    {
        return $this->delete($id);
    }
}
