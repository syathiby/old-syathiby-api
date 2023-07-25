<?php

namespace App\Controllers;

use App\Models\GalleryModel;
use App\Models\KategoriModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class Gallery extends ResourceController
{
    use ResponseTrait;
    public function createGallery()
    {
        $token = $this->request->getServer('HTTP_AUTHORIZATION');

        if($token)
        {
            $token = str_replace('Bearer ', '', $token);

            $cache = \Config\Services::cache();
            $userData = $cache->get('user_' . $token);

            if($userData)
            {
                $model = new GalleryModel();

                $type = $this->request->getPost('type');

                if( $type == 'photo' )
                {
                    $title = $this->request->getPost('title');
                    $filename = str_replace(' ', '-', $title);

                    $imgFile = $this->request->getFile('img');
                    $imgName = '';
                    if ($imgFile && $imgFile->isValid()) {
                        $imgName = $filename . '.' . $imgFile->getClientExtension();
                        $imgFile->move('upload/Galeri', $imgName);
                    }

                    $data = [
                        'title' => $title,
                        'type' => $type,
                        'filename' => $imgName,
                        'caption' => $this->request->getPost('caption'),
                        'kategori' => $this->request->getPost('kategori'),
                        'created_by' => $userData['name']
                    ];

                    $model->addGaleri($data);

                    if ($model->affectedRows() > 0) {
                        return $this->respondDeleted(['message' => 'Success'], 200);
                    } else {
                        return $this->fail('Error! Failed to delete post.', 500);
                    }
                    
                }else if($type == 'video'){

                    $data = [
                        'title' => $this->request->getPost('title'),
                        'type' => $type,
                        'filename' => $this->request->getPost('filename'),
                        'caption' => $this->request->getPost('caption'),
                        'kategori' => $this->request->getPost('kategori'),
                        'created_by' => $userData['name']
                    ];

                    $model->addGaleri($data);

                    if ($model->affectedRows() > 0) {
                        return $this->respondDeleted(['message' => 'Success'], 200);
                    } else {
                        return $this->fail('Error! Failed to delete post.', 500);
                    }
                }else{
                    return $this->fail(['code' => '400', 'message' => 'Type must be video or photo'], 400);
                }

            }
        }

        return $this->respond('Unauthorized', 401);
    }

    public function getGal()
    {
        $token = $this->request->getServer('HTTP_AUTHORIZATION');

        if($token)
        {
            $token = str_replace('Bearer ', '', $token);

            $cache = \Config\Services::cache();
            $userData = $cache->get('user_' . $token);

            if($userData)
            {
                $model = new GalleryModel();

                $data = $model->getGaleri();

                return $this->respond($data);
            }
        }

        return $this->respond('Unauthorized', 401);
    }
    public function deleteGaleri($id = null)
    {
        $token = $this->request->getServer('HTTP_AUTHORIZATION');

        if($token)
        {
            $token = str_replace('Bearer ', '', $token);

            $cache = \Config\Services::cache();
            $userData = $cache->get('user_' . $token);

            if($userData)
            {
                $model = new GalleryModel();

                $model->deleteGal($id);

                if ($model->affectedRows() > 0) {
                    return $this->respondDeleted(['message' => 'Success'], 201);
                } else {
                    return $this->fail('Error! Failed to delete post.', 500);
                }
            }
        }

        return $this->respond('Unauthorized', 401);
    }

    // Crud Kategori for Galeri
    public function addKategori()
    {
        $token = $this->request->getServer('HTTP_AUTHORIZATION');
        
        if($token)
        {
            $token = str_replace('Bearer ', '', $token);

            $cache = \Config\Services::cache();
            $userData = $cache->get('user_' . $token);
            
            if($userData)
            {
                $model = new KategoriModel();

                $data = [
                    'nkategori' => $this->request->getPost('nkategori'),
                    'created_by' => $userData['name']
                ];
                
                $model->createKategori($data);

                if ($model->affectedRows() > 0) {
                    return $this->respondDeleted(['message' => 'Success'], 201);
                } else {
                    return $this->fail('Error! Failed to delete post.', 500);
                }
            }
        }

        return $this->respond('Unauthorized!', 401);
    }

    public function getKategori()
    {
        $token = $this->request->getServer('HTTP_AUTHORIZATION');

        if($token)
        {
            $token = str_replace('Bearer ', '', $token);
            $cache = \Config\Services::cache();

            $userData = $cache->get('user_' . $token);

            if($userData)
            {
                $model = new KategoriModel();

                $data = $model->getKat();

                return $this->respond($data, 200);
            }
        }

        return $this->respond('Unauthorized!', 401);
    }

    public function deleteKategori($id = null)
    {
        $token = $this->request->getServer('HTTP_AUTHORIZATION');

        if ($token){
            $token = str_replace('Bearer ', '', $token);

            $cache = \Config\Services::cache();
            $userData = $cache->get('user_' . $token);

            if($userData){
                $model = new KategoriModel();

                if ($id === null) {
                    return $this->fail('Post ID not provided.', 400);
                }

                $model->deleteKat($id);

                if ($model->affectedRows() > 0) {
                    return $this->respondDeleted(['message' => 'Success'], 200);
                } else {
                    return $this->fail('Error! Failed to delete post.', 500);
                }
            }
        }
    }
}
