<?php

namespace App\Controllers;

use App\Models\BannerModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class Banner extends ResourceController
{
    use ResponseTrait;
    public function getBanner(){

        $token = $this->request->getServer('HTTP_AUTHORIZATION');
        if ($token){
            $token = str_replace('Bearer ', '', $token);

            $cache = \Config\Services::cache();
            $userData = $cache->get('user_' . $token);

            if($userData){
                $model = new BannerModel();

                $data = $model->getBanners();

                return $this->respond($data);
            }
        }

        return $this->respond(['code' => '401', 'message' => ''], 401);
    }

    public function postBanner()
    {
        $token = $this->request->getServer('HTTP_AUTHORIZATION');

        if ($token){
            $token = str_replace('Bearer ', '', $token);

            $cache = \Config\Services::cache();
            $userData = $cache->get('user_' . $token);

            if($userData){
                $model = new BannerModel();

                $title = $this->request->getPost('title');
                $link = str_replace(' ', '-', $title);

                $data = [
                    'title' => $title,
                    'caption' => $this->request->getPost('caption'),
                    'image' => $this->request->getPost('image'),
                    'link' => $link,
                    'created_by' => $userData['name']
                ];

                $model->createBanner($data);

                if ($model->affectedRows() > 0) {
                    return $this->respond(['code' => '201','message' => 'Success'], 201);
                } else {
                    return $this->fail('Error! Failed to update post.', 500);
                }    
            }
        }
    }

    public function deletBanners($id = null)
    {
        $token = $this->request->getServer('HTTP_AUTHORIZATION');

        if ($token){
            $token = str_replace('Bearer ', '', $token);

            $cache = \Config\Services::cache();
            $userData = $cache->get('user_' . $token);

            if($userData){
                $model = new BannerModel();

                if ($id === null) {
                    return $this->fail('Post ID not provided.', 400);
                }

                $model->deleteBanner($id);

                if ($model->affectedRows() > 0) {
                    return $this->respondDeleted(['message' => 'Success'], 200);
                } else {
                    return $this->fail('Error! Failed to delete post.', 500);
                }
            }
        }
    }
}
