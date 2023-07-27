<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\AuthModel;
use CodeIgniter\API\ResponseTrait;

class Auth extends ResourceController
{
    use ResponseTrait;

    public function getUsers()
    {
        $token = $this->request->getServer('HTTP_AUTHORIZATION');
        if ($token) {
            $token = str_replace('Bearer ', '', $token);

            $cache = \Config\Services::cache();
            $userData = $cache->get('user_' . $token);

            if ($userData) {
                return $this->respond($userData);
            }
        }

        // User authentication failed, return an error response
        return $this->failUnauthorized('Unauthorized');
    }

    public function getUserData()
    {
        $token = $this->request->getServer('HTTP_AUTHORIZATION');
        if ($token) {
            $token = str_replace('Bearer ', '', $token);

            $cache = \Config\Services::cache();
            $userData = $cache->get('user_' . $token);

            if ($userData) {

                $model = new AuthModel();

                $data = $model->getUserData();

                return $this->respond($data, 200);
            }
        }

        // User authentication failed, return an error response
        return $this->failUnauthorized('Unauthorized');
    }

    public function createUser()
    {
        $model = new AuthModel();

        $password = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);

        $data = [
            'name' => $this->request->getPost('name'),
            'username' => $this->request->getPost('username'),
            'password' => $password,
            'role' => $this->request->getPost('role'),
            'photo' => $this->request->getPost('photo')
        ];

        $model->createNew($data);

        if ($model->affectedRows() > 0) {
            return $this->respond(['message' => 'Success'], 200);
        } else {
            return $this->fail('Error! Failed to update post.', 500);
        }
    }

    public function signIn()
    {
        $model = new AuthModel();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $model->login($username, $password);

        if ($user) {
            $cache = \Config\Services::cache();
            $token = $user['token'];
            $cache->save('user_' . $token, $user, 86400); 

            return $this->respond($user);
        } else {
            // User authentication failed
            return $this->failUnauthorized('Invalid username or password');
        }
    }

    public function logoutUser()
    {
        $model = new AuthModel();

        // Get the user's ID from the session or wherever it is stored
        $userId = '1';

        $loggedOut = $model->logout($userId);

        if ($loggedOut) {
            // Remove user data from cache
            $cache = \Config\Services::cache();
            $token = $this->request->getServer('HTTP_AUTHORIZATION');
            if ($token) {
                $token = str_replace('Bearer ', '', $token);
                $cache->delete('user_' . $token);
            }

            return $this->respond(['message' => 'Logged out successfully'], 200);
        } else {
            return $this->fail('Failed to logout', 500);
        }
    }

    public function deleteUser($id = null)
    {
        $token = $this->request->getServer('HTTP_AUTHORIZATION');

        if ($token){
            $token = str_replace('Bearer ', '', $token);

            $cache = \Config\Services::cache();
            $userData = $cache->get('user_' . $token);

            if($userData){
                $model = new AuthModel();

                if ($id === null) {
                    return $this->fail('Post ID not provided.', 400);
                }

                $model->deleteUs($id);

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
