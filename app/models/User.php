<?php

final class User 
{
    private $_connect_db;
    public function __construct(){
        $this->_connect_db = new Database;
    }

    public function login($email, $password) {
        $this->_connect_db->query("SELECT  users.id, users.name, users.email, users.role_id, roles.name AS role_name, users.created_at, users.updated_at, users.password FROM users INNER JOIN roles ON users.role_id = roles.id WHERE users.email = :email LIMIT 1");
    
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
    
	public function update_user_password($userId, $newPass, $hashedPassword, $oldPassword){
		$this->_connect_db->query('SELECT * FROM `users` WHERE id =:userId');
		// Bind the values
		$this->_connect_db->bind(':userId', $userId);
		$row = $this->_connect_db->single();
		$response =array();
		if(!empty($row)){
			$db_password = $row->password;
			if(password_verify($oldPassword, $db_password)){
                if(password_verify($newPass, $db_password)){
                    $response = array('status' => 'error', 'message' => 'This new password looks similar to the previous one, we recommend not to use similar password or same password twice..');
                }else{
                    $this->_connect_db->query('UPDATE users SET password = :hashedPassword, updated_at= NOW() WHERE id=:userId');
                    $this->_connect_db->bind(':hashedPassword', $hashedPassword);
                    $this->_connect_db->bind(':userId', $userId);
                    if ($this->_connect_db->execute()) {
                        $response = array('status' => 'success', 'message' => 'Password successfully updated!.');
                    }else {
                        $response = array('status' => 'error', 'message' => 'Fail to updated');
                    }
                }
			}else {
				$response = array('status' => 'success', 'message' => 'The provided Old password does not match the user current password.');
			}
			return($response);
		}
	}

    public function update_user_details($userId, $role, $email, $telephone, $username){
        $this->_connect_db->query('SELECT * FROM `users` WHERE id =:userId');
		// Bind the values
		$this->_connect_db->bind(':userId', $userId);
		$row = $this->_connect_db->single();
		$response =array();
		if(!empty($row)){
            $this->_connect_db->query('UPDATE `users` SET `name`=:username,`email`=:email, `role_id`=:role,`telephone`=:telephone,`updated_at`= NOW() WHERE id=:userId');
            $this->_connect_db->bind(':role', $role);
            $this->_connect_db->bind(':userId', $userId);
            $this->_connect_db->bind(':telephone', $telephone);
            $this->_connect_db->bind(':username', $username);
            $this->_connect_db->bind(':email', $email);
            if ($this->_connect_db->execute()) {
                
                $response = array('status' => 'success', 'message' => 'Profile successfully updated!.');
            }else {
                $response = array('status' => 'error', 'message' => 'Fail to updated');
            }
        }else {
            $response = array('status' => 'not_found', 'message' => 'User not found with this Id ('.$userId.')');
        }
        return($response);
    }

    public function findById($id){
        $this->_connect_db->query("SELECT  users.id, users.name, users.email, users.role_id, roles.name AS role_name, users.created_at, users.updated_at, users.password FROM users INNER JOIN roles ON users.role_id = roles.id WHERE users.id = :id LIMIT 1");
        $this->_connect_db->bind(':id', $id);
        $user = $this->_connect_db->single();
    
        if (!empty($user)) {
            unset($user->password);
            return $user;
        }
    }

    public function findAll(){
        $this->_connect_db->query(/** @lang text */" SELECT users.id, users.name, users.email, users.role_id, roles.name AS role_name, users.created_at, users.updated_at FROM users INNER JOIN roles ON users.role_id = roles.id");
        $row = $this->_connect_db->fetchAll();
        if(!empty($row)){
            return $row;
        }else{
            return [];
        }
    }

    public function delete_user($ids) {
        if (!is_array($ids) || empty($ids)) return false;
        $placeholders = [];
        foreach ($ids as $index => $val) {
            $placeholders[] = ":id{$index}";
        }
        $inClause = implode(',', $placeholders);
        $sql = "DELETE FROM users WHERE id IN ($inClause)";
    
        $this->_connect_db->query($sql);
        foreach ($ids as $index => $val) {
            $this->_connect_db->bind(":id{$index}", $val);
        }
        return $this->_connect_db->execute();
    }

    public function findAllRoles(){
        $this->_connect_db->query(/** @lang text */"SELECT `id`, `name` FROM `roles`");
        $row = $this->_connect_db->fetchAll();
        if(!empty($row)){
            return $row;
        }else{
            return [];
        }
    }

    public function create_new($role, $email, $telephone, $username, $hash_password){
        $this->_connect_db->query(/** @lang text */"INSERT INTO `users`(`name`, `email`, `password`, `role_id`, `telephone`, `created_at`) VALUES (:username, :email, :hash_password, :role, :telephone, NOW())");
        $this->_connect_db->bind(':role', $role);
        $this->_connect_db->bind(':hash_password', $hash_password);
        $this->_connect_db->bind(':telephone', $telephone);
        $this->_connect_db->bind(':username', $username);
        $this->_connect_db->bind(':email', $email);
        if ($this->_connect_db->execute()) {
            return true;
        }else{
            return false;
        }
    }

    public function findByEmail($email):bool{
        $this->_connect_db->query("SELECT * FROM `users` WHERE email=:email");
        $this->_connect_db->bind(':email', $email);
        $user = $this->_connect_db->single();
        if (!empty($user)) {
            return true;
        }else{
            return false;
        }
    }
}
