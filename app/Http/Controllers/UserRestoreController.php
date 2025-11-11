<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserRestoreController extends Controller
{
    /**
     * Restore a soft-deleted user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        // Temukan pengguna yang di-soft delete
        $user = User::onlyTrashed()->find($id);

        // Jika pengguna tidak ditemukan, kembalikan response 404
        if (!$user) {
            return response()->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        // Restore pengguna
        $user->restore();

        // Kembalikan response sukses
        return response()->json(['message' => 'User restored successfully'], Response::HTTP_OK);
    }
}
