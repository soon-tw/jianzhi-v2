<?php
/**
 * Created by PhpStorm.
 * User: chen
 * Date: 17-10-9
 * Time: 上午1:48
 */

namespace App\Repositories;

use App\Model\Employer;
use App\Repositories\Contracts\NotificationRepositoryInterface;
use Illuminate\Support\Facades\Redis;

class EmployerNotificationRepository implements NotificationRepositoryInterface {

    public $employer;
    public $unReadNotifications;
    public $allNotifications;

    public function __construct(Employer $employer) {
        $this->employer = $employer;
    }

    public function all() {
        $allNotifications = Redis::lrange('employer:'.$this->employer->id.':notifications',0,-1);
        $this->allNotifications = array_map("unSerialize",$allNotifications);
        return $this->allNotifications;
    }

    public function unRead() {
        $unReadNotifications = Redis::lrange('employer:'.$this->employer->id.':notifications:unread',0,-1);
        $this->unReadNotifications = array_map("unSerialize",$unReadNotifications);
        return $this->unReadNotifications;
    }

    public function markAsRead() {
        $unReadNotifications = Redis::lrange('employer:'.$this->employer->id.':notifications:unread',0,-1);
        $addedResult = Redis::lpush('employer:'.$this->employer->id.':notifications',$unReadNotifications);
        $removeUnRead = Redis::del('employer:'.$this->employer->id.':notifications:unread');
    }

    public function unSerialize($item) {
        return unserialize($item);
    }
}