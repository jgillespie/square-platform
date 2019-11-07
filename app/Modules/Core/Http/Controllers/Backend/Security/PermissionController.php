<?php

namespace App\Modules\Core\Http\Controllers\Backend\Security;

use App\Http\Controllers\Controller;
use App\Modules\Core\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    /**
     * Display a listing of the permission.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('core::backend.security.permission.index');
    }

    /**
     * Process permission index datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexData()
    {
        $model = Permission::query();

        return DataTables::eloquent($model)
            ->make(true);
    }
}
