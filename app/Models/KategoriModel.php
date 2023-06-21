<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriModel extends Model
{
    protected $table = 'kategori';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nkategori', 'created_by', 'updated_by'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function createKategori($data)
    {
        return $this->insert($data);
    }

    public function getKat()
    {
        return $this->findAll();
    }

    public function updateLabel($id, $data)
    {
        return $this->update($id, $data);
    }

    public function deleteKat($id)
    {
        return $this->delete($id);
    }

}
