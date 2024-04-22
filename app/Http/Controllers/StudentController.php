<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Redirect;
use App\Models\Student;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
                // 'student_number'=> 'required|string',
                'blood_group'   => 'required|string',
                'email'         => 'required|email',
                'class'         => 'required|string',
                'phone_number'  => 'required',
                'upload'        => 'required|image',
                // 'school'        => 'required|string',
                // 'educations'    => 'required|string',
                // 'events'        => 'required|string',
                // 'lessons'       => 'required|string',
                // 'parent_number' => 'required',
                // 'status'       => 'required|string',
                
    
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
                    'school'       => $request->get('school'),
                    'educations'       => $request->get('educations'),
                    'events'       => $request->get('events'),
                    'lessons'       => $request->get('lessons'),
                    'parent_number'       => $request->get('parent_number'),
                    'status'       => $request->get('status'),

                ]);

                Toastr::success('Has been add successfully :)','Success');
                DB::commit();
            }

            return Redirect::route('student/add/page');

        } catch(Exception $e) {
            DB::rollback();
            Toastr::error($e->getMessage(), 'Error');  // Hatanın detayını gösteriyoruz
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
        DB::beginTransaction();
        try {
           
            if (!empty($request->id)) {
                Student::destroy($request->id);
                unlink(storage_path('app/public/student-photos/'.$request->avatar));
                DB::commit();
                Toastr::success('Student deleted successfully :)','Success');
                return redirect()->back();
            }
    
        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('Student deleted fail :)','Error');
            return redirect()->back();
        }
    }
}
