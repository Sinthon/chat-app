<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use App\Events\NewMessage;
use App\Models\Room;
use Inertia\Inertia;
use Illuminate\Support\Facades\Response;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $messages = Message::with(['user', 'room'])->orderBy('created_at', 'desc')->get();
        return Inertia::render("Chat/Index",[
            'messages' => $messages
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

        /**
     * Render chat page with Inertia.
     * 
     * @return \Inertia\Response
     */
    public function renderChat(Request $request, $room = null)
    {
        $rooms = Room::all()->pluck('name')->toArray();
        if ($room === 'general') return redirect()->route('chat');
        if (!$room) $room = "general";
        if (!in_array($room, $rooms)) abort(404);
        return Inertia::render('Chat', [
            'chatData' => [
                'messages' => Room::where('name', $room)->first()->messages()->with('user')->get(),
                'rooms' => $rooms,
                'room' => $room
            ]
        ]);
    }

    /**
     * Handle message post request.
     *  
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required',
            'room_id' => 'required',
        ]);
        $message = Message::create($validated);
        broadcast(new NewMessage($message))->toOthers();
        
        return Response::json(['ok' => true]);
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required',
            'room_id' => 'required',
        ]);

        $message = Message::create($validated);

        broadcast(new NewMessage($message))->toOthers();
        
        if (request()->wantsJson()) {
            return response([
                'message' => $message
            ]);
        }

        return redirect()->action(
            [ChatController::class, 'index']
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        //
    }
}
