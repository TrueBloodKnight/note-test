<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Info(
 *     title="Notes API",
 *     version="1.0.0",
 *     description="API for managing notes",
 *     @OA\Contact(
 *         email="truebloodknight@gmail.com"
 *     )
 * )
 * @OA\Schema(
 *     schema="Note",
 *     type="object",
 *     title="Note",
 *     description="A note object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Sample Title"),
 *     @OA\Property(property="content", type="string", example="Sample Content"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class NoteController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/notes",
     *     summary="Get all notes",
     *     tags={"Notes"},
     *     @OA\Response(
     *         response=200,
     *         description="List of notes",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Note"))
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Note::all());
    }

    /**
     * @OA\Get(
     *     path="/api/notes/{id}",
     *     summary="Get a single note",
     *     tags={"Notes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Note details",
     *         @OA\JsonContent(ref="#/components/schemas/Note")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Note not found",
     *         @OA\JsonContent(@OA\Property(property="error", type="string", example="Note not found"))
     *     )
     * )
     */
    public function show($id)
    {
        $note = Note::find($id);
        if (!$note) {
            return response()->json(['error' => 'Note not found'], 404);
        }
        return response()->json($note);
    }

    /**
     * @OA\Post(
     *     path="/api/notes",
     *     summary="Create a new note",
     *     tags={"Notes"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "content"},
     *             @OA\Property(property="title", type="string", maxLength=255, example="New Note Title"),
     *             @OA\Property(property="content", type="string", example="New Note Content")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Note created",
     *         @OA\JsonContent(ref="#/components/schemas/Note")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(@OA\Property(property="errors", type="object"))
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $note = Note::create($request->only(['title', 'content']));
        return response()->json($note, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/notes/{id}",
     *     summary="Update an existing note",
     *     tags={"Notes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "content"},
     *             @OA\Property(property="title", type="string", maxLength=255, example="Updated Title"),
     *             @OA\Property(property="content", type="string", example="Updated Content")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Note updated",
     *         @OA\JsonContent(ref="#/components/schemas/Note")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Note not found",
     *         @OA\JsonContent(@OA\Property(property="error", type="string", example="Note not found"))
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(@OA\Property(property="errors", type="object"))
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $note = Note::find($id);
        if (!$note) {
            return response()->json(['error' => 'Note not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $note->update($request->only(['title', 'content']));
        return response()->json($note);
    }

    /**
     * @OA\Delete(
     *     path="/api/notes/{id}",
     *     summary="Delete a note",
     *     tags={"Notes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Note deleted",
     *         @OA\JsonContent(@OA\Property(property="message", type="string", example="Note deleted"))
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Note not found",
     *         @OA\JsonContent(@OA\Property(property="error", type="string", example="Note not found"))
     *     )
     * )
     */
    public function destroy($id)
    {
        $note = Note::find($id);
        if (!$note) {
            return response()->json(['error' => 'Note not found'], 404);
        }

        $note->delete();
        return response()->json(['message' => 'Note deleted']);
    }
}
