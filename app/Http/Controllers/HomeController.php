<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(Auth::user()->hasrole('user')){
            return view('home');
        }
        else
        return redirect()->route('manager');

    }

    public function manager(){
        $messages = Application::paginate(10);
        return view('manager', compact('messages'));
    }

    public function message_store(Request $request){
        // dd($request);
        $validated = $request->validate([
            'file' => 'required|mimes:png,jpeg,jpg,pdf',
        ]);
        $message = new Application();
        if (request()->hasFile('file')){
            $file = $request->file('file');
            $filename = time().'.'.$file->GetClientOriginalExtension();
            $path = public_path('files/');
            $file->move($path, $filename);
            $message->file = $filename;
        }else{
            return redirect()->back();
        }
        $message->username = Auth::user()->name;
        $message->email = Auth::user()->email;
        $message->theme = $request->theme;
        $message->text = $request->message;

        $message->save();

        $minutes = 60*24;
        Cookie::queue('control', $message->username, $minutes);

        return redirect()->route('home')->with(['success' => 'Your message sent successfully']);
    }

    public function change_status($id){

        $status = Application::find($id);
        $status->status = 1;
        $status->save();
        return redirect()->route('manager')->with(['success' => 'Message has been answered successfully']);
    }

}
