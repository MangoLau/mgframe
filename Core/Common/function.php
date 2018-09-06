<?php
function p($d) {
	echo '<pre>';
	print_r($d);
	echo '</pre>';
	exit;
}

/**
 * post
 *
 * @param $name
 * @param null $default
 * @param string $filter
 * @return null
 */
function post($name, $default = null, $filter = '') {
    if (isset($_POST[$name])) {
        if ($filter) {
            switch ($filter) {
                case 'int' :
                    if (is_numeric($_POST[$name])) {
                        return $_POST[$name];
                    } else {
                        return $default;
                    }
                    break;
                default :
                    return $filter($_POST[$name]);
                    break;
            }
        } else {
            return $_POST[$name];
        }
    } else {
        return $default;
    }
}

/**
 * get
 *
 * @param $name
 * @param null $default
 * @param string $filter
 * @return null
 */
function get($name, $default = null, $filter = '') {
    if (isset($_GET[$name])) {
        if ($filter) {
            switch ($filter) {
                case 'int' :
                    if (is_numeric($_GET[$name])) {
                        return $_GET[$name];
                    } else {
                        return $default;
                    }
                    break;
                default :
                    return $filter($_GET[$name]);
                    break;
            }
        } else {
            return $_GET[$name];
        }
    } else {
        return $default;
    }
}