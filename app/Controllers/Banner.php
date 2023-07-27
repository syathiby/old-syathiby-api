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

    public function banner(){
        $model = new BannerModel();

        $data = $model->getBanners();

        return $this->respond($data);
    }

    public function postBanner()
    {
        $token = $this->request->getServer('HTTP_AUTHORIZATION');

        if ($token) {
            $token = str_replace('Bearer ', '', $token);

            $cache = \Config\Services::cache();
            $userData = $cache->get('user_' . $token);

            if ($userData) {
                $model = new BannerModel();

                $title = $this->request->getPost('title');
                $truncateTitle = str_replace(' ', '-', $title);

                $imgFile = $this->request->getFile('img');
                $imgName = '';
                if ($imgFile && $imgFile->isValid()) {
                    $imgName = $truncateTitle . '.' . $imgFile->getClientExtension();
                    $imgFile->move('upload/Banner', $imgName);
                }

                $data = [
                    'title' => $title,
                    'caption' => $this->request->getPost('caption'),
                    'image' => $imgName,
                    'link' => $this->request->getPost('link'),
                    'created_by' => $userData['name']
                ];

                $model->createBanner($data);

                if ($model->affectedRows() > 0) {
                    return $this->respond(['code' => '201', 'message' => 'Success'], 201);
                } else {
                    return $this->fail('Error! Failed to create banner.', 500);
                }
            }
        }

        return $this->respond('Unauthorized', 401);
    }


    public function deletBanners($id = null)
    {
        $token = $this->request->getServer('HTTP_AUTHORIZATION');

        if ($token) {
            $token = str_replace('Bearer ', '', $token);

            $cache = \Config\Services::cache();
            $userData = $cache->get('user_' . $token);

            if ($userData) {
                $model = new BannerModel();

                if ($id === null) {
                    return $this->fail('Banner ID not provided.', 400);
                }

                $bannerData = $model->getBannerId($id);
                $imageFilename = $bannerData['image'];

                $model->delete($id);

                if ($model->affectedRows() > 0) {
                    // Delete the image file from the public/upload/Banner directory
                    $uploadPath = ROOTPATH . 'public/upload/Banner/' . $imageFilename; // Adjust the path based on your setup
                    if (file_exists($uploadPath)) {
                        unlink($uploadPath);
                    }

                    return $this->respondDeleted(['message' => 'Success'], 200);
                } else {
                    return $this->fail('Error! Failed to delete banner.', 500);
                }
            }
        }
    }


}
