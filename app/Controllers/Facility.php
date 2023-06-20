<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;
use App\Models\FacilityModel;

class Facility extends ResourceController
{
    use ResponseTrait;
    public function createFacility()
    {
        $token = $this->request->getServer('HTTP_AUTHORIZATION');

        if($token)
        {
            $token = str_replace('Bearer ', '', $token);
    
            $cache = \Config\Services::cache();
            $userData = $cache->get('user_' . $token);

            if($userData)
            {
                $model = new FacilityModel();

                $data = [
                    'name' => $this->request->getPost('name'),
                    'created_by' => $userData['name']
                ];

                $model->createFa($data);
                
                if ($model->affectedRows() > 0) {
                    return $this->respondDeleted(['message' => 'Success'], 200);
                } else {
                    return $this->fail('Error! Failed to delete post.', 500);
                }

            }
    
        }

        return $this->respond('Unauthorized', 401);
    }

    public function getFacility()
    {
        $token = $this->request->getServer('HTTP_AUTHORIZATION');

        if($token)
        {
            $token = str_replace('Bearer ', '', $token);
            
            $cache = \Config\Services::cache();
            $userData = $cache->get('user_' . $token);

            if($userData)
            {
                $model = new FacilityModel();

                $data = $model->getFa();

                return $this->respond($data);
            }
        }

        return $this->respond('Unauthorized', 401);
    }

    public function delFacility($id = null)
    {
        $token = $this->request->getServer('HTTP_AUTHORIZATION');

        if ($token){
            $token = str_replace('Bearer ', '', $token);

            $cache = \Config\Services::cache();
            $userData = $cache->get('user_' . $token);

            if($userData){
                $model = new FacilityModel();

                if ($id === null) {
                    return $this->fail('Post ID not provided.', 400);
                }

                $model->deleteFa($id);

                if ($model->affectedRows() > 0) {
                    return $this->respondDeleted(['message' => 'Success'], 200);
                } else {
                    return $this->fail('Error! Failed to delete post.', 500);
                }
            }
        }

        return $this->respond('Unauthorized', 401);
    }
}
