<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\User;
use App\Models\FileMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FileController extends Controller
{
    // ফাইল লিস্ট ও ফিল্টারিং
    public function index(Request $request)
    {
        $query = File::with('currentDesk');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $files = $query->latest()->paginate(20);
        return view('files.index', compact('files'));
    }

    // নতুন ফাইল এন্ট্রি
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string',
            'case_number' => 'nullable|string',
            'badi_name' => 'nullable|string',
            'badi_phone' => 'nullable|string',
            'bibadi_name' => 'nullable|string',
            'bibadi_phone' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);

        $file = File::create(array_merge($validated, [
            'status' => File::STATUS_INITIATED,
            'current_desk_id' => Auth::id(), // শুরুতে নিজের ডেস্কে থাকবে
            'created_by' => Auth::id(),
        ]));

        // Initial Movement Log
        FileMovement::create([
            'file_id' => $file->id,
            'from_user_id' => Auth::id(),
            'to_user_id' => Auth::id(),
            'is_received' => true,
            'received_at' => now(),
            'remarks' => 'ফাইল রুজু করা হয়েছে'
        ]);

        return redirect()->route('files.show', $file->id)->with('success', 'ফাইল এন্ট্রি সফল হয়েছে। QR প্রিন্ট করুন।');
    }

    // অন্য ডেস্কে ফাইল ট্রান্সফার (Dashboard থেকে)
    public function transfer(Request $request, File $file)
    {
        $request->validate(['to_user_id' => 'required|exists:users,id']);

        FileMovement::create([
            'file_id' => $file->id,
            'from_user_id' => Auth::id(),
            'to_user_id' => $request->to_user_id,
            'remarks' => $request->remarks,
        ]);

        // Status update for current desk (Pending Receive)
        $file->update([
            'current_desk_id' => $request->to_user_id,
        ]);

        return back()->with('success', 'ফাইলটি পাঠানো হয়েছে। স্ক্যান করার অপেক্ষায় আছে।');
    }
}