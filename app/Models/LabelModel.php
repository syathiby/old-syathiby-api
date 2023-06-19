<?php

namespace App\Models;

use CodeIgniter\Model;

class LabelModel extends Model
{
    protected $table = 'labels';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'color', 'created_by', 'updated_by'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function createLabel($data)
    {
        return $this->insert($data);
    }

    public function getLabel()
    {
        return $this->findAll();
    }

    public function getLabelBy($link)
    {
        return $this->where('link', $link)->first();
    }

    public function updateLabel($id, $data)
    {
        return $this->update($id, $data);
    }

    public function deleteLabel($id)
    {
        return $this->delete($id);
    }
}
