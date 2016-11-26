<?php

namespace App\Http\Controllers;

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
    /**
     * @function addExecutable
     * @input $request
     *
     * @return json with add result
     * @description add executable;if success return success else return fail
     */
    public function addExecutable(Request $request)
    {
        $execId = $request->execid;
        $execFile = $request->file('file');
        $execType = $request->type;
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
        $execId = $request->execid;
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
     * @function updateExecutableByExeId
     * @input $request
     *
     * @return json with update result
     * @description update executable
     */
    public function updateExecutable(Request $request)
    {
        $execId = $request->execid;
        $exeIdEdit = $request->execidEdit;
        $exeType = $request->typeEdit;
        if (Executable::where('execid', $execId)->first()) {
            if ($exeType == "lang" || $exeType == "compare" || $exeType == "run") {
                Executable::where('execid', $execId)->update(['execid' => $exeIdEdit]);
                Executable::where('execid', $exeIdEdit)->update(['type' => $exeType]);
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