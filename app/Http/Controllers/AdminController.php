<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ContentItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function __construct()
    {
        // Simple authorization check
        $this->middleware(function ($request, $next) {
            if (auth()->user()->email !== 'abdullahtahi001@gmail.com') {
                abort(403, 'Unauthorized action.');
            }
            return $next($request);
        });
    }

    public function dashboard()
    {
        $stats = [
            'users' => User::count(),
            'posts' => ContentItem::where('content_type', 'post')->count(),
            'reels' => ContentItem::where('content_type', 'reel')->count(),
            'lives' => ContentItem::where('content_type', 'live')->count(),
        ];
        return view('admin.dashboard', compact('stats'));
    }

    // --- User Management ---
    public function users()
    {
        $users = User::latest()->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function editUser(User $user)
    {
        return view('admin.edit-user', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'display_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'username' => 'required|string|unique:users,username,' . $user->id,
        ]);

        $user->update($validated);
        return redirect()->route('admin.users')->with('success', 'User updated successfully.');
    }

    public function deleteUser(User $user)
    {
        // Prevent deleting yourself if you are the admin
        if ($user->email === 'abdullahtahi001@gmail.com') {
            return back()->with('error', 'You cannot delete the super admin.');
        }

        $user->delete();
        return redirect()->route('admin.users')->with('success', 'User deleted successfully.');
    }

    // --- Content Management ---
    public function posts()
    {
        $posts = ContentItem::where('content_type', 'post')->latest()->paginate(20);
        return view('admin.posts', compact('posts'));
    }

    public function reels()
    {
        $reels = ContentItem::where('content_type', 'reel')->latest()->paginate(20);
        return view('admin.reels', compact('reels'));
    }

    public function lives()
    {
        $lives = ContentItem::where('content_type', 'live')->latest()->paginate(20);
        return view('admin.lives', compact('lives'));
    }

    public function editContent(ContentItem $contentItem)
    {
        return view('admin.edit-content', compact('contentItem'));
    }

    public function updateContent(Request $request, ContentItem $contentItem)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'visibility' => 'required|in:public,private',
        ]);

        $contentItem->update($validated);
        
        $route = 'admin.posts';
        if ($contentItem->content_type === 'reel') $route = 'admin.reels';
        if ($contentItem->content_type === 'live') $route = 'admin.lives';

        return redirect()->route($route)->with('success', 'Content updated successfully.');
    }

    public function deleteContent(ContentItem $contentItem)
    {
        if ($contentItem->media_path) {
            Storage::disk('public')->delete($contentItem->media_path);
        }
        
        $type = $contentItem->content_type;
        $contentItem->delete();

        $route = 'admin.posts';
        if ($type === 'reel') $route = 'admin.reels';
        if ($type === 'live') $route = 'admin.lives';

        return redirect()->route($route)->with('success', ucfirst($type) . ' deleted successfully.');
    }
}
