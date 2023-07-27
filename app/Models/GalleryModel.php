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
        $this->select('gallery.id, gallery.title, gallery.type, gallery.type, gallery.filename, gallery.caption, kategori.nkategori, gallery.created_by, gallery.updated_at');
        $this->join('kategori', 'gallery.kategori = kategori.id');
        $this->orderBy($this->table.'.created_at', 'DESC');

        return $this->findAll();
    }

    public function getGaleriId($id)
    {
        return $this->where('id', $id)->first();
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
