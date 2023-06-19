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
        // Check if the bearer token is present in the request headers
        $token = $this->request->getServer('HTTP_AUTHORIZATION');
        if ($token) {
            // Remove the 'Bearer ' prefix from the token
            $token = str_replace('Bearer ', '', $token);

            // Retrieve the user data from the cache based on the token
            $cache = \Config\Services::cache();
            $userData = $cache->get('user_' . $token);

            if ($userData) {
                // User authentication successful, return the user data
                return $this->respond($userData);
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
            // User authentication successful, save user data to cache
            $cache = \Config\Services::cache();
            $token = $user['token'];
            $cache->save('user_' . $token, $user, 3600); // Cache for 1 hour

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
}
