<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Message;
use Illuminate\Http\Request;

class HelpdeskController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/helpdesk",
     *     summary="List all tickets",
     *     tags={"Helpdesk"},
     *     @OA\Response(response=200, description="List of tickets")
     * )
     */
    public function index()
    {
        return Subject::with(['messages', 'user'])->get();
    }

    /**
     * @OA\Post(
     *     path="/api/helpdesk",
     *     summary="Create new ticket",
     *     tags={"Helpdesk"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="content", type="string"),
     *             @OA\Property(property="user_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Ticket created")
     * )
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'user_id' => 'required|exists:users,id'
        ]);

        $subject = Subject::create([
            'title' => $data['title'],
            'user_id' => $data['user_id'],
            'ticket_id' => 'TIC-' . Str::random(8),
            'status' => 'new'
        ]);

        Message::create([
            'subject_id' => $subject->id,
            'content' => $data['content'],
            'direction' => 'incoming',
            'email' => auth()->user()->email
        ]);

        return response()->json($subject->load('messages'), 201);
    }

    /**
     * @OA\Get(
     *     path="/api/helpdesk/{id}",
     *     summary="Get ticket details",
     *     tags={"Helpdesk"},
     *     @OA\Parameter(name="id", in="path", required=true),
     *     @OA\Response(response=200, description="Ticket details")
     * )
     */
    public function show(Subject $subject)
    {
        return $subject->load(['messages', 'user']);
    }

    /**
     * @OA\Post(
     *     path="/api/helpdesk/{id}/reply",
     *     summary="Add reply to ticket",
     *     tags={"Helpdesk"},
     *     @OA\Parameter(name="id", in="path", required=true),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="content", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Reply added")
     * )
     */
    public function reply(Request $request, Subject $subject)
    {
        $data = $request->validate(['content' => 'required|string']);

        $message = Message::create([
            'subject_id' => $subject->id,
            'content' => $data['content'],
            'direction' => 'incoming',
            'email' => auth()->user()->email
        ]);

        return response()->json($message, 201);
    }
}
