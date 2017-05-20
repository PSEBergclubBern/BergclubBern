<?php

global $wpOptions;

function get_option($key)
{
    global $wpOptions;

    if (isset($wpOptions[$key])) {
        return $wpOptions[$key];
    }

    return null;
}

function update_option($key, $value)
{
    global $wpOptions;
    $wpOptions[$key] = $value;
}

function delete_option($key)
{
    global $wpOptions;
    unset($wpOptions[$key]);
}

function get_user_by($field, $value)
{
    global $wpUsers;
    if ($field == 'ID') {
        foreach ($wpUsers as $wpUser) {
            if ($wpUser->ID == $value) {
                return $wpUser;
            }
        }
    } elseif ($field == 'login') {
        foreach ($wpUsers as $wpUser) {
            if ($wpUser->data['user_login'] == $value) {
                return $wpUser;
            }
        }
    }

    return null;
}

function get_users($args = null)
{
    global $wpUsers;
    if (!$args) {
        return $wpUsers;
    } elseif (isset($args['role'])) {
        $result = [];
        foreach ($wpUsers as $wpUser) {
            if (in_array($args['role'], $wpUser->roles)) {
                $result[] = $wpUser;
            }
        }
        return $result;
    }

    return [];
}

function get_user_meta($id)
{
    global $wpUsersMeta;
    if (isset($wpUsersMeta[$id])) {
        return $wpUsersMeta[$id];
    }

    return null;
}

function get_current_user_id()
{
    return 1;
}

function wp_insert_user($args)
{
    global $wpUsers;
    global $wpUsersMeta;

    $lastUser = array_values(array_slice($wpUsers, -1))[0];
    $id = $lastUser->ID + 1;


    $tm = date("U");

    $wpUser = new \WP_User();
    $wpUser->data = [
        'ID' => $id,
        'user_login' => $args['user_login'],
        'user_pass' => '$P$B374xSqLsoDdG5.zVyvNTy1wJjpoUW.',
        'user_nicename' => $args['user_login'],
        'user_email' => $args['user_email'],
        'user_registered' => date('Y-m-d H:i:s', $tm),
        'user_activation_key' => null,
        'user_status' => 0,
        'display_name' => $args['user_login'],
    ];
    $wpUser->ID = $id;
    $wpUser->caps = [];
    $wpUser->cap_key = 'wp_capabilities';
    $wpUser->roles = [];
    $wpUser->allcaps = ['read' => null];

    $wpUsers[] = $wpUser;

    $wpUsersMeta[$id] = [
        'nickname' => $args['user_login'],
        'description' => [null],
        'rich_editing' => [true],
        'comment_shortcuts' => [false],
        'admin_color' => ['fresh'],
        'use_ssl' => [0],
        'show_admin_bar_front' => [true],
        'locale' => [null],
        'wp_capabilities' => [serialize([])],
        'wp_user_level' => [0],
        'dismissed_wp_pointers' => [null],
    ];

    return $id;
}

function wp_update_user($args)
{
    global $wpUsers;
    if (isset($args['ID'])) {
        $id = $args['ID'];
        $wpUser = get_user_by('ID', $id);
        foreach ($args as $key => $value) {
            $wpUser->data[$key] = $value;
        }
    }
}

function update_user_meta($id, $field, $value)
{
    global $wpUsersMeta;
    if (is_array($value) || is_object($value)) {
        $value = serialize($value);
    }
    $wpUsersMeta[$id][$field] = [$value];
}

function wp_delete_user($id)
{
    global $wpUsers;
    global $wpUsersMeta;
    foreach ($wpUsers as $key => $wpUser) {
        if ($wpUser->ID == $id) {
            unset($wpUsers[$key]);
            $wpUsers = array_values($wpUsers);
            break;
        }
    }
    unset($wpUsersMeta[$id]);
}

function username_exists($username)
{
    global $wpUsers;

    foreach ($wpUsers as $wpUser) {
        if ($wpUser->data['user_login'] == $username) {
            return true;
        }
    }

    return false;
}

function sanitize_email($value)
{
    return $value;
}

function sanitize_text_field($value)
{
    return $value;
}

function wp_lostpassword_url()
{
    return "http://lostpassword.com";
}

function wp_mail($to, $subject, $message)
{
    global $wpMail;
    $wpMail[] = ['to' => $to, 'subject' => $subject, 'message' => $message];
}

function get_role($name)
{
    global $wp_roles;

    if (isset($wp_roles->roles[$name])) {
        return new WP_Role($name, $wp_roles->roles[$name]['capabilities']);
    }

    return null;
}

function add_role($key, $name, $capabilities)
{
    global $wp_roles;
    $wp_roles->roles[$key] = ['name' => $name, 'capabilities' => $capabilities];
}

function remove_role($name)
{
    global $wp_roles;
    unset($wp_roles->roles[$name]);
}


class WP_User
{
    public $ID;
    public $data;
    public $caps = [];
    public $cap_key = "wp_capabilities";
    public $roles = [];
    public $allcaps = ["read" => null];

    public function add_role($role)
    {
        if ($this->ID) {
            $this->caps[$role] = 1;
            if (!in_array($role, $this->roles)) {
                $this->roles[] = $role;
            }
            $this->allcaps[$role] = 1;

            update_user_meta($this->ID, 'wp_capabilities', $this->roles);
        }
    }

    public function remove_role($role)
    {
        if ($this->ID) {
            unset($this->caps[$role]);
            foreach ($this->roles as $key => $value) {
                if ($value == $role) {
                    unset($this->roles[$key]);
                }
            }
            unset($this->allcaps[$role]);

            update_user_meta($this->ID, 'wp_capabilities', $this->roles);
        }
    }
}

class WP_Role
{
    public $name;
    public $capabilities = [];

    public function __construct($name, array $capabilities)
    {
        $this->name = $name;
        $this->capabilities = $capabilities;
    }

    public function add_cap($capability, $grant)
    {
        global $wp_roles;
        $this->capabilities[$capability] = $grant;
        $wp_roles->roles[$this->name]['capabilities'][$capability] = $grant;
    }
}