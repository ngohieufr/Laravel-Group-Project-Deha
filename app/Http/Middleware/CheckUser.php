<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;  // Thêm dòng này để import User model

class CheckUser
{
    public function handle(Request $request, Closure $next, $action = null)
    {
        $user = Auth::user(); // Lấy người dùng hiện tại
        
        // Kiểm tra nếu người dùng không đăng nhập
        if (!$user) {
            return redirect('login');
        }

        // Lấy người dùng mục tiêu (targetUser) từ route (Giả sử bạn truyền 'user' trong route)
        $targetUserId = $request->route('user'); // 'user' là tên tham số trong route (có thể khác nếu bạn dùng tên khác)
        
        // Kiểm tra nếu không có người dùng mục tiêu
        if ($targetUserId) {
            $targetUser = User::findOrFail($targetUserId); // Tìm người dùng mục tiêu
        }

        // Kiểm tra quyền dựa trên action (create, edit, delete, view)
        if ($action === 'create' && !$user->can('create', User::class)) {
            abort(403, 'You do not have permission to create users.');
        }

        // Kiểm tra quyền edit với người dùng mục tiêu
        if ($action === 'edit' && isset($targetUser) && !$user->can('edit', [$targetUser])) {
            abort(403, 'You do not have permission to edit this user.');
        }

        if ($action === 'delete' && isset($targetUser) && !$user->can('delete', [$targetUser])) {
            abort(403, 'You do not have permission to delete this user.');
        }

        if ($action === 'view' && !$user->can('view', User::class)) {
            abort(403, 'You do not have permission to view users.');
        }

        // Nếu người dùng có quyền, tiếp tục yêu cầu
        return $next($request);
    }
}
