<?php

class User extends ModelCRUD
{
    private $_sessionName,
            $_cookieName,
            $_isLoggedIn;

    public function __construct($user = null)
    {
        parent::__construct('user');

        $this->_sessionName = Config::get('session/session_name');

        $this->_cookieName  = Config::get('remember/cookie_name');

        if (! $user)
        {
            if (Session::exists($this->_sessionName))
            {
                $user = Session::get($this->_sessionName);

                if ($this->find($user))
                {
                    $this->_isLoggedIn = true;
                }
            }

        }
        else
        {
            $this->find($user);
        }
    }

    public function update($id, array $data)
    {
        if (!$id && $this->isLoggedIn())
        {
            $id = $this->data()['id' . $this->_table];
        }

        if (!$this->_db->update($this->_table, "id_" . $this->_table, $id, $data))
        {
            throw new Exception('Unable to update the user.');
        }
    }

    public function find($user = null)
    {
        if ($user)
        {
            $field  = (is_numeric($user)) ? 'id_user' : 'username';

            $data = $this->_db->get($this->_table, array($field, '=', $user));

            if ($data->count())
            {
                $this->_data = $data->first();
                return true;
            }
        }
    }

    public function login($username = null, $password = null, $remember = false)
    {
        if (! $username && ! $password && $this->exists())
        {
            Session::put($this->_sessionName, $this->data()['id' . $this->_table]);
        }
        else
        {
            $user = $this->find($username);

            if ($user)
            {
                if (Password::check($password, $this->data()['password']))
                {
                    Session::put($this->_sessionName, $this->data()['id' . $this->_table]);

                    if ($remember)
                    {
                        $hash       = Hash::unique();
                        $hashCheck  = $this->_db->get('user_session', array('user', '=', $this->data()['id' . $this->_table]));

                        if (!$hashCheck->count())
                        {
                            $this->_db->insert('user_session', array(
                                'user'   => $this->data()['id' . $this->_table],
                                'hash'      => $hash
                            ));
                        }
                        else
                        {
                            $hash = $hashCheck->first()['hash'];
                        }

                        Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));
                    }

                    return true;
                }
            }
        }

        return false;
    }

    public function hasPermission($key)
    {
        $group = $this->_db->get('group', array('id_group', '=', $this->data()['group']));

        if  ($group->count())
        {
            $permissions = json_decode($group->first()['permissions'], true);

            if ($permissions[$key] == true)
            {
                return true;
            }
        }

        return false;
    }

    public function exists()
    {
        return (!empty($this->_data)) ? true : false;
    }

    public function logout()
    {
        $this->_db->delete('user_session', array('user', '=', $this->data()['id' . $this->_table]));

        Session::delete($this->_sessionName);
        Cookie::delete($this->_cookieName);
    }

    public function isLoggedIn()
    {
        return $this->_isLoggedIn;
    }

    public function deleteMe()
    {
        if ($this->isLoggedIn())
        {
            $id = $this->data()['id' . $this->_table];
        }

        if (!$this->_db->delete($this->_table, array('id_user', '=', $id)))
        {
            throw new Exception('Unable to update the user.');
        }
    }
}
