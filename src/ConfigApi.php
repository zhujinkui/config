<?php
declare (strict_types=1);

namespace think;

use think\facade\Db;

/**
 * | Notes：数据库配置类
 * +----------------------------------------------------------------------
 * | PHP Version 7.2+
 * +----------------------------------------------------------------------
 * | Copyright (c) 2011-2020 https://www.xxq.com.cn, All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: 阶级娃儿 <devloper@zhujinkui.com>
 * +----------------------------------------------------------------------
 * | Date: 2020/7/6 17:49
 * +----------------------------------------------------------------------
 */
class ConfigApi
{
    /**
     * 获取数据库中的配置列表
     *
     * @param int $cache
     *
     * @return array
     */
    public static function lists($cache = 0): array
    {
        $map = [
            'status' => 1
        ];

        $data = Db::name('config')
            ->where($map)
            ->field('type,name,value')
            ->cache($cache)
            ->select()
            ->toArray();

        $config = [];

        if ($data && is_array($data)) {
            foreach ($data as $value) {
                $config[$value['name']] = self::parse($value['type'], $value['value']);
            }
        }

        return $config;
    }

    /**
     * 根据配置类型解析配置
     *
     * @param $type
     * @param $value
     *
     * @return array|false|string[]
     */
    private static function parse(int $type, string $value)
    {
        switch ($type) {
            case 3:
                //解析数组
                $array = preg_split('/[,;\r\n]+/', trim($value, ",;\r\n"));

                if (strpos($value, ':')) {
                    $value = [];
                    foreach ($array as $val) {
                        list($k, $v) = explode(':', $val);
                        $value[$k] = $v;
                    }
                } else {
                    $value = $array;
                }
                break;
        }

        return $value;
    }
}