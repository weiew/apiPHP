<?php
/**
 * Created by PhpStorm.
 * User: lange
 * Date: 2018/11/3
 * Time: 下午9:53
 */

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PassportController extends Controller
{

    public $successStatus = 200;

    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            //'email' => 'required|email|exists:users,email',
            'email' => 'required|email|exists:users,email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            //return response()->json(['error'=>$validator->errors()], 401);
            return self::output($validator->errors(), 417,'valid Error');
        }

        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')->accessToken;
            //return response()->json(['success' => $success], $this->successStatus);
            return self::output($success, $this->successStatus);
        }
        else{
            //return response()->json(['error'=>'Unauthorised'], 401);
            return self::output([], 401,'Unauthorised');
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            //'email' => 'required|email|exists:users,email',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            //return response()->json(['error'=>$validator->errors()], 401);
            return self::output($validator->errors(), 401,'valid Error');
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['name'] =  $user->name;

        //return response()->json(['success'=>$success], $this->successStatus);
        return self::output($success, $this->successStatus);
    }

    /**
     * details api
     *
     * @return \Illuminate\Http\Response
     */
    public function userDetails()
    {
        $user = Auth::user();
        //return response()->json(['success' => $user], $this->successStatus);
        return self::output($user, $this->successStatus);
    }
}
