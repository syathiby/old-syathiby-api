<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\PostModel;

use CodeIgniter\API\ResponseTrait;

class Api extends ResourceController
{
    use ResponseTrait;
    public function posts()
    {
        $model = new PostModel();
        $post = $model->getpost();

        return $this->respond($post, 200);
    }

    public function postsAdmin()
    {
        $token = $this->request->getServer('HTTP_AUTHORIZATION');
        if ($token) {
            $token = str_replace('Bearer ', '', $token);
            $cache = \Config\Services::cache();
            $user = $cache->get('user_' . $token);

            if ($user) {
                $model = new PostModel();
                $post = $model->getpost();

                return $this->respond($post, 200);
            }
        }

        return $this->respond('Unauthorized', 401);
    }
    public function postAdmin($link = null)
    {
        $token = $this->request->getServer('HTTP_AUTHORIZATION');
        if ($token) {
            $token = str_replace('Bearer ', '', $token);
            $cache = \Config\Services::cache();
            $user = $cache->get('user_' . $token);

            if ($user) {
                $model = new PostModel();

                $post = $model->getPostByLink($link);
            
                if ($post) {
                    return $this->respond($post, 200);
                } else {
                    return $this->fail('Post not found.', 404);
                }
            }
        }

        return $this->respond('Unauthorized', 401);
    }


    public function post($link = null)
    {
        $model = new PostModel();

        $post = $model->getPostByLink($link);
    
        if ($post) {
            return $this->respond($post, 200);
        } else {
            return $this->fail('Post not found.', 404);
        }
    }
    public function showLabelBl($link = null)
    {
        $model = new PostModel();

        $post = $model->getPostByLabel($link);
    
        if ($post) {
            return $this->respond($post, 200);
        } else {
            return $this->fail('Post not found.', 404);
        }
    }

    public function createPost()
    {
        $token = $this->request->getServer('HTTP_AUTHORIZATION');

        if ($token) {
            $token = str_replace('Bearer ', '', $token);

            $cache = \Config\Services::cache();
            $user = $cache->get('user_' . $token);
            if ($user) {
                $model = new PostModel();

                $title = $this->request->getPost('title');
                if (empty($title)) {
                    return $this->fail('Error! Title is required.', 400);
                }

                $link = str_replace(' ', '-', $title);

                // Check if an image file is uploaded
                $imgFile = $this->request->getFile('img');
                $imgName = '';
                if ($imgFile && $imgFile->isValid()) {
                    $imgName = $link . '.' . $imgFile->getClientExtension();
                    $imgFile->move('upload/Post', $imgName);
                }

                $data = [
                    'title' => $title,
                    'img' => $imgName,
                    'description' => $this->request->getPost('description'),
                    'label' => $this->request->getPost('label'),
                    'meta' => $this->request->getPost('meta'),
                    'created_by' => $user['name'],
                    'link' => $link
                ];

                $model->createPost($data);

                if ($model->affectedRows() > 0) {
                    return $this->respondCreated(['message' => 'Success'], 201);
                } else {
                    return $this->fail('Error! Failed to create post.', 500);
                }
            }
        }

        return $this->respond('Unauthorized', 401);
    }


    public function updatePost($id = null)
    {
        $model = new PostModel();

        if ($id === null) {
            return $this->fail('Post ID not provided.', 400);
        }

        $title = $this->request->getPost('title');
        if (empty($title)) {
            return $this->fail('Error! Title is required.', 400);
        }

        $link = str_replace(' ', '-', $title);

        $data = [
            'title' => $title,
            'img' => $this->request->getPost('img'),
            'description' => $this->request->getPost('description'),
            'label' => $this->request->getPost('label'),
            'meta' => $this->request->getPost('meta'),
            'link' => $link
        ];

        $model->updatePosts($id, $data);

        if ($model->affectedRows() > 0) {
            return $this->respond(['message' => 'Success'], 200);
        } else {
            return $this->fail('Error! Failed to update post.', 500);
        }
    }

    public function deletePost($id = null)
    {
        $token = $this->request->getserver('HTTP_AUTHORIZATION');

        if($token) {

            $token = str_replace('Bearer ', '', $token);

            $cache = \Config\Services::cache();
            $user = $cache->get('user_' . $token);
            if ($user) {
                $model = new PostModel();

                if ($id === null) {
                    return $this->fail('Post ID not provided.', 400);
                }

                $model->deletePosts($id);

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
