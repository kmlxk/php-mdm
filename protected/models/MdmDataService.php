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
        if (strpos($key, self::DEF_NS) !== 0) {
            $key = $namespace . '.' . $key;
        }
        $data = MdmData::model()->find('key=:key', array(':key' => $key));
        return $data;
    }

    public static function create($namespace, $key, $value) {
        if ($namespace == null) {
            $namespace = self::DEF_NS;
        }
        if (strpos($key, self::DEF_NS) !== 0) {
            $key = $namespace . '.' . $key;
        }
        $data = new MdmData();
        $data->key = $key;
        $data->value = $value;
        $data->hash = hexdec(hash('adler32', $value));
        $data->save();
    }

    /**
     * 通过数据版本和摘要(version+hash)的方式来保证更新的原子性
     * 更新前先判断client提供的版本和摘要是否和服务器一致。
     */
    public static function set($namespace, $key, $value, $version, $hash) {

        if ($key == null) {
            throw new Exception('Key cannot be null.');
        }

        $data = self::get($namespace, $key);
        if ($data == null) {
            // 不存在则新增
            self::create($namespace, $key, $value);
        } else {
            if ($version != $data->version ||
                    $hash != $data->hash) {
                // 之前检出的版本过期了，服务器已经更新
                throw new Exception("Data Inconsistency. Server Version:{$data->version}({$data->hash}), Client Version:{$version}({$hash})");
            }
            $data->value = $value;
            $data->hash = hexdec(hash('adler32', $value));
            $data->version++;
            $data->save();
        }
    }

}