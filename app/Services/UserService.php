<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserService
{
    public static function update(User $user, array $data): User
    {
        $user->update($data['user']);
        $roleId = Role::where('title', $data['roleTitle'])->first()->id;
        $user->roles()->sync([$roleId]);
        return $user;
    }

    public static function deleteUserWithAllRelations(User $user): bool
    {
        return DB::transaction(function () use ($user) {
            try {
                // Порядок ВАЖЕН!

                // 1. Удаляем лайки пользователя (где он автор лайка)
                DB::table('likes')->where('user_id', $user->id)->delete();

                // 2. Удаляем лайки на посты пользователя (где он автор поста)
                $postIds = DB::table('posts')->where('user_id', $user->id)->pluck('id');
                DB::table('likes')->whereIn('post_id', $postIds)->delete();

                // 3. Удаляем комментарии пользователя
//                DB::table('comments')->where('user_id', $user->id)->delete();

                // 4. Удаляем комментарии к постам пользователя
//                DB::table('comments')->whereIn('post_id', $postIds)->delete();

                // 5. Удаляем посты пользователя
                DB::table('posts')->where('user_id', $user->id)->delete();

                // 6. Удаляем связи с ролями
                DB::table('role_user')->where('user_id', $user->id)->delete();

                // 7. Удаляем самого пользователя
                return $user->delete();

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        });
    }
}
