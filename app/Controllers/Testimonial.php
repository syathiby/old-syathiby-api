<?php

namespace App\Controllers;

use App\Models\TestimonialModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class Testimonial extends ResourceController
{
    use ResponseTrait;
    public function addTesti()
    {
        $token = $this->request->getServer('HTTP_AUTHORIZATION');

        if($token)
        {
            $token = str_replace('Bearer ', '', $token);
    
            $cache = \Config\Services::cache();
            $userData = $cache->get('user_' . $token);

            if($userData)
            {
                $model = new TestimonialModel();

                $data = [
                    'name' => $this->request->getPost('name'),
                    'angkatan' => $this->request->getPost('angkatan'),
                    'pesan' => $this->request->getPost('pesan'),
                    'image' => $this->request->getPost('image'),
                    'created_by' => $userData['name']
                ];

                $model->createTesti($data);
                
                if ($model->affectedRows() > 0) {
                    return $this->respondDeleted(['message' => 'Success'], 200);
                } else {
                    return $this->fail('Error! Failed to delete post.', 500);
                }

            }
    
        }

        return $this->respond('Unauthorized', 401);
    }

    public function delTestimonial($id = null)
    {
        $token = $this->request->getServer('HTTP_AUTHORIZATION');

        if ($token){
            $token = str_replace('Bearer ', '', $token);

            $cache = \Config\Services::cache();
            $userData = $cache->get('user_' . $token);

            if($userData){
                $model = new TestimonialModel();

                if ($id === null) {
                    return $this->fail('Post ID not provided.', 400);
                }

                $model->deleteTesti($id);

                if ($model->affectedRows() > 0) {
                    return $this->respondDeleted(['message' => 'Success'], 200);
                } else {
                    return $this->fail('Error! Failed to delete post.', 500);
                }
            }
        }

        return $this->respond('Unauthorized', 401);
    }
    public function getTestimonial()
    {
        $token = $this->request->getServer('HTTP_AUTHORIZATION');

        if($token)
        {
            $token = str_replace('Bearer ', '', $token);
            
            $cache = \Config\Services::cache();
            $userData = $cache->get('user_' . $token);

            if($userData)
            {
                $model = new TestimonialModel();

                $data = $model->getTesti();

                return $this->respond($data);
            }
        }

        return $this->respond('Unauthorized', 401);
    }
    public function getAllTesti()
    {
        
        $model = new TestimonialModel();

        $data = $model->getTesti();

        return $this->respond($data);
    }
}
