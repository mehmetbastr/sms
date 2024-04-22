<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class StudentController extends Controller
{
    /** index page student list */
    public function student()
    {
        $studentList = Student::all();
        return view('student.student',compact('studentList'));
    }

    /** index page student grid */
    public function studentGrid()
    {
        $studentList = Student::all();
        return view('student.student-grid',compact('studentList'));
    }

    /** student add page */
    public function studentAdd()
    {
        return view('student.add-student');
    }

    /** student save record */
    public function studentSave(Request $request)
    {
        DB::beginTransaction();
        try {

            $request->validate([
                'first_name'    => 'required|string',
                'last_name'     => 'required|string',
                'gender'        => 'required|not_in:0',
                'date_of_birth' => 'required|string',
                'roll'          => 'required|string',
                'blood_group'   => 'required|string',
                'religion'      => 'required|string',
                'email'         => 'required|email',
                'class'         => 'required|string',
                'section'       => 'required|string',
                'admission_id'  => 'required|string',
                'phone_number'  => 'required',
                'upload'        => 'required|image',
            ]);

            $upload_file = Carbon::now()->timestamp . '_' .mt_rand(). '.' . $request->upload->extension();
            $request->upload->move(storage_path('app/public/student-photos/'), $upload_file);
            if(!empty($request->upload)) {
                Student::query()->create([
                    'first_name'    => $request->get('first_name'),
                    'last_name'     => $request->get('last_name'),
                    'gender'        => $request->get('gender'),
                    'date_of_birth' => $request->get('date_of_birth'),
                    'roll'          => $request->get('roll'),
                    'blood_group'   => $request->get('blood_group'),
                    'religion'      => $request->get('religion'),
                    'email'         => $request->get('email'),
                    'class'         => $request->get('class'),
                    'section'       => $request->get('section'),
                    'admission_id'  => $request->get('admission_id'),
                    'phone_number'  => $request->get('phone_number'),
                    'upload'        => $upload_file,
                ]);

                Toastr::success('Has been add successfully :)','Success');
                DB::commit();
            }

            return Redirect::route('student/add/page');

        } catch(Exception $e) {
            DB::rollback();
            Toastr::error('fail, Add new student  :)','Error');
            return Redirect::route('student/add/page');
        }
    }

    /** view for edit student */
    public function studentEdit($id)
    {
        $studentEdit = Student::where('id',$id)->first();
        return view('student.edit-student',compact('studentEdit'));
    }

    /** update record */
    public function studentUpdate(Request $request)
    {
        DB::beginTransaction();
        try {

            if (!empty($request->upload)) {
                unlink(storage_path('app/public/student-photos/'.$request->image_hidden));
                $upload_file = Carbon::now()->timestamp . '_' .mt_rand().'.'. $request->upload->extension();
                $request->upload->move(storage_path('app/public/student-photos/'), $upload_file);
            } else {
                $upload_file = $request->image_hidden;
            }

            $updateRecord = [
                'upload' => $upload_file,
            ];
            Student::where('id',$request->id)->update($updateRecord);

            Toastr::success('Has been update successfully :)','Success');
            DB::commit();
            return redirect()->back();

        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('fail, update student  :)','Error');
            return redirect()->back();
        }
    }

    /** student delete */
    public function studentDelete(Request $request)
    {
        /** @var Student $student */
        if ($student = Student::query()->find($request->get('id'))) {
            unlink(storage_path('app/public/student-photos/'.$student->getAvatarPath()));
            $student->delete();
            Toastr::success('Student deleted successfully :)', 'Success');

            return Redirect::route('student/list');
        }

        Toastr::error('Student not found', 'Error');

        return Redirect::route('student/list');
    }

    /** student profile page */
    public function studentProfile($id)
    {
        $studentProfile = Student::where('id',$id)->first();
        return view('student.student-profile',compact('studentProfile'));
    }
}
