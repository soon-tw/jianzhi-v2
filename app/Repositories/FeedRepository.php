<?php
/**
 * Created by PhpStorm.
 * User: chen
 * Date: 17-10-9
 * Time: 上午1:38
 */

namespace App\Repositories;

use App\Model\User;
use App\Repositories\Contracts\FeedRepositoryInterface;
use Illuminate\Support\Facades\Redis;

class FeedRepository implements FeedRepositoryInterface {

    public $user;
    public $feeds;

    public function __construct(User $user) {
        $this->user = $user;
    }

    public function all() {
        $feeds = Redis::lrange('user:'.$this->user->id.':activities',0,-1);
        $this->feeds = array_map("unSerialize",$feeds);
        return $this->feeds;
    }

    public function unSerialize($item) {
        return unserialize($item);
    }
}