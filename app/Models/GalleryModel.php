<?php

namespace App\Models;

use CodeIgniter\Model;

class GalleryModel extends Model
{
    protected $table = 'gallery';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'type', 'filename', 'caption', 'kategori', 'created_by'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function addGaleri($data)
    {
        return $this->insert($data);
    }

    public function getGaleri()
    {
        return $this->findAll();
    }

    public function updateLabel($id, $data)
    {
        return $this->update($id, $data);
    }

    public function deleteGal($id)
    {
        return $this->delete($id);
    }
}
