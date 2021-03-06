<?php

namespace App;

use Config;
use App\SlaveNodeConnectionDirectory;
use App\User;

class Helper {

    public static function getConnection($partition) {

        if ($partition > 0) {
            $conn_name = 'slave' . $partition;
            $connection = SlaveNodeConnectionDirectory::find($partition);
            if ($connection !== NULL) {
                Config::set('database.connections.' . $conn_name, array(
                    'driver' => 'mysql',
                    'host' => $connection->host,
                    'port' => $connection->port,
                    'database' => $connection->database,
                    'username' => $connection->username,
                    'password' => $connection->password,
                    'charset' => 'utf8',
                    'collation' => 'utf8_unicode_ci',
                    'prefix' => '',
                    'strict' => true,
                    'engine' => null,
                ));
            }

            return $conn_name;
        } else {
            return 'mysql';
        }
    }

}
