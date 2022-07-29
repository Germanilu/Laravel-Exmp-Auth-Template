<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    public function createTask(Request $request)
    {
        try {
            Log::info('Create Task');

            //Recupero el title por body con $request
            $title = $request->input('title');
            //Recupero el userid por el token 
            $userId = auth()->user()->id;

            //Conecto la variable con el modelo
            $newTask = new Task();
            //Digo que el titulo de newTask es la variable $title
            $newTask->title = $title;
            //Digo que el userid de la variable newTask es el $userId
            $newTask->user_id = $userId;

            $newTask->save();
            return response()->json(
                [
                    "success"=> true,
                    "message"=> 'Task Created'
                ],200
                );
            
        } catch (\Exception $exception) {
            
            Log::error('Error creating task: ' .$exception->getMessage());

            return response()->json(
                [
                    "success"=> true,
                    "message"=> 'Error creating task'
                ],500
                );
        }
        return 'Create Task';
    }



    public function getAllTasksByUserId ()
    {
        try {
            Log::info('Getting Task');
            //Recupero el user ID
            $userId = auth()->user()->id;
            //El ultimo task de la linea abajo le estoy diciendo de atacar al metodo del modelo user que se llama task
            $tasks = User::find($userId)->tasks;

            return response()->json(
                [
                    "success"=> true,
                    "message"=> 'Get all tasks',
                    "data"=> $tasks
                ],200
                );


        } catch (\Exception $exception) {
            Log::error('Error Getting All tasks: ' .$exception->getMessage());

            
            return response()->json(
                [
                    "success"=> true,
                    "message"=> 'Error Getting tasks'
                ],500
                );
        }
    }
}
