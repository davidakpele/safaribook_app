<?php

final class User 
{
    private $_connect_db;
    public function __construct(){
        $this->_connect_db = new Database;
    }

    public function login($email, $password) {
        $this->_connect_db->query("
            SELECT 
                users.id,
                users.name,
                users.email,
                users.role_id,
                roles.name AS role_name,
                users.created_at,
                users.updated_at,
                users.password
            FROM users
            INNER JOIN roles ON users.role_id = roles.id
            WHERE users.email = :email
            LIMIT 1
        ");
    
        $this->_connect_db->bind(':email', $email);
        $user = $this->_connect_db->single();
    
        if (!empty($user)) {
            $hashedPassword = $user->password;
    
            if (password_verify($password, $hashedPassword)) {
                // Remove password before returning
                unset($user->password);
                return $user;
            } else {
                return false;
            }
        }
    
        return false;
    }
    
	
	public function updateUserPassword($userId, $hashedPassword, $oldPassword){
		$this->_connect_db->query('SELECT * FROM `users` WHERE id =:userId');
		// Bind the values
		$this->_connect_db->bind(':userId', $$userId);
		$row = $this->_connect_db->single();
		$response =array();
		if(!empty($row)){
			$db_password = $row->password;
			if(password_verify($oldPassword, $db_password)){
				$this->_connect_db->query('UPDATE users SET password = :hashedPassword WHERE id=:userId');
				$this->_connect_db->bind(':hashedPassword', $hashedPassword);
				$this->_connect_db->bind(':userId', $$userId);
				if ($this->_connect_db->execute()) {
				    $response = array('status' => 'success', 'message' => 'Password successfully updated!.');
                    http_response_code(200);
				}else {
				    $response = array('status' => 'error', 'message' => 'Fail to updated');
                    http_response_code(400);
				}
			}else {
				$response = array('status' => 'success', 'message' => 'The provided Old password does not match the user current password.');
                http_response_code(400);
			}
			echo json_encode($response);
		}
	}

    

}
