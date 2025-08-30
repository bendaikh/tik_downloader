<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAccountRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MeController extends Controller
{
    use AdminAccessMiddleware;

    public function __construct()
    {
        $this->middleware($this->makeIsAdminMiddleware());
        $this->middleware($this->makeDemoRestrictionMiddleware());
    }

    public function index()
    {
        return view('admin.me');
    }

    public function store(StoreAccountRequest $request)
    {
        /** @var User $user */
        $user = auth()->user();
        $user->fill($request->only(['name', 'email']));
        if($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        $user->save();

        return redirect()->route('admin.me')->with('message', [
            'type' => 'success',
            'content' => 'Account updated successfully',
        ]);
    }
}
