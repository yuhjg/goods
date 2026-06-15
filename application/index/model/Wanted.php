<?php
namespace app\index\model;

use think\Model;

class Wanted extends Model
{
    protected $name = 'wanted';

    // 状态常量
    const STATUS_ACTIVE    = 1; // 求购中
    const STATUS_COMPLETED = 2; // 已完成
    const STATUS_CANCELLED = 3; // 已撤消

    public static $statusMap = [
        self::STATUS_ACTIVE    => '求购中',
        self::STATUS_COMPLETED => '已完成',
        self::STATUS_CANCELLED => '已撤消',
    ];

    /**
     * 获取器 - 格式化时间
     */
    public function getCreateTimeAttr($value)
    {
        return $value > 0 ? date('Y-m-d H:i', $value) : '';
    }

    /**
     * 获取状态文本
     */
    public function getStatusTextAttr($value, $data)
    {
        return isset(self::$statusMap[$data['status']]) ? self::$statusMap[$data['status']] : '未知';
    }
}
