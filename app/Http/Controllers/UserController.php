<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Role;
use App\Http\Requests\CreateUserRequest;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $user;
    protected $role;

    public function __construct(User $user){
        $this->user = $user;
    }
     
    public function index(Request $request)
    {
        $query = $this->user->latest('id');
    
        // Nếu có từ khóa tìm kiếm, áp dụng scope search
        if ($request->has('search') && !empty($request->search)) {
            $query->search($request->search);
        }
    
        // Paginate kết quả
        $users = $query->paginate(5);
    
        return view('users.index', compact('users'));
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', User::class);
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateUserRequest $request)
    {
        $this->authorize('create', User::class);
        
        $dataCreate = $request->all();
        $user = User::create($dataCreate);
    
        // Kiểm tra nếu role_id tồn tại trước khi gắn vai trò
        if ($request->has('role_id')) {
            $user->roles()->attach($request->role_id);
        }
    
        return redirect()
            ->route('users.index')
            ->with(['message' => "Create {$user->name} Success"]);
    }
    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Tìm người dùng theo ID và nạp dữ liệu liên quan đến roles
        $user = User::with('roles')->findOrFail($id);
    
        // Trả về view với dữ liệu người dùng và các vai trò của họ
        return view('users.view', compact('user'));
    }
    
    
    


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        
        $roles = Role::all();
        $user = $this->user->findOrFail($id);
        
        try {

            $this->authorize('edit', $user);

        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {

            // Quay lại trang index nếu không có quyền

            return redirect()->route('users.index')->with('error', 'You do not have permission to edit this user.');

        }

        $rolesOfUser = $user->roles;
        return view('users.edit', compact('user', 'roles', 'rolesOfUser'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        // Tìm người dùng
        $user = $this->user->findOrFail($id);
        $this->authorize('edit', $user);
    
        // Chuẩn bị dữ liệu để cập nhật
        $dataUpdate = $request->except('password', 'role_id');
        if ($request->password) {
            $dataUpdate['password'] = Hash::make($request->password);
        }
    
        // Cập nhật thông tin người dùng
        $user->update($dataUpdate);
    
        // Cập nhật vai trò của người dùng
        if ($request->has('role_id')) {
            $user->roles()->sync($request->role_id); // Đồng bộ vai trò mới
        }
    
        return redirect()->route('users.index')->with(['message' => "Update {$user->name} Success"]);
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $user = $this->user->findOrFail($id);

        $this->authorize('delete', $user);
        // Gỡ bỏ các vai trò của người dùng trước khi xóa
        $user->roles()->detach();
    
        // Xóa người dùng
        $user->delete();
    
        return redirect()->route('users.index')->with(['message' => "Delete Success"]);
    }
    
}
