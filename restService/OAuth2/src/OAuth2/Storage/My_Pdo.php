<?php

namespace OAuth2\Storage;

class My_Pdo extends Pdo {
	protected function checkPassword($user, $password) {
		return password_verify($password, $user["passwd"]);
    }

	public function getUser($username) {
        $stmt = $this->db->prepare($sql = sprintf('SELECT username, passwd, fName, midName, lName, gender, role from %s where username=:username', $this->config['user_table']));
        $stmt->execute(array('username' => $username));

        if (!$userInfo = $stmt->fetch(\PDO::FETCH_ASSOC))
            return false;

        // the default behavior is to use "username" as the user_id
        return array_merge(array(
            'user_id' => $username
        ), $userInfo);
    }

    public function checkUserCredentials($username, $password) {
        if ($user = $this->getUser($username))
            return $this->checkPassword($user, $password);

        return false;
    }
}