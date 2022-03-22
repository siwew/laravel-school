<?php

namespace App\Http\Controllers;

use App\Model\FollowList;
use App\Model\Student;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * 关注列表
     *
     * @param Request $request
     * @return mixed
     */
    public function followList(Request $request)
    {
        $user_id = $request->user()->id;
        $students = FollowList::query()->where('teacher_id', $user_id)->pluck('student_id');
        $list = Student::query()->whereIn('id', $students)->get();

        return api()->success($list);
    }


    /**
     * 操作关注动作
     *
     * @param Request $request
     * @return mixed
     */
    public function follow(Request $request)
    {
        $user_id = $request->user()->id;
        $teacher_id = $request->input('teacher_id');

        $follow = FollowList::query()
            ->where('student_id', $user_id)
            ->where('teacher_id', $teacher_id)
            ->first();

        if (!$follow) {
            $follow = new FollowList();
            $status = 0;
            $follow->teacher_id = $teacher_id;
            $follow->student_id = $user_id;

            $follow->save();
        }
        $follow->update(['status' => !($follow->status)]);

        return api()->success(['msg' => '操作成功']);
    }
}
