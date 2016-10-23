<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    //use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['username', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];


    protected $primaryKey = 'uid';

    public function info()
    {
        return $this->hasOne('App\Userinfo', 'uid');
    }

    public static function getUserInPage($user_per_page, $page_id, $gid, $search_username)
    {
        $data = [];
        $data['users'] = [];
        if($search_username == "" && $gid == 0 )
            $userObj = User::all();
        else if($search_username != "" && $gid != 0)
            $userObj = User::where('gid', $gid)->where('username', 'like', '%'.$search_username.'%')->get();
        else if($search_username != "")
            $userObj = User::where('username', 'like', '%'.$search_username.'%')->get();
        else if($gid != 0)
            $userObj = User::where('gid', $gid)->get();
        $usernum = $userObj->count();
        for($count = 0, $i = ($page_id - 1) * $user_per_page; $i < $usernum && $count < $user_per_page; $i++, $count++)
        {
            $data['users'][$count] = $userObj[$i];
        }
        $data['page_num'] = ceil($usernum / $user_per_page);
        $data['page_id'] = $page_id;
        $data['page_user'] = $user_per_page;
        return $data;
    }
}
