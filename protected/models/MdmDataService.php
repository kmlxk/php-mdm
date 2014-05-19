<?php

class MdmDataService {
    const DEF_NS = 'main';

    public static function get($namespace, $key) {
        if ($key == null) {
            throw new Exception('Key cannot be null.');
        }
        if ($namespace == null) {
            $namespace = self::DEF_NS;
        }

        $key = $namespace . '.' . $key;
        $data = MdmData::model()->find('key=:key', array(':key' => $key));
        return $data;
    }

    public static function create($namespace, $key, $value) {
        if ($namespace == null) {
            $namespace = self::DEF_NS;
        }
        $key = $namespace . '.' . $key;
        $data = new MdmData();
        $data->key = $key;
        $data->value = $value;
        $data->save();
    }

    public static function set($namespace, $key, $value, $version) {

        if ($key == null) {
            throw new Exception('Key cannot be null.');
        }

        $data = self::get($namespace, $key);
        if ($data == null) {
            // 不存在则新增
            self::create($namespace, $key, $value);
        } else {
            if ($version > 0) {
                if ($version < $data->version) {
                    // 之前检出的版本过期了，服务器已经更新
                    throw new Exception("Server data has been changed to Version: {$data->version}, newwer than {$version}");
                }
            }
            $data->value = $value;
            $data->version++;
            $data->save();
        }
    }

}