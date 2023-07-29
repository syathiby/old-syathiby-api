<?php

namespace App\Controllers;

use App\Models\LabelModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class Labels extends ResourceController
{
    use ResponseTrait;
    // API Label Crud Admin
    public function addLabels()
    {
        $token = $this->request->getServer('HTTP_AUTHORIZATION');

        if ($token){
            $token = str_replace('Bearer ', '', $token);

            $cache = \Config\Services::cache();
            $userData = $cache->get('user_' . $token);

            if($userData){
                $model = new LabelModel();

                $data = [
                    'name' => $this->request->getPost('name'),
                    'color' => $this->request->getPost('color'),
                    'created_by' => $userData['name']
                ];

                $model->createLabel($data);

                if ($model->affectedRows() > 0) {
                    return $this->respond(['code' => '201','message' => 'Success'], 201);
                } else {
                    return $this->fail('Error! Failed to update post.', 500);
                }    
            }
        }

        return $this->respond(['Code' => '401','Message' => 'UnAuthorized'], 401);

    }

    public function showLabels()
    {
        $token = $this->request->getServer('HTTP_AUTHORIZATION');

        if ($token){
            $token = str_replace('Bearer ', '', $token);

            $cache = \Config\Services::cache();
            $userData = $cache->get('user_' . $token);

            if($userData){
                $model = new LabelModel();

                $data = $model->getLabel();

                return $this->respond($data);
            }
        }
        return $this->respond(['Code' => '401','Message' => 'UnAuthorized'], 401);
    }

    public function showLabelB()
    {
        
        $model = new LabelModel();

        $data = $model->getLabel();

        return $this->respond($data);
            
    }
    public function showLabel($name = null)
    {
        $token = $this->request->getServer('HTTP_AUTHORIZATION');

        if ($token){
            $token = str_replace('Bearer ', '', $token);

            $cache = \Config\Services::cache();
            $userData = $cache->get('user_' . $token);

            if($userData){
                $model = new LabelModel();

                $data = $model->getLabelBy($name);

                if ($data) {
                    return $this->respond($data, 200);
                } else {
                    return $this->fail('Post not found.', 404);
                }
            }
        }
        return $this->respond(['Code' => '401','Message' => 'UnAuthorized'], 401);
    }
    public function deleteLabels($id = null)
    {
        $token = $this->request->getServer('HTTP_AUTHORIZATION');

        if ($token){
            $token = str_replace('Bearer ', '', $token);

            $cache = \Config\Services::cache();
            $userData = $cache->get('user_' . $token);

            if($userData){
                $model = new LabelModel();

                if ($id === null) {
                    return $this->fail('Post ID not provided.', 400);
                }

                $model->deleteLabel($id);

                if ($model->affectedRows() > 0) {
                    return $this->respondDeleted(['message' => 'Success'], 200);
                } else {
                    return $this->fail('Error! Failed to delete post.', 500);
                }

            }
        }
        return $this->respond(['Code' => '401','Message' => 'UnAuthorized'], 401);
    }
}
