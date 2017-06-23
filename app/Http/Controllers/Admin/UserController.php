<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\User;
use App\Visitor;
use Validation;
use Hash;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.user.list', ['users' => User::paginate(10) ]);
    }

    public function clients(){
        return view('admin.user.listclient',['visitors' => Visitor::paginate(30) ]);
    }

    public function saveKlantNr(Request $request){
        $klantNr = $request->input('klantNr');
        $visitorId = $request->input('visitorId');

        $visitor = Visitor::where('id', $visitorId)->first();
        $visitor->klantNr = $klantNr;
        $visitor->save();

        return "success";
    }

    function passReset(Request $request){
        $visitor = Visitor::find($request->input('visitorId'));
        $bedrijfsnaam = $visitor->bedrijfsnaam;
        $randCijfer = rand(1, 999);
        $bedrijfsnaamForPass = preg_replace("/[^A-Za-z0-9]/", "", $bedrijfsnaam);

        $newPass = sha1(ucfirst(strtolower($bedrijfsnaamForPass)) . $randCijfer);
        $visitor->password = $newPass;

        $visitor->save();

        $html = '<h2>Nieuw wachtwoord</h2><table style="border:none;"><thead style="border:none;"><tr height="20px" style="border:none;"><td style="border:none;">E-mail: ' . $visitor->email . '</td></tr><tr height="20px" style="border:none;"><td style="border:none;">Nieuw wachtwoord: ' . $newPass . '</td></tr></thead></table>';
        $onderwerpOwner = "Nieuw wachtwoord Hermic BVBA";

        $headers = array('From: no-reply@hermicdev.be',"Reply-To: no-reply@hermicdev.be", "Content-Type: text/html; charset=ISO-8859-1");
        $headers = implode("\r\n", $headers);

        //mail to owner
        mail($visitor->email, $onderwerpOwner, $html, $headers);
    }

    public function clientDelete($visitorId){
        $visitor = Visitor::find($visitorId);

        $visitor->delete();

        return redirect('/admin/clients')->with('success', 'Successfully deleted!');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
        	'name'		=> 'required',
        	'email'		=> 'required|email|unique:users',
        	'password'	=> 'required'
        ]);

        $data = [
        	'name' 		=> $request->input('name'),
        	'email' 	=> $request->input('email'),
        	'password'	=> Hash::make($request->input('password'))
        ];

        User::create($data);

        return redirect()->route('admin.user.index')->with('success', 'Successfully created!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('admin.user.edit', ['user' => User::find($id)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
        	'name'		=> 'required',
        	'email'		=> 'required|email',
        ]);

        $data = [
        	'name' 		=> $request->input('name'),
        	'email' 	=> $request->input('email')
        ];

        if ($request->has('password')) {
        	$data['password'] = Hash::make($request->input('password'));
        }

        User::find($id)->update($data);

        return redirect()->route('admin.user.index')->with('success', 'Successfully updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();

        return redirect()->route('admin.user.index')->with('success', 'Successfully deleted!');
    }
}
