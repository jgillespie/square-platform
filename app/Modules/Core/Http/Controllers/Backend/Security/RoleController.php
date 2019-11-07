<?php

namespace App\Modules\Core\Http\Controllers\Backend\Security;

use App\Http\Controllers\Controller;
use App\Modules\Core\Models\Permission;
use App\Modules\Core\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('core.sroledeny')->only('edit', 'update', 'destroy');
    }

    /**
     * Display a listing of the role.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('core::backend.security.role.index');
    }

    /**
     * Process role index datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexData()
    {
        $model = Role::query();

        return DataTables::eloquent($model)
            ->addColumn('actions', function ($model) {
                $editRoute = route('backend.security.role.edit', ['role' => $model->id]);
                $editText = __('Edit');
                $html = <<<HTML
                    <a class="btn btn-primary btn-sm btn-icon-split mr-2" href="$editRoute">
                        <span class="icon text-white-50"><i class="far fa-edit"></i></span>
                        <span class="text">$editText</span>
                    </a>
HTML;

                $destroyRoute = route('backend.security.role.destroy', ['role' => $model->id]);
                $destroyText = __('Delete');
                $html .= <<<HTML
                    <a class="btn btn-danger btn-sm btn-icon-split" href="$destroyRoute"
                        onclick="event.preventDefault(); deleteRole($model->id, '$destroyRoute');">
                        <span class="icon text-white-50"><i class="far fa-trash-alt"></i></span>
                        <span class="text">$destroyText</span>
                    </a>
HTML;

                return $html;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    /**
     * Show the form for creating a new role.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::all();

        return view('core::backend.security.role.create', [
            'permissions' => $permissions,
        ]);
    }

    /**
     * Store a newly created role in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $permissions = Permission::pluck('id')->toArray();

        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:roles',
            'description' => 'required|string|max:255',
            'permissions' => [
                'sometimes',
                'array',
                Rule::in($permissions),
            ],
        ]);

        $role = new Role();
        $role->name = $validatedData['name'];
        $role->description = $validatedData['description'];
        $role->save();

        if ($request->has('permissions')) {
            $role->permissions()->attach($validatedData['permissions']);
        }

        return redirect()
            ->route('backend.security.role.index')
            ->with('status', __('The role has been created.'));
    }

    /**
     * Show the form for editing the specified role.
     *
     * @param  \App\Modules\Core\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $permissions = Permission::all();

        return view('core::backend.security.role.edit', [
            'role' => $role,
            'permissions' => $permissions,
        ]);
    }

    /**
     * Update the specified role in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Modules\Core\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $permissions = Permission::pluck('id')->toArray();

        $validatedData = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles')->ignore($role->id),
            ],
            'description' => 'required|string|max:255',
            'permissions' => [
                'sometimes',
                'array',
                Rule::in($permissions),
            ],
        ]);

        $role->name = $validatedData['name'];
        $role->description = $validatedData['description'];
        $role->save();

        if ($request->has('permissions')) {
            $role->permissions()->sync($validatedData['permissions']);
        } else {
            $role->permissions()->sync([]);
        }

        return redirect()
            ->back()
            ->with('status', __('The role has been updated.'));
    }

    /**
     * Remove the specified role from storage.
     *
     * @param  \App\Modules\Core\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()
            ->back()
            ->with('status', __('The role has been deleted.'));
    }
}
