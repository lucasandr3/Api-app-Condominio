<?php


namespace App\Repositories;

use App\Models\User;
use App\Models\Wall;
use App\Models\WallLike;

class WallRepository
{
    public function walls(): array
    {
        $user = auth()->user();
        $walls = Wall::all();

        foreach ($walls as $wallKey => $wallValue) {
            $walls[$wallKey]['likes'] = 0;
            $walls[$wallKey]['liked'] = false;

            $likes = WallLike::where('id_wall', $wallValue['id'])->count();
            $walls[$wallKey]['likes'] = $likes;

            $meLikes = WallLike::where('id_wall', $wallValue['id'])
                ->where('id_user', $user['id'])
                ->count();

            if($meLikes > 0) {
                $walls[$wallKey]['liked'] = true;
            }
        }

        return ['error' => '', 'list' => $walls];
    }

    public function likeWall($data)
    {
        $user = auth()->user();

        $meLikes = WallLike::where('id_wall', $data['id'])
            ->where('id_user', $user['id'])
            ->count();

        if($meLikes > 0) {
            WallLike::where('id_wall', $data['id'])
                ->where('id_user', $user['id'])
                ->delete();
            $liked = false;
        } else {
            $newLike = new WallLike();
            $newLike->id_wall = $data['id'];
            $newLike->id_user = $user['id'];
            $newLike->save();
            $liked = true;
        }

        $likes = WallLike::where('id_wall', $data['id'])->count();
        return ['error' => '', 'likes' => $likes, 'liked' => $liked];
    }
}
