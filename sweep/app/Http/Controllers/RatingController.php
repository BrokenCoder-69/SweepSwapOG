<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    // User rates another user
    public function store(Request $request, $ratedId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        // prevent self rating
        if ($request->user()->id == $ratedId) {
            return response()->json(['error' => 'You cannot rate yourself'], 403);
        }

        $rating = Rating::updateOrCreate(
            [
                'rater_id' => $request->user()->id,
                'rated_id' => $ratedId
            ],
            [
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]
        );

        return response()->json(['success' => true, 'data' => $rating]);
    }

    // Get ratings of a user
    public function show($userId)
    {
        $ratings = Rating::where('rated_id', $userId)
            ->with('rater:id,name,email')
            ->get();

        $avg = $ratings->avg('rating');
        $sum = $ratings->sum('rating');
        $count = $ratings->count();



        return response()->json([
            'average_rating' => round($avg,2),
            'total_ratings' => $count,
            'sum_ratings'    => $sum,
            'ratings' => $ratings
        ]);
    }
}
