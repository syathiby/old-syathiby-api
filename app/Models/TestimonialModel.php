<?php

namespace App\Models;

use CodeIgniter\Model;

class TestimonialModel extends Model
{
    protected $table = 'testimonial';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'angkatan', 'pesan', 'image', 'created_by'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function createTesti($data)
    {
        return $this->insert($data);
    }

    public function getTestiId($id)
    {
        return $this->where('id', $id)->first();
    }

    public function getTesti()
    {
        return $this->findAll();
    }

    public function updateLabel($id, $data)
    {
        return $this->update($id, $data);
    }

    public function deleteTesti($id)
    {
        return $this->delete($id);
    }
}
