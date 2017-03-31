<?php

namespace App\Http\Controllers;

//use App\OJLog;
use App\Executable;
use Illuminate\Http\Request;
use Storage;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;

class ExecutableController extends Controller
{

    public function getExecutableDashboard(Request $request)
    {
        return View::make('dashboard.executable');
    }

    /**
     * @function addExecutable
     * @input $request
     *
     * @return json with add result
     * @description add executable;if success return success else return fail
     */
    public function addExecutable(Request $request)
    {
        $input = $request->input();
        $execId = $input['execid'];
        $execFile = $request->file('file');
        $execType = $input['type'];
        if ($execType == "lang" || $execType == "compare" || $execType == "run") {
            if (isset($execFile)) {
                if (substr($execFile->getMimeType(), 0, 15) == "application/zip") {
                    if (Executable::where('execid', $execId)->first()) {
                        $data['status'] = "fail";
                        $data['ErrorInfo'] = $execId . " existed!";
                    } else {
                        $executableObj = new Executable;
                        $executableObj->execid = $execId;
                        $executableObj->type = $execType;
                        $executableObj->md5sum = md5_file($execFile);
                        $executableObj->save();
                        Storage::put(
                            'executables/' . $execId . ".zip",
                            file_get_contents($request->file('file')->getRealPath())
                        );
                        $data['status'] = "success";
                    }
                } else {
                    $data['status'] = "fail";
                    $data['ErrorInfo'] = "Input File type must be .zip!";
                }
            } else {
                $data['status'] = "fail";
                $data['ErrorInfo'] = "No input file!";
            }
        } else {
            $data['status'] = "fail";
            $data['ErrorInfo'] = "Type must be one of run, compare and lang!";
        }
        return response()->json($data);
    }

    /**
     * @function getExecutable
     * @input
     *
     * @return json
     * @description get executable dashboard page
     */
    public function getExecutable()
    {
        $data = Executable::all();
        return response()->json($data);
    }

    /**
     * @function deleteExecutable
     * @input $execId
     *
     * @return json with delete result
     * @description delete executable
     */
    public function deleteExecutable(Request $request)
    {
        $execId = $request->input('execid');
        Executable::where('execid', $execId)->delete();
        if (Storage::has('executables/' . $execId . '.zip')) {
            Storage::delete('executables/' . $execId . '.zip');
            if (Storage::has('executables/' . $execId . '.zip')) {
                $data['status'] = "fail";
            } else
                $data['status'] = "success";
        } else {
            $data['status'] = "fail";
            $data['ErrorInfo'] = "File isn't exist!";
        }
        return response()->json($data);
    }

    /**
     * @function updateExecutableByExecId
     * @input $request
     *
     * @return json with update result
     * @description update executable
     */
    public function updateExecutable(Request $request)
    {
        $input = $request->input();
        $execId = $input['execid'];
        $execIdEdit = $input['execid_edit'];
        $execType = $input['type_edit'];
        $execFile = $request->file('file');
        if (Executable::where('execid', $execId)->first()) {
            if ($execType == "lang" || $execType == "compare" || $execType == "run") {
                Executable::where('execid', $execId)->update(['execid' => $execIdEdit]);
                Executable::where('execid', $execIdEdit)->update(['type' => $execType]);
                if (isset($execFile) && substr($execFile->getMimeType(), 0, 15) == "application/zip") {
                    Executable::where('execid', $execIdEdit)->update(['md5sum' => md5_file($execFile)]);
                    //Storage::delete('executables/' . $execId . ".zip");
                    Storage::put(
                        'executables/' . $execId . ".zip",
                        file_get_contents($request->file('file')->getRealPath())
                    );
                }
                $data['status'] = "success";
            } else {
                $data['status'] = "fail";
                $data['ErrorInfo'] = "Type must be one of run, compare and lang!";
            }
        } else {
            $data['status'] = "fail";
            $data['ErrorInfo'] = $execId . " didn't exist!";
        }
        return response()->json($data);
    }

    /**
     * @function getExecutableFile
     * @input $request, $execId
     * @return response
     * @description get executable file to download
     */
    public function getExecutableFile(Request $request, $execId)
    {
        $file = Storage::get('executables/' . $execId . '.zip');
        $type = Storage::mimeType('executables/' . $execId . '.zip');
        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);
        $response->header("Cache-Control", "max-age=60000");
        return $response;
    }
}