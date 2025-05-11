<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use Dom\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class commentController extends Controller
{
    public function postComment(Request $request, $appointment_id)
    {

        
        $validator = Validator::make($request->all(), [
            "rating" => "required",
            "comment" => "required",
        ]);


      
        if ($validator->fails()) {
            
            
            return response()->json([
                "status" => "failed",
                "data" => $validator->errors(),
            ], 422);
        }


        $comment = new Comments();
       
        $comment->appointment_id = $appointment_id;
        $comment->rating = $request->rating;
        $comment->comment = $request->comment;
        
        $comment->save();
        return response()->json($comment, 200);


    }

}
