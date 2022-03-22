<?php

namespace App\Http\Controllers;

use App\Model\School;
use App\Model\SchoolTeacher;
use App\Model\Student;
use App\Model\Teacher;
use Illuminate\Http\Request;

class SchoolController extends Controller
{

    protected $user_id;

    public function __construct(Request $request)
    {
        $this->user_id = $request->user()->id;
    }


    /**
     * 申请创建学校
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createSchool(Request $request)
    {
        $school_name = $request->get('name');


        $school = School::query()->where('name', $school_name)->exists();
        if ($school) {
            return api()->failed('学校名称已存在');
        }

        $school_data = [
            'name' => $school,
            'admin_user' => $request->user()->id,
            'status' => 0,
        ];

        $school = new School($school_data);
        $school->save();

        return api()->success(['message' => '申请成功']);
    }


    /**
     * 获取学校教师列表
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function teachers(Request $request)
    {
        $school_id = $request->get('school_id', '');

        $teacher_ids = SchoolTeacher::query()->where('school_id', $school_id)->pluck('teacher_id');
        $teachers = Teacher::query()->whereIn('id', $teacher_ids)->get()->toArray();

        return api()->success(['teachers' => $teachers]);
    }


    /**
     * 获取学校学生列表
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function students(Request $request)
    {
        $school_id = $request->get('school_id', '');

        $students = Student::query()->where('school_id', $school_id)->get();

        return api()->success(['students' => $students]);
    }


    /**
     * 邀请老师加入学校
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function inviteTeacher(Request $request)
    {
        $school_id = $request->get('school_id', '');
        $email = $request->get('email', '');

        if (empty($email)) {
            return api()->failed('邮箱不能为空');
        }

        $school = School::query()->where('id', $school_id)->first();
        if (!$school) {
            return api()->failed('错误的学校');
        }

        if ($school->admin_user != $this->user_id) {
            return api()->failed('没有权限');
        }
        $invite_url = '' . $school_id;
        sendMail($email, '邀请加入学校'. '<a href="' . $invite_url . '">点击加入</a>');

        return response()->json(['message' => '邀请成功'], 200);
    }


    /**
     * 通过邮件链接直接进入或先注册再加入
     *
     * @param Request $request
     * @return mixed
     */
    public function joinSchool(Request $request)
    {
        $school_id = $request->get('school_id', '');

        $school = School::query()->where('id', $school_id)->first();
        if (!$school) {
            return api()->failed('错误的学校');
        }

        $user = $request->user();
        $user->school_id = $school_id;
        $user->save();

        return api()->success(['message' => '加入成功']);
    }


    /**
     * 新增学生
     *
     * @param Request $request
     * @return mixed
     */
    public function studentAdd(Request $request)
    {
        $name = $request->get('name', '');
        $school_id = $request->get('school_id', '');
        $account = $request->get('account', '');
        $password = $request->get('password', '');

        if (empty($name)) {
            return api()->failed('姓名不能为空');
        }

        if (empty($account)) {
            return api()->failed('账号不能为空');
        }

        if (empty($password)) {
            return api()->failed('密码不能为空');
        }

        $student = new Student();
        $student->save($request->all());

        return api()->success(['message' => '添加成功']);
    }
}
