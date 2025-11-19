<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    public function getall() 
    {
        return response()->json([
            'status' => 'success',
            'data' => Task::all()
        ]);
    }

    public function store(Request $request) 
    {
        $description = $request->description;
        $status = $request->status;

        // Vérification du status
        if (!in_array($status, ['pending', 'done'])) {
            return response()->json([
                'status' => 'error',
                'message' => "Le status doit être 'pending' ou 'done'."
            ], 422);
        }

        if (!$description) {
            return response()->json([
                'status' => 'error',
                'message' => "La description est obligatoire."
            ], 422);
        }

        $task = Task::create([
            'description' => $description,
            'status' => $status
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $task
        ]);
    }

    public function update(Request $request, $id) 
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'status' => 'success',
                'data' => "La tâche avec l'id $id n'est pas dans la base."
            ]);
        }

        $description = $request->description;
        $status = $request->status;

        // Vérification du status
        if ($status && !in_array($status, ['pending', 'done'])) {
            return response()->json([
                'status' => 'error',
                'message' => "Le status doit être 'pending' ou 'done'."
            ], 422);
        }

        $task->update($request->only('description', 'status'));

        return response()->json([
            'status' => 'success',
            'data' => $task
        ]);
    }

    public function destroy($id) 
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'status' => 'success',
                'data' => "La tâche avec l'id $id n'est pas dans la base."
            ]);
        }

        $task->delete();

        return response()->json([
            'status' => 'success',
            'data' => "Task Deleted"
        ]);
    }
}
